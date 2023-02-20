<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateNote;
use App\Models\Category;
use App\Models\Group;
use App\Models\Note;
use App\Models\Subgroup;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use DataTables;

class NoteController extends Controller
{
    public function index()
    {
        return view('note.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = Note::latest()->get();
            return Datatables::of($data)
                ->addColumn('category', function ($row) {
                    $categoryString = Note::find($row->id)->category?->name;
                    return $categoryString;
                })
                ->addColumn('visibility', function ($row) {
                    $visibilityString = $this->getVisibilityData($row->id);
                    return $visibilityString;
                })
                ->rawColumns(['category', 'visibility'])
                ->addIndexColumn()
                ->make(true);
        }
    }

    private function getVisibilityData($id)
    {
        $visibilityString = '';
        $note = Note::find($id);

        if ($note->restrictedVisibility) {
            foreach ($note->groups as $group) {
                $visibilityString .= '<span class="badge rounded-pill bg-primary">' . $group->name . '</span> ';
            }
            foreach ($note->subgroups as $subgroup) {
                $visibilityString .= '<span class="badge rounded-pill bg-success">' . $subgroup->name . '</span> ';
            }
            foreach ($note->users as $user) {
                $visibilityString .= '<span class="badge rounded-pill bg-secondary">' . $user->name . ' ' . $user->surname . '</span> ';
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
        $categories = Category::get();

        return view('note.edit', [
            'users' => $users,
            'groups' => $groups,
            'subgroups' => $subgroups,
            'categories'  => $categories
        ]);
    }


    public function store(UpdateNote $request)
    {
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

        if($data['upload']){
            $file = (new FileController)->storeFile($request, 'note');
            $file = ($file->getData()->id);
        }

        $note = Note::create([
            'name' => $data['name'],
            'restrictedVisibility' => (empty($groups) && empty($subgroups) && empty($users)) ? false : true,
            'file_id' => $file,
            'category_id' => $data['category']
        ]);

        $note->groups()->sync($groups);
        $note->subgroups()->sync($subgroups);
        $note->users()->sync($users);



        return redirect()->route('notes.index')
            ->with('success', 'Plik z nutami został dodany');
    }

    public function edit(Note $Note)
    {
        $users = User::get();
        $groups = Group::get();
        $subgroups = Subgroup::get();
        $categories = Category::get();

        $visibleGroups = $Note->groupsIDs();
        $visibleSubgroups = $Note->subgroupsIDs();
        $visibleUsers = $Note->usersIDs();


        return view('note.edit', [
            'note' => $Note,
            'users' => $users,
            'groups' => $groups,
            'subgroups' => $subgroups,
            'categories' => $categories,
            'visibleGroups' => $visibleGroups,
            'visibleSubgroups' => $visibleSubgroups,
            'visibleUsers' => $visibleUsers,

        ]);
    }


    public function update(Note $Note, UpdateNote $request)
    {
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

        $Note->update([
            'name' => $data['name'],
            'restrictedVisibility' => (empty($groups) && empty($subgroups) && empty($users)) ? false : true,
        ]);

        $Note->groups()->sync($groups);
        $Note->subgroups()->sync($subgroups);
        $Note->users()->sync($users);

        return redirect()->route('notes.index')
            ->with('success', 'Nuty zostały aktualizowane');
    }


    public function destroy(Note $note)
    {
        //
    }
}
