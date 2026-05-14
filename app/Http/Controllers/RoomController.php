<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Show all rooms (Admin).
     */
    public function index()
    {
        $rooms = Room::paginate(15);
        $stats = [
            'total' => Room::count(),
            'available' => Room::where('status', 'available')->count(),
            'occupied' => Room::where('status', 'occupied')->count(),
            'maintenance' => Room::where('status', 'maintenance')->count(),
        ];
        return view('admin.rooms.index', ['rooms' => $rooms, 'stats' => $stats]);
    }

    /**
     * Show create room form.
     */
    public function create()
    {
        return view('admin.rooms.create');
    }

    /**
     * Store a new room.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms',
            'capacity' => 'required|integer|min:1|max:10',
            'floor' => 'required|integer|min:1',
            'type' => 'required|string|in:single,double,triple,quad',
            'status' => 'required|string|in:available,occupied,maintenance',
            'maintenance_notes' => 'nullable|string',
        ]);

        Room::create([
            'room_number' => $validated['room_number'],
            'capacity' => $validated['capacity'],
            'available_beds' => $validated['capacity'],
            'floor' => $validated['floor'],
            'type' => $validated['type'],
            'status' => $validated['status'],
            'maintenance_notes' => $validated['maintenance_notes'],
        ]);

        return redirect()->route('rooms.index')->with('success', 'Room created successfully');
    }

    /**
     * Show edit room form.
     */
    public function edit(Room $room)
    {
        return view('admin.rooms.edit', ['room' => $room]);
    }

    /**
     * Update a room.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number,'.$room->id,
            'capacity' => 'required|integer|min:1|max:10',
            'floor' => 'required|integer|min:1',
            'type' => 'required|string|in:single,double,triple,quad',
            'status' => 'required|string|in:available,occupied,maintenance',
            'maintenance_notes' => 'nullable|string',
        ]);

        $room->update($validated);

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully');
    }

    /**
     * Delete a room.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully');
    }
}
