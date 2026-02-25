<?php

namespace App\Http\Controllers;

use App\Models\guests;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class GuestsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function index()
    {
        try {
           $guests = guests::with(['event','transactions'])->get();
            return response()->json([
                'success' => true,
                'guests' => $guests
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch guests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
     
    }

    /**
     * Store a newly created resource in storage.
     */
      public function store(Request $request)
    {
        try {
            $request->validate([
                'event_id' => 'required|exists:events,id',
                'guest_no' => 'required|integer',
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:50',
                'address' => 'nullable|string|max:255',
               'status' => 'required|in:pending,completed',
                'note' => 'nullable|string',
            ]);

            $guest = guests::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $guest,
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


    /**
     * Display the specified resource.
     */
      public function show($id)
    {
        try {
           $guest = guests::with(['event', 'transactions'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'guests' => $guest
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch guest',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(guests $guests)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
      public function update(Request $request, $id)
    {
        try {
            $guest = guests::findOrFail($id);

            $request->validate([
                'event_id' => 'required|exists:events,id',
                'guest_no' => 'required|integer',
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:50',
                'address' => 'nullable|string|max:255',
                'note' => 'nullable|string',
            ]);

            $guest->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $guest,
                'message' => 'Guest updated successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update guest',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
       public function destroy($id)
    {
        try {
            $guest = guests::findOrFail($id);
            $guest->delete();

            return response()->json([
                'success' => true,
                'message' => 'Guest deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete guest',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
