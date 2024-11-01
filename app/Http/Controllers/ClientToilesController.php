<?php

namespace App\Http\Controllers;

use App\Models\ClientToiles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientToilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientToiles = ClientToiles::all();
        if ($clientToiles->isEmpty()) {
            return response()->json(['message' => 'No clientToiles found (vide) '], 404);
        }
        return response()->json(ClientToiles::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string'],
            'address' => ['required', 'string'],
            'cin' => ['required', 'numeric'],
            'preferences' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $clientToiles = ClientToiles::create($request->all());

        return response()->json($clientToiles, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $clientToile = ClientToiles::find($id);
        if (!$clientToile) {
            return response()->json([
                'message' => 'ClientToile not found',
            ], 404);
        }
        return response()->json($clientToile, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $clientToiles = ClientToiles::find($id);
        if (!$clientToiles) {
            return response()->json(['message' => 'ClientToiles not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string'],
            'address' => ['required', 'string'],
            'cin' => ['required', 'numeric'],
            'preferences' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $clientToiles->update($request->all());
        $clientToiles->save();
        return response()->json($clientToiles, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $clientToiles = ClientToiles::find($id);
        if (!$clientToiles) {
            return response()->json(['message' => 'ClientToiles not found'], 404);
        }
        $clientToiles->delete();
        return response()->json(['message' => 'ClientToiles deleted successfully'], 200);
    }
}
