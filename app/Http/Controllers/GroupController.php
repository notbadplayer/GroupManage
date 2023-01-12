<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGroup;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
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
        return view('group.edit');
    }


    public function store(UpdateGroup $request): RedirectResponse
    {
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
        $users = User::get();
        return view('group.edit', [
            'group' => $Group,
            'users' => $users,
        ]);
    }

    public function update(Group $Group, UpdateGroup $request): RedirectResponse
    {
        $data = $request->validated();

        $Group->update([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        return redirect()->route('groups.index')
            ->with('success', 'Dane grupy zostały aktualizowane');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
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
        Debugbar::info($request);
        // $member = new GroupUser();
        // $member->group_id = $request->member;
        // $member->user_id = $request->member;
        // $member->save();

        // $member = GroupUser::create([
        //     'group_id' => $request->group,
        //     'user_id' => $request->member,
        // ]);

        // $member->subgroups()->attach($request->subGroups);

        $group = Group::find($request->group);

        if($request->subgoups){

            foreach($request->subgroups as $subgroup){
                $group->users()->attach($request->member, ['subgroup_id' => $subgroup]);
            }
        } else {
            $group->users()->attach($request->member);
        }


    }
}
