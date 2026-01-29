<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Ride;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Update driver's current location
     */
    public function updateLocation(Request $request)
    {
        // Validate request data
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Update driver location
        $driver = Driver::find($request->driver_id);
        $driver->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => $driver
        ]);
    }

    /**
     * Get nearby rides for driver
     */
    public function getNearbyRides(Request $request)
    {
        // Validate request data
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:0.1|max:50', // Radius in kilometers
        ]);

        // Get driver's current location
        $driver = Driver::find($request->driver_id);

        // Calculate nearby rides using Haversine formula
        $rides = Ride::where('status', 'requested')
            ->whereHas('passenger')
            ->get()
            ->filter(function ($ride) use ($request) {
                // Simple distance calculation (for demo purposes)
                $distance = $this->calculateDistance(
                    $request->latitude,
                    $request->longitude,
                    $ride->pickup_latitude,
                    $ride->pickup_longitude
                );

                return $distance <= $request->radius;
            });

        return response()->json([
            'success' => true,
            'message' => 'Nearby rides fetched successfully',
            'data' => $rides
        ]);
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Request to accept a ride
     */
    public function requestRide(Request $request)
    {
        // Validate request data
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'ride_id' => 'required|exists:rides,id',
        ]);

        // Find the ride
        $ride = Ride::find($request->ride_id);

        // Check if ride exists and is available
        if (!$ride || $ride->status != 'requested') {
            return response()->json([
                'success' => false,
                'message' => 'Ride not available or already accepted'
            ], 400);
        }

        // Check if driver is available
        $driver = Driver::find($request->driver_id);
        if (!$driver->is_available) {
            return response()->json([
                'success' => false,
                'message' => 'Driver is not available'
            ], 400);
        }

        // Accept the ride
        $ride->update([
            'driver_id' => $request->driver_id,
            'status' => 'accepted'
        ]);

        // Update driver availability
        $driver->update(['is_available' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Ride accepted successfully',
            'data' => $ride
        ]);
    }

    /**
     * Mark a ride as completed by driver
     */
    public function markCompleted(Request $request)
    {
        // Validate request data
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'ride_id' => 'required|exists:rides,id',
        ]);

        // Find the ride
        $ride = Ride::find($request->ride_id);

        // Check if ride exists and belongs to the driver
        if (!$ride || $ride->driver_id != $request->driver_id) {
            return response()->json([
                'success' => false,
                'message' => 'Ride not found or access denied'
            ], 404);
        }

        // Check if ride is already completed
        if ($ride->status == 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Ride already completed'
            ], 400);
        }

        // Mark driver as completed
        $ride->update(['driver_completed' => true]);

        // Check if both passenger and driver have marked as completed
        if ($ride->passenger_completed && $ride->driver_completed) {
            $ride->update(['status' => 'completed']);

            // Make driver available again
            Driver::find($request->driver_id)->update(['is_available' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ride marked as completed by driver',
            'data' => $ride
        ]);
    }
}
