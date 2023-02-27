<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Group;
use App\Models\Questionnaire;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class QuestionnaireController extends Controller
{
    public function destroy(Questionnaire $Questionnaire)
    {
        $Questionnaire->delete();
    }

    public function vote(Questionnaire $Questionnaire, Request $request)
    {
        if($Questionnaire->type = 'closed' && count($Questionnaire->userAnswers) > 0)
        {
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
        $entitledToVote = 1;

        // $entitledUsersIds = $Questionnaire->publication->usersIDs();

        // $entitledUsersIdsFromGroups = [];
        // foreach($Questionnaire->publication->groups as $group)
        // {
        //     foreach($group->users as $user){
        //     $entitledUsersIdsFromGroups[] = $user->id;
        //     }
        // }

        // $entitledUsersIdsFromSubgroups = [];
        // foreach($Questionnaire->publication->subgroups as $subgroup)
        // {
        //     foreach($subgroup->users as $user){
        //     $entitledUsersIdsFromSubgroups[] = $user->id;
        //     }
        // }

        // dump($entitledUsersIds);
        // dump($entitledUsersIdsFromGroups);
        // dump($entitledUsersIdsFromSubgroups);
        // exit();

        $membersFromGroups = $Questionnaire->publication->restrictedVisibilityUserIds();
         dump($membersFromGroups);
        //exit();

        $group = Group::find(1);
        dump($group->users);


        return view('questionnaires.results', [
            'questionnaire' => $Questionnaire,
            'entitledToVote' => $entitledToVote,

        ]);
    }
}
