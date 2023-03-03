<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Note;
use App\Models\Publication;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
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

        if (Gate::allows('admin-level')) {
            $publications = Publication::where('archived', 0)->latest()->get();
            $events = Event::whereDate('date', '>=', date('Y-m-d'))->orderBy('date', 'asc')->get();
            $latestNote = Note::latest()->first();
        } else {
            $user_id = Auth::id();
            $groups = auth()->user()->groups;
            $subgroups = auth()->user()->subgroups;


            $publications = Publication::where(function ($query) use ($groups, $subgroups, $user_id) {
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

            $events = Event::where(function ($query) use ($groups, $subgroups, $user_id) {
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
            })->whereDate('date', '>=', date('Y-m-d'))->orderBy('date', 'asc')->get();

            $latestNote = Note::where(function ($query) use ($groups, $subgroups, $user_id) {
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
            ->latest()->first();

        }

        $today = new DateTime();

        return view('home.home',[
            'publications' => $publications,
            'events' => $events,
            'today' =>  $today->format('Y-m-d'),
            'tomorrow' => $today->modify('+1 day')->format('Y-m-d'),
            'latestNote' => $latestNote,
        ]);
    }
}
