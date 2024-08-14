<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Traits\PjaxTrait;

class EventController extends Controller
{   

    use PjaxTrait;
    /**
     * Display a listing of the events.
     */
    public function index(Request $request)
    {


        return $this->view('events.index');
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image',
        ]);

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'image' => $request->file('image') ? $request->file('image')->store('event-images', 'public') : null,
        ]);

        return redirect()->route('events.show', $event->id)->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified event.
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        if (request()->ajax() || request()->pjax()) {
            return response()->json([
                'view' => view('events.show', compact('event'))->render(),
            ]);
        }

        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image',
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'image' => $request->file('image') ? $request->file('image')->store('event-images', 'public') : $event->image,
        ]);

        if ($request->ajax() || $request->pjax()) {
            return response()->json([
                'view' => view('events.show', compact('event'))->render(),
            ]);
        }

        return redirect()->route('events.show', $event->id)->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Request $request, Event $event)
    {
        $event->delete();

        if ($request->ajax() || $request->pjax()) {
            return response()->json([
                'message' => 'Event deleted successfully.',
            ]);
        }

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    /**
     * Search for events.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        $events = Event::where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orderBy('date', 'desc')
                        ->paginate(10);

        if ($request->ajax() || $request->pjax()) {
            return response()->json([
                'view' => view('events.partials._events', compact('events'))->render(),
                'pagination' => view('events.partials._pagination', compact('events'))->render(),
                'query' => $query,
            ]);
        }

        return view('events.index', compact('events', 'query'));
    }
}
