<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('user_id', Auth::id())->get();
        return view('events.index', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
        ]);

        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Event added successfully.');
    }
}
