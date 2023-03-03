<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Publication;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

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
        $publications = Publication::where('archived', 0)->latest()->get();
        $events = Event::whereDate('date', '>=', date('Y-m-d'))->orderBy('date', 'asc')->get();
        $today = new DateTime();

        return view('home.home',[
            'publications' => $publications,
            'events' => $events,
            'today' =>  $today->format('Y-m-d'),
            'tomorrow' => $today->modify('+1 day')->format('Y-m-d'),
        ]);
    }
}
