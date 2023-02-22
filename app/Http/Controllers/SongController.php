<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSong;
use App\Models\Category;
use App\Models\Group;
use App\Models\Song;
use App\Models\Subgroup;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use DataTables;

class SongController extends Controller
{
    public function index()
    {
        return view('song.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = Song::latest()->get();
            return Datatables::of($data)
                ->addColumn('category', function ($row) {
                    $categoryString = Song::find($row->id)->category?->name;
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
        $song = Song::find($id);

        if ($song->restrictedVisibility) {
            foreach ($song->groups as $group) {
                $visibilityString .= '<span class="badge rounded-pill bg-primary">' . $group->name . '</span> ';
            }
            foreach ($song->subgroups as $subgroup) {
                $visibilityString .= '<span class="badge rounded-pill bg-success">' . $subgroup->name . '</span> ';
            }
            foreach ($song->users as $user) {
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

        return view('song.edit', [
            'users' => $users,
            'groups' => $groups,
            'subgroups' => $subgroups,
            'categories'  => $categories
        ]);
    }


    public function store(UpdateSong $request)
    {
        $visibilityData = $request->visibility;

        $request->validate([
            'upload' => 'required|max:10240|mimes:midi,mid'
        ], [
            'upload.required' =>'Musisz dołączyć plik MIDI',
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
            $file = (new FileController)->storeFile($request, 'song');
            $file = ($file->getData()->id);
        }

        $song = Song::create([
            'name' => $data['name'],
            'restrictedVisibility' => (empty($groups) && empty($subgroups) && empty($users)) ? false : true,
            'file_id' => $file,
            'category_id' => $data['category']
        ]);

        $song->groups()->sync($groups);
        $song->subgroups()->sync($subgroups);
        $song->users()->sync($users);



        return redirect()->route('songs.index')
            ->with('success', 'Plik z utworem został dodany');
    }

    public function edit(Song $Song)
    {
        $users = User::get();
        $groups = Group::get();
        $subgroups = Subgroup::get();
        $categories = Category::get();

        $visibleGroups = $Song->groupsIDs();
        $visibleSubgroups = $Song->subgroupsIDs();
        $visibleUsers = $Song->usersIDs();


        return view('song.edit', [
            'song' => $Song,
            'users' => $users,
            'groups' => $groups,
            'subgroups' => $subgroups,
            'categories' => $categories,
            'visibleGroups' => $visibleGroups,
            'visibleSubgroups' => $visibleSubgroups,
            'visibleUsers' => $visibleUsers,

        ]);
    }


    public function update(Song $Song, UpdateSong $request)
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

        $Song->update([
            'name' => $data['name'],
            'restrictedVisibility' => (empty($groups) && empty($subgroups) && empty($users)) ? false : true,
        ]);

        if($request['upload']){
            $file = (new FileController)->storeFile($request, 'song');
            $file = ($file->getData()->id);
            $Song->update([
                'file_id' => $file,
            ]);
        }



        $Song->groups()->sync($groups);
        $Song->subgroups()->sync($subgroups);
        $Song->users()->sync($users);

        return redirect()->route('songs.index')
            ->with('success', 'Utwór został aktualizowany.');
    }


    public function destroy(Song $Song)
    {
        //
    }

    public function play(Song $Song)
    {
        $midiFile = file_get_contents(public_path($Song->file->location));
        $base64Midi = base64_encode($midiFile);

        return view('song.play', [
            'song' => $Song,
            'midi' => $base64Midi,
            'soundFontDir' => url('files').'/soundfonts/',
        ]);
    }
}
