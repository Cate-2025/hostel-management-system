<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Allocation;
use Illuminate\Http\Request;

class RoomController extends Controller
{
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