<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Group;
use App\Models\Publication;
use App\Models\Questionnaire;
use App\Models\Subgroup;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Facades\Gate;

use function PHPUnit\Framework\isNull;

class QuestionnaireController extends Controller
{
    public function destroy(Questionnaire $Questionnaire)
    {
        Gate::authorize('admin-level');
        $Questionnaire->delete();
    }

    public function vote(Questionnaire $Questionnaire, Request $request)
    {
        if ($Questionnaire->type = 'closed' && count($Questionnaire->userAnswers) > 0) {
            return response()->json(['message' => 'Action failed'], 400);
            exit;
        }


        $answer = Answer::create([
            'user_id' => Auth::user()->id,
            'value' => $request->value,
        ]);

        $Questionnaire->answers()->attach($answer);
    }

    public function results(Questionnaire $Questionnaire)
    {
        Gate::authorize('admin-level');

        $data = [];
        //jeżeli jest ograniczona widoczność publikacji, to wykresy dla wszystkich grup, podgrup itp.
        if ($Questionnaire->publication->restrictedVisibility) {

            //jeżeli jest kilka grup, albo kilka podgrup to dorzucamy ogólne podsumowanie całości:
            if (count($Questionnaire->publication->groups) + count($Questionnaire->publication->groups)) {
                $data = [];
                $entitledToVote = $Questionnaire->publication->restrictedVisibilityUserIds();
                $votes = $Questionnaire->answers;



                $data[] = [
                    'name'  => 'Ogólne podsumowanie',
                    'entitled' => count($Questionnaire->publication->restrictedVisibilityUserIds()),
                    'yes' =>  count($Questionnaire->answers()->where('value', 1)->get()),
                    'no' =>  count($Questionnaire->answers()->where('value', 0)->get()),
                    'held' => (count($entitledToVote) - count($votes)),
                ];
            }

            foreach ($Questionnaire->publication->groups ?? [] as $group) {
                $userIds = $group->members();
                $entitledUsers = count($userIds);
                $votes = count($Questionnaire->answers->whereIn('user_id', $userIds));


                $data[] = [
                    'name'  => $group->name,
                    'entitled' => $entitledUsers,
                    'yes' =>  count($Questionnaire->answers()->where('value', 1)->whereIn('user_id',  $userIds)->get()),
                    'no' =>  count($Questionnaire->answers()->where('value', 0)->whereIn('user_id',  $userIds)->get()),
                    'held' => $entitledUsers - $votes,
                    'group' => $group->id,
                    'subgroup' => null,
                ];

                foreach ($group->subgroups ?? [] as $subgroup) {
                    $subgroupUserIds = $subgroup->members();
                    $subgroupEntitledUsers = count($subgroupUserIds);
                    $subgroupVotes = count($Questionnaire->answers->whereIn('user_id', $subgroupUserIds));

                    $data[] = [
                        'name'  => $group->name . ' : ' . $subgroup->name,
                        'entitled' => $subgroupEntitledUsers,
                        'yes' =>  count($Questionnaire->answers()->where('value', 1)->whereIn('user_id',  $subgroupUserIds)->get()),
                        'no' =>  count($Questionnaire->answers()->where('value', 0)->whereIn('user_id',  $subgroupUserIds)->get()),
                        'held' => $subgroupEntitledUsers - $subgroupVotes,
                        'group' => $group->id,
                        'subgroup' => $subgroup->id,
                    ];
                }

                //użytkownicy grupy,którzy nie są przypisani do żadnej podgrupy.
                if (count($group->users()->wherePivot('subgroup_id', null)->get()) > 0) {
                    $idsWithoutGroup = $group->users()->wherePivot('subgroup_id', null)->pluck('users_chor.id')->toArray();
                    $withoutSubgroupEntitledUsers = count($idsWithoutGroup);
                    $withoutSubgroupVotes = count($Questionnaire->answers->whereIn('user_id', $idsWithoutGroup));

                    $data[] = [
                        'name'  => $group->name . ' : Bez przypisania',
                        'entitled' => $withoutSubgroupEntitledUsers,
                        'yes' =>  count($Questionnaire->answers()->where('value', 1)->whereIn('user_id',  $idsWithoutGroup)->get()),
                        'no' =>  count($Questionnaire->answers()->where('value', 0)->whereIn('user_id',  $idsWithoutGroup)->get()),
                        'held' => $withoutSubgroupEntitledUsers - $withoutSubgroupVotes,
                        'group' => $group->id,
                        'subgroup' => null,
                        'unassigned' => 1,
                    ];
                }
            }

            foreach ($Questionnaire->publication->subgroups ?? [] as $subgroup) {
                $subgroupUserIds = $subgroup->members();
                $subgroupEntitledUsers = count($subgroupUserIds);
                $subgroupVotes = count($Questionnaire->answers->whereIn('user_id', $subgroupUserIds));

                $data[] = [
                    'name'  => $subgroup->name,
                    'entitled' => $subgroupEntitledUsers,
                    'yes' =>  count($Questionnaire->answers()->where('value', 1)->whereIn('user_id',  $subgroupUserIds)->get()),
                    'no' =>  count($Questionnaire->answers()->where('value', 0)->whereIn('user_id',  $subgroupUserIds)->get()),
                    'held' => $subgroupEntitledUsers - $subgroupVotes,
                    'group' => null,
                    'subgroup' => $subgroup->id,
                ];
            }
        } else { //nie ma ograniczonej widoczności
            $data = [];
            $entitledToVote = $Questionnaire->publication->restrictedVisibilityUserIds();
            $votes = $Questionnaire->answers;



            $data[] = [
                'name'  => 'Ogólne podsumowanie',
                'entitled' => count($Questionnaire->publication->restrictedVisibilityUserIds()),
                'yes' =>  count($Questionnaire->answers()->where('value', 1)->get()),
                'no' =>  count($Questionnaire->answers()->where('value', 0)->get()),
                'held' => (count($entitledToVote) - count($votes)),
            ];
        }



        return view('questionnaires.results', [
            'questionnaire' => $Questionnaire,
            'data' => $data,
        ]);
    }

