<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGroup;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Subgroup;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        Gate::authorize('admin-level');
        return view('group.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = Group::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('action', function($row){
                //     $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                //     return $actionBtn;
                // })
                // ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function create(): View
    {
        Gate::authorize('admin-level');
        $users = User::get();
        return view('group.edit',[
            'users' => $users,
        ]);
    }


    public function store(UpdateGroup $request): RedirectResponse
    {
        Gate::authorize('admin-level');
        $data = $request->validated();

        $group = Group::create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        return redirect()->route('groups.index')
            ->with('success', 'Grupa została dodana');
    }


    public function edit(Group $Group): View
    {
        Gate::authorize('admin-level');
        $users = User::get();
        return view('group.edit', [
            'group' => $Group,
            'users' => $users,
        ]);
    }

    public function update(Group $Group, UpdateGroup $request): RedirectResponse
    {
        Gate::authorize('admin-level');
        $data = $request->validated();

        $Group->update([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        return redirect()->route('groups.index')
            ->with('success', 'Dane grupy zostały aktualizowane');
    }


    public function destroy(Group $Group): RedirectResponse
    {
        Gate::authorize('admin-level');

        foreach($Group->subgroups as $subgroup){
            $subgroup->users()->detach();
            $subgroup->group()->dissociate();
            $subgroup->publications()->detach();
            $subgroup->notes()->detach();
            $subgroup->songs()->detach();
            $subgroup->forceDelete();
        }

        $Group->users()->detach();
        $Group->subgroups()->delete();
        $Group->publications()->detach();
        $Group->notes()->detach();
        $Group->songs()->detach();
        $Group->forceDelete();
        return redirect()->route('groups.index')
            ->with('success', 'Grupa została usunięta');
    }

    public function members(Group $Group, Request $request)
    {
        if ($request->ajax()) {

            $data = GroupUser::with(['user', 'subgroup'])->where('group_id', $Group->id)
                ->get()->toArray();

                Debugbar::info($data);

            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('action', function($row){
                //     $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                //     return $actionBtn;
                // })
                // ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function addMember(Request $request)
    {
        $group = Group::find($request->group);

        if($request->subgroups == ['']){
            $request->merge([
                'subgroups' => null,
            ]);
        }

        if($request->subgroups){

            foreach($request->subgroups as $subgroup){
                $subgroupModel = Subgroup::find($subgroup);
                if(!($subgroupModel->users->contains($request->member))){
                    $group->users()->attach($request->member, ['subgroup_id' => $subgroup]);
                }
            }
        } else {
            if(!($group->users->contains($request->member))){
                $group->users()->attach($request->member);
            }
        }

        $data = array(
            'groupId' => $group->id,
            'groupName' => $group->name,
            'subgroupId' => $subgroupModel->id ?? '',
            'subgroupName' => $subgroupModel->name ?? '',
        );

        return response()->json($data);

    }

    public function subgroups(Group $Group)
    {
        $subgroups = $Group->subgroups->pluck('id', 'name')->toArray();
        return response()->json($subgroups);
    }

    public function removeMember(Request $request)
    {
        $group = Group::find($request->group);
        $user = User::find($request->member);

        $user->groups()->detach($group);

    }
}
