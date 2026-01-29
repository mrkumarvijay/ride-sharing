<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of all rides
     */
    public function index()
    {
        // Get all rides with passenger and driver relationships
        $rides = Ride::with(['passenger', 'driver'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Rides fetched successfully',
            'data' => $rides
        ]);
    }

    /**
     * Display the specified ride details
     */
    public function show($id)
    {
        // Find the ride with passenger and driver relationships
        $ride = Ride::with(['passenger', 'driver'])
            ->find($id);

        // Check if ride exists
        if (!$ride) {
            return response()->json([
                'success' => false,
                'message' => 'Ride not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ride details fetched successfully',
            'data' => $ride
        ]);
    }
}
