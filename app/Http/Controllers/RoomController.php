<?php

namespace App\Http\Controllers;

use App\Models\Room;
<<<<<<< HEAD
use App\Models\Allocation;
=======
>>>>>>> ed5b922bbb7a8f3fd2397b2769a66e738783be43
use Illuminate\Http\Request;

class RoomController extends Controller
{
<<<<<<< HEAD
    public function index()
    {
        $rooms = Room::all();
        
        // Calculate occupancy for each room
        foreach($rooms as $room) {
            $room->occupied_count = Allocation::where('room_id', $room->id)
                ->where('status', 'active')
                ->count();
            $room->available_count = $room->capacity - $room->occupied_count;
            $room->occupancy_percentage = $room->capacity > 0 ? round(($room->occupied_count / $room->capacity) * 100, 2) : 0;
        }
        
        return view('rooms.index', compact('rooms'));
    }
    
    public function create()
    {
        return view('rooms.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|unique:rooms',
            'capacity' => 'required|integer|min:1',
            'floor' => 'nullable|string',
            'price_per_month' => 'nullable|numeric'
        ]);
        
        Room::create($request->all());
        
        return redirect()->route('rooms.index')
            ->with('success', 'Room added successfully!');
    }
    
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }
    
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number' => 'required|unique:rooms,room_number,' . $room->id,
            'capacity' => 'required|integer|min:1',
            'floor' => 'nullable|string',
            'price_per_month' => 'nullable|numeric'
        ]);
        
        $room->update($request->all());
        
        return redirect()->route('rooms.index')
            ->with('success', 'Room updated successfully!');
    }
    
    public function destroy(Room $room)
    {
        $activeAllocations = Allocation::where('room_id', $room->id)
            ->where('status', 'active')
            ->exists();
            
        if ($activeAllocations) {
            return back()->with('error', 'Cannot delete room with active allocations!');
        }
        
        $room->delete();
        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully!');
    }
}
=======
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
>>>>>>> ed5b922bbb7a8f3fd2397b2769a66e738783be43
