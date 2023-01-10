<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUser;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Http\RedirectResponse;
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
        return view('user.edit');
    }

    public function store(UpdateUser $request): RedirectResponse
    {
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
        return view('user.edit', ['user' => $User]);
    }

    public function update(User $User, UpdateUser $request): RedirectResponse
    {
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
