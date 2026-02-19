<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\FuelStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuelStorageController extends Controller
{
    public function index()
    {
        $records = FuelStorage::latest()->paginate(15);
        $currentBalance = FuelStorage::latest()->value('running_balance') ?? 0;

        return view('pages.shared.manage-storage', compact('records', 'currentBalance'));
    }

    public function list()
    {
        $history = FuelStorage::orderByDesc('transaction_datetime')->limit(50)->get();
        $recent = $history->take(5);

        return response()->json([
            'recent' => $recent,
            'history' => $history,
            'currentLevel' => $history->first()->running_balance ?? 0
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'container_type' => 'required|in:Fuel Can,Fuel Drum',
            'transaction_type' => 'required|in:added,removed',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string'
        ]);

        // Check capacity limit
        $lastRecord = FuelStorage::latest()->first();
        $currentBalance = $lastRecord ? $lastRecord->running_balance : 0;

        if ($request->transaction_type === 'added' && ($currentBalance + $request->amount > 1000)) {
            return response()->json([
                'message' => 'Cannot add fuel. Tank capacity exceeded.'
            ], 422);
        }

        return DB::transaction(function () use ($request) {

            $lastRecord = FuelStorage::latest()->first();
            $currentBalance = $lastRecord ? $lastRecord->running_balance : 0;

            if ($request->transaction_type === 'added') {
                $newBalance = $currentBalance + $request->amount;
            } else {

                if ($request->amount > $currentBalance) {
                    return response()->json([
                        'message' => 'Not enough fuel stock available.'
                    ], 422);
                }

                $newBalance = $currentBalance - $request->amount;
            }

            $record = FuelStorage::create([
                'transaction_datetime' => now(),
                'container_type' => $request->container_type,
                'transaction_type' => $request->transaction_type,
                'amount' => $request->amount,
                'running_balance' => $newBalance,
                'note' => $request->note,
            ]);

            return response()->json([
                'message' => 'Fuel transaction recorded successfully.',
                'data' => $record
            ]);
        });
    }
}
