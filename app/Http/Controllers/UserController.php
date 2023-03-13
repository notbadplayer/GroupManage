<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePassword;
use App\Http\Requests\UpdateUser;
use App\Models\Group;
use App\Models\Subgroup;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
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
        $groups = Group::get();
        return view(
            'user.edit',
            ['groups' => $groups]
        );
    }

    public function store(UpdateUser $request): RedirectResponse
    {
        Gate::authorize('admin-level');
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => Hash::make('12345'),
        ]);

        return redirect()->route('users.edit', ['User' => $user->id])
            ->with('success', 'Użytkownik został dodany.');
    }

    public function edit(User $User): View
    {
        Gate::authorize('admin-level');
        $groups = Group::get();
        $subgroups = Subgroup::get();
        return view('user.edit', [
            'user' => $User,
            'groups' => $groups,
            'subgroups' => $subgroups
        ]);
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
            ->with('success', 'Dane użytkownika zostały aktualizowane');
    }

    public function profile()
    {
        $user = Auth::user();
        $groups = Group::get();
        return view('user.profile', [
            'user' => $user,
            'groups' => $groups,
        ]);
    }

    public function profileUdate(User $User, UpdateUser $request): RedirectResponse
    {
        $data = $request->validated();

        $User->update([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'phone' => $data['phone'],
            'email' => $data['email'],
        ]);

        return redirect()->route('users.profile')
            ->with('success', 'Dane zostały aktualizowane');
    }

    public function passwordUdate(UpdatePassword $request): RedirectResponse
    {
        $data = $request->validated();
        User::find(auth()->user()->id)->update(['password' => Hash::make($request->password)]);
        return redirect()->route('users.profile')
            ->with('success', 'Hasło zostało zmienione.');
    }

    public function destroy(User $User): RedirectResponse
    {
        Gate::authorize('admin-level');
        if(!($User->role == 'admin')){
            $User->groups()->detach();
            $User->subgroups()->detach();
            $User->publications()->detach();
            $User->notes()->detach();
            $User->songs()->detach();

            foreach ($User->answers as $answer) {
                $answer->questionnaires()->detach();
                $answer->forceDelete();
            }

            $User->delete();
            return redirect()->route('users.index')
                ->with('success', 'Użytkownik został usunięty');
        }
        else {
            return redirect()->route('users.index')
                ->with('success', 'Nie możesz usunąć administratora');
        }

    }
}
