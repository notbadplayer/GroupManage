<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateEvent;
use App\Models\Event;
use App\Models\Group;
use App\Models\Subgroup;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    public function index()
    {
        Gate::authorize('admin-level');
        return view('event.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = Event::latest()->get();
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
        $event = Event::find($id);

        if ($event->restrictedVisibility) {
            foreach ($event->groups as $group) {
                $visibilityString .= '<span class="badge rounded-pill bg-primary">' . $group->name . '</span> ';
            }
            foreach ($event->subgroups as $subgroup) {
                $visibilityString .= '<span class="badge rounded-pill bg-success">' . $subgroup->name . '</span> ';
            }
            foreach ($event->users as $user) {
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

        return view('event.edit', [
            'users' => $users,
            'groups' => $groups,
            'subgroups' => $subgroups,
        ]);
    }


    public function store(UpdateEvent $request)
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

        $event = Event::create([
            'name' => $data['name'],
            'date' => $data['date'],
            'time' => $data['time'] ?? null,
            'restrictedVisibility' => (empty($groups) && empty($subgroups) && empty($users)) ? false : true,
        ]);

        $event->groups()->sync($groups);
        $event->subgroups()->sync($subgroups);
        $event->users()->sync($users);



        return redirect()->route('events.index')
            ->with('success', 'Dodano wydarzenie');
    }

    public function edit(Event $Event)
    {
        Gate::authorize('admin-level');
        $users = User::get();
        $groups = Group::get();
        $subgroups = Subgroup::get();

        $visibleGroups = $Event->groupsIDs();
        $visibleSubgroups = $Event->subgroupsIDs();
        $visibleUsers = $Event->usersIDs();


        return view('event.edit', [
            'event' => $Event,
            'users' => $users,
            'groups' => $groups,
            'subgroups' => $subgroups,
            'visibleGroups' => $visibleGroups,
            'visibleSubgroups' => $visibleSubgroups,
            'visibleUsers' => $visibleUsers,

        ]);
    }


    public function update(Event $Event, UpdateEvent $request)
    {
        Gate::authorize('admin-level');
        $visibilityData = $request->visibility;

        $groups = [];
        $subgroups = [];
        $users = [];

        foreach ($visibilityData ?? [] as $entry)
        {
            $separatedEntry = explode(":", $entry);
            switch($separatedEntry[0]){
                case 'group':
                $groups [] = (int) $separatedEntry[1];
                break;
                case 'subgroup':
                $subgroups [] = (int) $separatedEntry[1];
                break;
                case 'user':
                $users [] = (int) $separatedEntry[1];
                break;
            }
        }

        $data = $request->validated();

        $Event->update([
            'name' => $data['name'],
            'date' => $data['date'],
            'time' => $data['time'] ?? null,
            'restrictedVisibility' => (empty($groups) && empty($subgroups) && empty($users)) ? false : true,
        ]);

        $Event->groups()->sync($groups);
        $Event->subgroups()->sync($subgroups);
        $Event->users()->sync($users);

        return redirect()->route('events.index')
            ->with('success', 'Aktualizowano wydarzenie');
    }


    public function destroy(Note $note)
    {
        //
    }
}