    public function resultsModal(Request $request)
    //wyniki głosowania widoczne w modalu
    {
        if ($request->ajax()) {

            //dane zawieracjące grupę
            if($request->groupId && !($request->subGroupId))
            {
                $group = Group::find($request->groupId);
                $userIds = $group->members();

                $questionnaire = Questionnaire::find($request->Questionnaire);

                $data = $questionnaire->answers()->whereIn('user_id',  $userIds)->with('user')->get();

            }
            //dane zawieracjące podgrupę
            if($request->subGroupId)
            {
                $subGroup = Subgroup::find($request->subGroupId);

                $subgroupUserIds = $subGroup->members();

                $questionnaire = Questionnaire::find($request->Questionnaire);

                $data = $questionnaire->answers()->whereIn('user_id',  $subgroupUserIds)->with('user')->get();

            }

            //dane zawieracjące użytkowników grupy, którzy nie są przypisani do żadnej podgrupy
            if($request->groupId && $request->unassigned)
            {
                $group = Group::find($request->groupId);
                $idsWithoutGroup = $group->users()->wherePivot('subgroup_id', null)->pluck('users.id')->toArray();

                $questionnaire = Questionnaire::find($request->Questionnaire);

                $data = $questionnaire->answers()->whereIn('user_id',  $idsWithoutGroup)->with('user')->get();

            }

             //dane "ogolne podsumowanie" (jeśli jest kilka różnych grup w zakresie widoczności)
             if(!($request->groupId) && !($request->subGroupId) && !($request->unassigned))
             {
                $questionnaire = Questionnaire::find($request->Questionnaire);
                $entitledToVote = $questionnaire->publication->restrictedVisibilityUserIds();

                 $data = $questionnaire->answers()->whereIn('user_id',  $entitledToVote)->with('user')->get();

             }




            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('answer', function($row){
                    return ($row->value) ? 'Tak' : 'Nie';
                })
                ->rawColumns(['answer'])
                // ->addColumn('action', function($row){
                //     $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                //     return $actionBtn;
                // })
                // ->rawColumns(['action'])
                ->make(true);


        }
    }

    public function addOption(Request $request)
    {
        $request->validate([
            'questionnaire' => 'required',
            'value' => 'required|max:160'
        ]);

        $questionnaire = Questionnaire::find($request->questionnaire);

        $answer = Answer::create([
            'user_id' => Auth::user()->id,
            'userValue' => $request->value,
        ]);

        $questionnaire->answers()->attach($answer);

        $data = array(
            'answerId' => $answer->id,
            'questionnaireId' => $questionnaire->id,
        );
        return response()->json($data);

    }

    public function deleteOption(Request $request)
    {
        $answer = Answer::find($request->answer);

        $answer->questionnaires()->detach();
        $answer->forceDelete();

    }

    public function updateOption(Request $request)
    {
        $request->validate([
            'answer' => 'required',
            'value' => 'required|max:160'
        ]);

        $answer = Answer::find($request->answer);
        $answer->update(['userValue' => $request->value]);
    }
}
