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
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    public function index()
    {
        $categories = Category::get();
        return view('note.index', ['categories' => $categories]);
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            if (Gate::allows('admin-level')) {
                $data = Note::latest()->get();
            } else {
                $user_id = Auth::id();
                $groups = auth()->user()->groups;
                $subgroups = auth()->user()->subgroups;

                $data = Note::where(function ($query) use ($groups, $subgroups, $user_id) {
                    $query->where(function ($query) use ($groups) {
                        $query->whereHas('groups', function ($query) use ($groups) {
                            $query->whereIn('id', $groups->pluck('id'));
                        });
                    })
                    ->orWhere(function ($query) use ($subgroups) {
                        $query->whereHas('subgroups', function ($query) use ($subgroups) {
                            $query->whereIn('id', $subgroups->pluck('id'));
                        });
                    })
                    ->orWhere(function ($query) use ($user_id) {
                        $query->whereHas('users', function ($query) use ($user_id) {
                            $query->where('id', $user_id);
                        });
                    })
                    ->orWhere(function ($query) {
                        $query->where('restrictedVisibility', '0');
                    });
                })
                ->get();

            }


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
        Gate::authorize('admin-level');
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
        Gate::authorize('admin-level');
        $visibilityData = $request->visibility;

        $request->validate([
            'upload' => 'required|max:10240|mimes:pdf,jpg,jpeg,png'
        ], [
            'upload.required' =>'Musisz dołączyć plik',
        ]);


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

        if($request['upload']){
            $file = (new FileController)->storeFile($request, 'note');
            $file = ($file->getData()->id);
        }

        $note = Note::create([
            'name' => $data['name'],
            'table' => $data['table'] ?? null,
            'transpose' => $data['transpose'] ?? null,
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
        Gate::authorize('admin-level');
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

        $Note->update([
            'name' => $data['name'],
            'table' => $data['table'] ?? null,
            'transpose' => $data['transpose'] ?? null,
            'category_id' => $data['category'],
            'restrictedVisibility' => (empty($groups) && empty($subgroups) && empty($users)) ? false : true,
        ]);

        if($request['upload']){
            //usunięcie starego pliku z serwera:
            unlink(public_path($Note->file->location));
            $Note->file->forceDelete();
            //aktualizacja:
            $file = (new FileController)->storeFile($request, 'note');
            $file = ($file->getData()->id);
            $Note->update([
                'file_id' => $file,
            ]);
        }



        $Note->groups()->sync($groups);
        $Note->subgroups()->sync($subgroups);
        $Note->users()->sync($users);

        return redirect()->route('notes.index')
            ->with('success', 'Nuty zostały aktualizowane');
    }


    public function destroy(Note $Note): RedirectResponse
    {
        Gate::authorize('admin-level');

        $Note->users()->detach();
        $Note->subgroups()->detach();
        $Note->groups()->detach();

        unlink(public_path($Note->file->location));
        $Note->file->forceDelete();

        $Note->forceDelete();
        return redirect()->route('notes.index')
            ->with('success', 'Nuty zostały usunięte');
    }

}
