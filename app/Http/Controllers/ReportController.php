<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Dashboard statistics
        $totalRooms = Room::count();
        $totalStudents = Student::count();
        $activeAllocations = Allocation::where('status', 'active')->count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        
        // Occupancy calculations
        $totalRoomsCount = Room::count();
        $occupiedRooms = Allocation::where('status', 'active')
            ->distinct('room_id')
            ->count('room_id');
        $occupancyRate = $totalRoomsCount > 0 ? round(($occupiedRooms / $totalRoomsCount) * 100) : 0;
        
        // Room type distribution
        $roomTypes = Room::select('capacity', DB::raw('count(*) as count'))
            ->groupBy('capacity')
            ->get();
        
        // Recent activities
        $recentPayments = Payment::with('student')
            ->latest()
            ->take(10)
            ->get();
            
        $recentAllocations = Allocation::with(['student', 'room'])
            ->latest()
            ->take(10)
            ->get();
        
        // Monthly revenue data for chart - FIXED GROUP BY
        $monthlyRevenue = DB::select("
            SELECT 
                MONTH(payment_date) as month,
                SUM(amount) as total 
            FROM payments 
            WHERE status = 'completed' 
                AND YEAR(payment_date) = ?
            GROUP BY MONTH(payment_date)
            ORDER BY month ASC
        ", [date('Y')]);
        
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $revenueData = array_fill(0, 12, 0);
        
        foreach($monthlyRevenue as $revenue) {
            $revenueData[$revenue->month - 1] = floatval($revenue->total);
        }
        
        return view('reports.index', compact(
            'totalRooms', 
            'totalStudents', 
            'activeAllocations', 
            'totalRevenue',
            'occupiedRooms',
            'totalRoomsCount',
            'occupancyRate',
            'roomTypes',
            'recentPayments',
            'recentAllocations',
            'revenueData',
            'months'
        ));
    }
    
    public function paymentReport(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-t');
        
        $payments = Payment::with('student')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->orderBy('payment_date', 'desc')
            ->get();
            
        $totalAmount = $payments->sum('amount');
        $byMethod = $payments->groupBy('payment_method')->map->sum('amount');
        
        return view('reports.payments', compact('payments', 'totalAmount', 'byMethod', 'startDate', 'endDate'));
    }
    
    public function allocationReport(Request $request)
    {
        $status = $request->status ?? 'active';
        
        $allocations = Allocation::with(['student', 'room'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('reports.allocations', compact('allocations', 'status'));
    }
    
    public function occupancyReport()
    {
        $rooms = Room::withCount('activeAllocations')->get();
        
        $occupancyData = [];
        $totalCapacity = 0;
        $totalOccupied = 0;
        
        foreach($rooms as $room) {
            $occupied = $room->active_allocations_count;
            $totalCapacity += $room->capacity;
            $totalOccupied += $occupied;
            
            $occupancyData[] = [
                'room_number' => $room->room_number,
                'capacity' => $room->capacity,
                'occupied' => $occupied,
                'available' => $room->capacity - $occupied,
                'percentage' => $room->capacity > 0 ? round(($occupied / $room->capacity) * 100, 2) : 0
            ];
        }
        
        $overallPercentage = $totalCapacity > 0 ? round(($totalOccupied / $totalCapacity) * 100, 2) : 0;
        
        return view('reports.occupancy', compact('occupancyData', 'overallPercentage', 'totalCapacity', 'totalOccupied'));
    }
    
    public function financialReport(Request $request)
    {
        $year = $request->year ?? date('Y');
        
        $monthlyData = [];
        for($month = 1; $month <= 12; $month++) {
            $amount = Payment::whereYear('payment_date', $year)
                ->whereMonth('payment_date', $month)
                ->where('status', 'completed')
                ->sum('amount');
                
            $count = Payment::whereYear('payment_date', $year)
                ->whereMonth('payment_date', $month)
                ->where('status', 'completed')
                ->count();
                
            $monthlyData[] = [
                'month' => date('F', mktime(0, 0, 0, $month, 1)),
                'amount' => floatval($amount),
                'count' => $count
            ];
        }
        
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $totalTransactions = Payment::where('status', 'completed')->count();
        $averagePayment = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        
        return view('reports.financial', compact('monthlyData', 'totalRevenue', 'totalTransactions', 'averagePayment', 'year'));
    }
}