<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\guests;
use Illuminate\Http\Request;

class GuestsController extends Controller
{
    public function index()
    {
        try {
            $guests = guests::with(['event', 'transactions'])->get();

            return response()->json([
                'success' => true,
                'guest' => $guests
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch guests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'event_id' => 'required|exists:events,id',
                'name' => 'required|string|max:255',
                'currency' => 'required|in:KHR,USD',
                'amount' => 'required|numeric|min:0',
                'phone' => 'nullable|string|max:50',
                'address' => 'nullable|string|max:255',
                'status' => 'required|in:pending,completed',
                'note' => 'nullable|string',
            ]);

            $guest = guests::create($request->all());

            return response()->json([
                'success' => true,
                'guest' => $guest,
                'message' => 'Guest created successfully'
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to create guest',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {

            $guest = guests::with(['event', 'transactions'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'guest' => $guest
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Guest not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

   public function update(Request $request, $id)
{
    try {

        $guest = guests::findOrFail($id);

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'currency' => 'required|in:KHR,USD',
            'amount' => 'required|numeric|min:0',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:pending,completed',
            'note' => 'nullable|string'
        ]);

        $guest->update($validated);

        return response()->json([
            'success' => true,
            'guest' => $guest,
            'message' => 'Guest updated successfully'
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => 'Failed to update guest',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function destroy($id)
    {
        try {

            $guest = guests::findOrFail($id);
            $guest->delete();

            return response()->json([
                'success' => true,
                'message' => 'Guest deleted successfully'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete guest',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
