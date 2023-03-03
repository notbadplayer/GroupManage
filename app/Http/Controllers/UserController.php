<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUser;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        Gate::authorize('admin-level');
        return view('user.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();
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
        return view('user.edit');
    }

    public function store(UpdateUser $request): RedirectResponse
    {
        Gate::authorize('admin-level');
        $data = $request->validated();

        $user=User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'phone' => $data['phone'],
            'email' => $data['email'],
        ]);

        return redirect()->route('users.index')
                ->with('success','Użytkownik został dodany');
    }

    public function edit(User $User): View
    {
        Gate::authorize('admin-level');
        return view('user.edit', ['user' => $User]);
    }

    public function update(User $User, UpdateUser $request): RedirectResponse
    {
        Gate::authorize('admin-level');
        $data = $request->validated();

        $User->update([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'phone' => $data['phone'],
            'email' => $data['email'],
        ]);

        return redirect()->route('users.index')
        ->with('success','Dane użytkownika zostały aktualizowane');
    }


}
