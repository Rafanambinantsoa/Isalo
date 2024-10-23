<?php

namespace App\Http\Controllers;

use App\Models\Paiment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MethodePaimentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paiments = Paiment::all();
        if ($paiments->isEmpty()) {
            return response()->json(['message' => 'No paiments found (vide) '], 404);
        }
        return response()->json(Paiment::all());
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
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $paiment = Paiment::create($request->all());
        return response()->json($paiment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $paiment = Paiment::find($id);
        if (!$paiment) {
            return response()->json(['message' => 'Paiment not found'], 404);
        }
        return response()->json($paiment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paiment $paiment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Paiment $paiment)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $paiment->update($request->all());
        $paiment->save();
        return response()->json($paiment, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $paiment = Paiment::find($id);
        if (!$paiment) {
            return response()->json(['message' => 'Paiment not found'], 404);
        }
        $paiment->delete();
        return response()->json(['message' => 'Paiment deleted successfully'], 200);
    }
}
