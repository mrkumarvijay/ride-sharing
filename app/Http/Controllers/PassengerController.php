<?php

namespace App\Http\Controllers;

use App\Models\Passenger;
use App\Models\Driver;
use App\Models\Ride;
use Illuminate\Http\Request;

class PassengerController extends Controller
{
    /**
     * Create a new ride request
     */
    public function createRide(Request $request)
    {
        // Validate request data
        $request->validate([
            'passenger_id' => 'required|exists:passengers,id',
            'pickup_location' => 'required|string',
            'dropoff_location' => 'required|string',
            'pickup_latitude' => 'required|numeric',
            'pickup_longitude' => 'required|numeric',
            'dropoff_latitude' => 'required|numeric',      // Add this
            'dropoff_longitude' => 'required|numeric',     // Add this
        ]);

        $ride = Ride::create([
            'passenger_id' => $request->passenger_id,
            'pickup_location' => $request->pickup_location,
            'dropoff_location' => $request->dropoff_location,
            'pickup_latitude' => $request->pickup_latitude,
            'pickup_longitude' => $request->pickup_longitude,
            'dropoff_latitude' => $request->dropoff_latitude,   // Save this
            'dropoff_longitude' => $request->dropoff_longitude, // Save this
            'status' => 'requested',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ride created successfully',
            'data' => $ride
        ], 201);
    }

    /**
     * Approve a driver for a ride
     */
    public function approveDriver(Request $request)
    {
        // Validate request data
        $request->validate([
            'passenger_id' => 'required|exists:passengers,id',
            'ride_id' => 'required|exists:rides,id',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        // Find the ride
        $ride = Ride::find($request->ride_id);

        // Check if ride exists and belongs to the passenger
        if (!$ride || $ride->passenger_id != $request->passenger_id) {
            return response()->json([
                'success' => false,
                'message' => 'Ride not found or access denied'
            ], 404);
        }

        // Check if ride is already accepted
        if ($ride->status == 'accepted') {
            return response()->json([
                'success' => false,
                'message' => 'Ride already accepted by another driver'
            ], 400);
        }

        // Approve the driver
        $ride->update([
            'driver_id' => $request->driver_id,
            'status' => 'accepted'
        ]);

        // Update driver availability
        Driver::find($request->driver_id)->update(['is_available' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Driver approved successfully',
            'data' => $ride
        ]);
    }

    /**
     * Mark a ride as completed by passenger
     */
    public function markCompleted(Request $request)
    {
        // Validate request data
        $request->validate([
            'passenger_id' => 'required|exists:passengers,id',
            'ride_id' => 'required|exists:rides,id',
        ]);

        // Find the ride
        $ride = Ride::find($request->ride_id);

        // Check if ride exists and belongs to the passenger
        if (!$ride || $ride->passenger_id != $request->passenger_id) {
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

        // Mark passenger as completed
        $ride->update(['passenger_completed' => true]);

        // Check if both passenger and driver have marked as completed
        if ($ride->passenger_completed && $ride->driver_completed) {
            $ride->update(['status' => 'completed']);

            // Make driver available again
            if ($ride->driver_id) {
                Driver::find($ride->driver_id)->update(['is_available' => true]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Ride marked as completed by passenger',
            'data' => $ride
        ]);
    }
}
