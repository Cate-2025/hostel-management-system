<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentData = [
            [
                'name' => 'John Doe',
                'email' => 'john@student.com',
                'roll_number' => 'CSE001',
                'enrollment_number' => 'EN001',
                'program' => 'B.Tech CSE',
                'semester' => 4,
                'contact_number' => '9876543210',
                'parent_contact' => '9876543211',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@student.com',
                'roll_number' => 'CSE002',
                'enrollment_number' => 'EN002',
                'program' => 'B.Tech CSE',
                'semester' => 4,
                'contact_number' => '9876543212',
                'parent_contact' => '9876543213',
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@student.com',
                'roll_number' => 'ECE001',
                'enrollment_number' => 'EN003',
                'program' => 'B.Tech ECE',
                'semester' => 3,
                'contact_number' => '9876543214',
                'parent_contact' => '9876543215',
            ],
        ];

        foreach ($studentData as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password123'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]);

            Student::create([
                'user_id' => $user->id,
                'roll_number' => $data['roll_number'],
                'enrollment_number' => $data['enrollment_number'],
                'program' => $data['program'],
                'semester' => $data['semester'],
                'contact_number' => $data['contact_number'],
                'parent_contact' => $data['parent_contact'],
                'address' => 'Address to be updated',
            ]);
        }
    }
}
