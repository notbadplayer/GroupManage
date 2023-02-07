<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePublication;
use App\Models\Group;
use App\Models\Publication;
use App\Models\Subgroup;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use DataTables;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('publication.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = Publication::latest()->get();
            return Datatables::of($data)
            ->addColumn('visibility', function($row){
                    $visibilityString = $this->getVisibilityData($row->id);
                    return $visibilityString;
                })
                ->rawColumns(['visibility'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    private function getVisibilityData($id){
        $visibilityString = '';
        $publication = Publication::find($id);

        if($publication->restrictedVisibility){
            foreach($publication->groups as $group){
                $visibilityString .= '<span class="badge rounded-pill bg-primary">'.$group->name.'</span> ';
            }
            foreach($publication->subgroups as $subgroup){
                $visibilityString .= '<span class="badge rounded-pill bg-success">'.$subgroup->name.'</span> ';
            }
            foreach($publication->users as $user){
                $visibilityString .= '<span class="badge rounded-pill bg-secondary">'.$user->name.' '.$user->surname.'</span> ';
            }
        } else {
            $visibilityString = 'Wszyscy';
        }

        return $visibilityString;

    }

    public function create()
    {
        $users = User::get();
        $groups = Group::get();
        $subgroups = Subgroup::get();

        return view('publication.edit', [
            'users' => $users,
            'groups' => $groups,
            'subgroups' =>$subgroups
        ]);
    }

    public function store(UpdatePublication $request)
    {
        $visibilityData = $request->visibility;

        $groups = [];
        $subgroups = [];
        $users = [];

        foreach ($visibilityData as $entry)
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

        $publication = Publication::create([
            'name' => $data['name'],
            'content' => $data['content'],
            'restrictedVisibility' => (empty($groups) || empty($subgroups) || empty($users)) ? true : false,
        ]);

        $publication->groups()->sync($groups);
        $publication->subgroups()->sync($subgroups);
        $publication->users()->sync($users);

        return redirect()->route('publications.index')
            ->with('success', 'Wpis został dodany');
    }

    public function edit(Publication $Publication)
    {
        $users = User::get();
        $groups = Group::get();
        $subgroups = Subgroup::get();

        $visibleGroups = $Publication->groupsIDs();
        $visibleSubgroups = $Publication->subgroupsIDs();
        $visibleUsers = $Publication->usersIDs();


        return view('publication.edit', [
            'publication' => $Publication,
            'users' => $users,
            'groups' => $groups,
            'subgroups' => $subgroups,
            'visibleGroups' => $visibleGroups,
            'visibleSubgroups' => $visibleSubgroups,
            'visibleUsers' => $visibleUsers,

        ]);

    }


    public function update(Publication $Publication, UpdatePublication $request)
    {
        $visibilityData = $request->visibility;

        $groups = [];
        $subgroups = [];
        $users = [];

        foreach ($visibilityData as $entry)
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

        $Publication->update([
            'name' => $data['name'],
            'content' => $data['content'],
            'restrictedVisibility' => (empty($groups) || empty($subgroups) || empty($users)) ? true : false,
        ]);

        $Publication->groups()->sync($groups);
        $Publication->subgroups()->sync($subgroups);
        $Publication->users()->sync($users);

        return redirect()->route('publications.index')
            ->with('success', 'Wpis został aktualizowany');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publication $publication)
    {
        //
    }
}
