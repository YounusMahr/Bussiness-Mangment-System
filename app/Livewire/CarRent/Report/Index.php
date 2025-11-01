<?php

namespace App\Livewire\CarRent\Report;

use Livewire\Component;
use App\Models\VehicleBooking;
use App\Models\CarRentUdaar;
use App\Models\Vehicle;

class Index extends Component
{
    public function render()
    {
        $today = now()->startOfDay();
        $yesterday = (clone $today)->copy()->subDay();

        // Total Bookings Revenue
        $totalRevenue = (float) (VehicleBooking::sum('total_price') ?? 0);
        $revenueToday = (float) (VehicleBooking::whereDate('date', $today)->sum('total_price') ?? 0);
        $revenueYesterday = (float) (VehicleBooking::whereDate('date', $yesterday)->sum('total_price') ?? 0);
        $revenueChangePct = $revenueYesterday > 0
            ? round((($revenueToday - $revenueYesterday) / max($revenueYesterday, 0.0001)) * 100)
            : ($revenueToday > 0 ? 100 : 0);

        // Total Bookings Count
        $totalBookings = (int) VehicleBooking::count();
        $bookingsToday = (int) VehicleBooking::whereDate('date', $today)->count();
        $bookingsYesterday = (int) VehicleBooking::whereDate('date', $yesterday)->count();
        $bookingsChange = $bookingsToday - $bookingsYesterday;

        // Active Vehicles
        $activeVehicles = (int) Vehicle::where('is_active', true)->count();
        $totalVehicles = (int) Vehicle::count();
        $vehiclesChange = $totalVehicles - $activeVehicles; // Show inactive vehicles count

        // Overdue Udhaar
        $overdueUdhaar = (int) CarRentUdaar::whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->where('status', '!=', 'paid')
            ->count();

        return view('livewire.car-rent.report.index', compact(
            'totalRevenue',
            'revenueChangePct',
            'totalBookings',
            'bookingsChange',
            'activeVehicles',
            'vehiclesChange',
            'overdueUdhaar'
        ));
    }
}
