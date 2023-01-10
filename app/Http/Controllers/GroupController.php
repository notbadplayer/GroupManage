<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGroup;
use App\Models\Group;
use App\Models\User;
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

        $group=Group::create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        return redirect()->route('groups.index')
                ->with('success','Grupa została dodana');
    }


    public function edit(Group $Group): View
    {
        return view('group.edit', ['group' => $Group]);
    }

    public function update(Group $Group, UpdateGroup $request): RedirectResponse
    {
        $data = $request->validated();

        $Group->update([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        return redirect()->route('groups.index')
        ->with('success','Dane grupy zostały aktualizowane');
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
}
