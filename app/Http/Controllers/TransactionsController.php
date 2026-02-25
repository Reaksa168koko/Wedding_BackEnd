<?php

namespace App\Http\Controllers;

use App\Models\transactions;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        try {
            $transactions = transactions::with([ 'guest','event'])->get();
            return response()->json([
                'success' => true,
                'transaction' => $transactions
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch transactions',
                'error' => $th->getMessage()
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
                'guest_id' => 'required|exists:guests,id',
                'event_id' => 'required|exists:events,id',
                'currency' => 'required|in:KHR,USD',
                'amount' => 'required|numeric',
                'note' => 'nullable|string',
            ]);

            $transaction = transactions::create($request->all());

            return response()->json([
                'success' => true,
                'transaction' => $transaction,
                'message' => 'Transaction created successfully'
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create transaction',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
      public function show($id)
    {
        try {
            $transaction = transactions::with([ 'guest','event'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'transaction' => $transaction
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch transaction',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(transactions $transactions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
    {
        try {
            $transaction = transactions::findOrFail($id);

            $request->validate([
                'guest_id' => 'required|exists:guests,id',
                'currency' => 'required|in:KHR,USD',
                'amount' => 'required|numeric',
                'note' => 'nullable|string',
            ]);

            $transaction->update($request->all());

            return response()->json([
                'success' => true,
                'transaction' => $transaction,
                'message' => 'Transaction updated successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update transaction',
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
            $transaction = transactions::findOrFail($id);
            $transaction->delete();

            return response()->json([
                'success' => true,
                'message' => 'Transaction deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete transaction',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
