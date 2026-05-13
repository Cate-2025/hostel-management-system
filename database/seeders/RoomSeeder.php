<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            // Floor 1
            ['room_number' => '101', 'capacity' => 2, 'available_beds' => 2, 'floor' => 1, 'type' => 'double', 'status' => 'available'],
            ['room_number' => '102', 'capacity' => 2, 'available_beds' => 2, 'floor' => 1, 'type' => 'double', 'status' => 'available'],
            ['room_number' => '103', 'capacity' => 3, 'available_beds' => 3, 'floor' => 1, 'type' => 'triple', 'status' => 'available'],
            ['room_number' => '104', 'capacity' => 1, 'available_beds' => 1, 'floor' => 1, 'type' => 'single', 'status' => 'available'],
            
            // Floor 2
            ['room_number' => '201', 'capacity' => 2, 'available_beds' => 2, 'floor' => 2, 'type' => 'double', 'status' => 'available'],
            ['room_number' => '202', 'capacity' => 4, 'available_beds' => 4, 'floor' => 2, 'type' => 'shared', 'status' => 'available'],
            ['room_number' => '203', 'capacity' => 2, 'available_beds' => 2, 'floor' => 2, 'type' => 'double', 'status' => 'available'],
            
            // Floor 3
            ['room_number' => '301', 'capacity' => 3, 'available_beds' => 3, 'floor' => 3, 'type' => 'triple', 'status' => 'available'],
            ['room_number' => '302', 'capacity' => 2, 'available_beds' => 2, 'floor' => 3, 'type' => 'double', 'status' => 'maintenance', 'maintenance_notes' => 'Plumbing repair in progress'],
            ['room_number' => '303', 'capacity' => 1, 'available_beds' => 1, 'floor' => 3, 'type' => 'single', 'status' => 'available'],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
