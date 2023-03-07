<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePublication;
use App\Jobs\SendEmailJob;
use App\Mail\UserEmail;
use App\Models\Group;
use App\Models\Publication;
use App\Models\Questionnaire;
use App\Models\Subgroup;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use DateInterval;
use DateTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('admin-level');
        return view('publication.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            if ($request->active == 'true') {
                $data = Publication::where('archived', 0)->latest()->get();
            } else {
                $data = Publication::where('archived', 1)->latest()->get();
            }

            return Datatables::of($data)
                ->addColumn('visibility', function ($row) {
                    $visibilityString = $this->getVisibilityData($row->id);
                    return $visibilityString;
                })
                ->rawColumns(['visibility'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    private function getVisibilityData($id)
    {
        $visibilityString = '';
        $publication = Publication::find($id);

        if ($publication->restrictedVisibility) {
            foreach ($publication->groups as $group) {
                $visibilityString .= '<span class="badge rounded-pill bg-primary">' . $group->name . '</span> ';
            }
            foreach ($publication->subgroups as $subgroup) {
                $visibilityString .= '<span class="badge rounded-pill bg-success">' . $subgroup->name . '</span> ';
            }
            foreach ($publication->users as $user) {
                $visibilityString .= '<span class="badge rounded-pill bg-secondary">' . $user->name . ' ' . $user->surname . '</span> ';
            }
        } else {
            $visibilityString = 'Wszyscy';
        }

        return $visibilityString;
    }

    public function create()
    {
        Gate::authorize('admin-level');
        $users = User::get();
        $groups = Group::get();
        $subgroups = Subgroup::get();

        $questionnaireValidTill = new DateTime();
        $questionnaireValidTill->add(new DateInterval('P7D'));

        return view('publication.edit', [
            'users' => $users,
            'groups' => $groups,
            'subgroups' => $subgroups,
            'questionnaireValidTill' => $questionnaireValidTill->format('Y-m-d'),
        ]);
    }

    public function store(UpdatePublication $request)
    {
        Gate::authorize('admin-level');
        $visibilityData = $request->visibility;

        $groups = [];
        $subgroups = [];
        $users = [];

        foreach ($visibilityData ?? [] as $entry) {
            $separatedEntry = explode(":", $entry);
            switch ($separatedEntry[0]) {
                case 'group':
                    $groups[] = (int) $separatedEntry[1];
                    break;
                case 'subgroup':
                    $subgroups[] = (int) $separatedEntry[1];
                    break;
                case 'user':
                    $users[] = (int) $separatedEntry[1];
                    break;
            }
        }

        $data = $request->validated();

        $publication = Publication::create([
            'name' => $data['name'],
            'content' => $data['content'],
            'allowComments' => ($data['allowComments'] ?? null) ? '1' : '0',
            'restrictedVisibility' => (empty($groups) && empty($subgroups) && empty($users)) ? false : true,
        ]);

        $publication->groups()->sync($groups);
        $publication->subgroups()->sync($subgroups);
        $publication->users()->sync($users);

        if ($data['questionnaireAvailable']) {
            $questionnaire = Questionnaire::create([
                'description' => $data['questionnaireDescription'],
                'validTill' => $data['questionnaireDate'],
                'type' => $data['questionnaireType'],
                'publication_id' => $publication->id,
            ]);
        }

        return redirect()->route('publications.index')
            ->with('success', 'Wpis został dodany');
    }

    public function edit(Publication $Publication)
    {
        Gate::authorize('admin-level');
        $users = User::get();
        $groups = Group::get();
        $subgroups = Subgroup::get();

        $visibleGroups = $Publication->groupsIDs();
        $visibleSubgroups = $Publication->subgroupsIDs();
        $visibleUsers = $Publication->usersIDs();

        $questionnaireValidTill = new DateTime();
        $questionnaireValidTill->add(new DateInterval('P7D'));


        return view('publication.edit', [
            'publication' => $Publication,
            'users' => $users,
            'groups' => $groups,
            'subgroups' => $subgroups,
            'visibleGroups' => $visibleGroups,
            'visibleSubgroups' => $visibleSubgroups,
            'visibleUsers' => $visibleUsers,
            'questionnaireValidTill' => $questionnaireValidTill->format('Y-m-d'),
        ]);
    }

    public function update(Publication $Publication, UpdatePublication $request)
    {
        Gate::authorize('admin-level');
        $visibilityData = $request->visibility;
        $sendMail = ($request->sendMail == 'on') ? true : false;

        $groups = [];
        $subgroups = [];
        $users = [];

        foreach ($visibilityData ?? [] as $entry) {
            $separatedEntry = explode(":", $entry);
            switch ($separatedEntry[0]) {
                case 'group':
                    $groups[] = (int) $separatedEntry[1];
                    break;
                case 'subgroup':
                    $subgroups[] = (int) $separatedEntry[1];
                    break;
                case 'user':
                    $users[] = (int) $separatedEntry[1];
                    break;
            }
        }

        $data = $request->validated();

        $Publication->update([
            'name' => $data['name'],
            'content' => $data['content'],
            'allowComments' => ($data['allowComments'] ?? null) ? '1' : '0',
            'restrictedVisibility' => (empty($groups) && empty($subgroups) && empty($users)) ? false : true,
        ]);

        $Publication->groups()->sync($groups);
        $Publication->subgroups()->sync($subgroups);
        $Publication->users()->sync($users);

        if ($data['questionnaireAvailable']) {
            if ($Publication->questionnaire) {
                $Publication->questionnaire->update([
                    'description' => $data['questionnaireDescription'],
                    'validTill' => $data['questionnaireDate'],
                    'type' => $data['questionnaireType'],
                    'publication_id' => $Publication->id,
                ]);
            } else {
                Questionnaire::create([
                    'description' => $data['questionnaireDescription'],
                    'validTill' => $data['questionnaireDate'],
                    'type' => $data['questionnaireType'],
                    'publication_id' => $Publication->id,
                ]);
            }
        }

        if($sendMail){
            $this->sendEmail($Publication->emailRecipients(), $Publication);
        }

        return redirect()->route('publications.index')
            ->with('success', 'Wpis został aktualizowany');
    }

    public function destroy(Publication $publication)
    {
        //
    }

    public function archive(Publication $Publication): RedirectResponse
    {
        $Publication->update(['archived' => 1]);
        return redirect()->route('publications.index')
            ->with('success', 'Ogłoszenie przeniesiono do archiwum');
    }



    public function sendEmail($users, Publication $publication)
    {
        foreach($users as $user)
        {
           dispatch(new SendEmailJob($user, $publication));
        }

    }
}
