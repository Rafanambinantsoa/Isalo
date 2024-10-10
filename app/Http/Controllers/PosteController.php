<?php

namespace App\Http\Controllers;

use App\Models\Poste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PosteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $postes = Poste::all();
        // check if its empty
        if ($postes->isEmpty()) {
            return response()->json(['message' => 'No postes found (vide) '], 404);
        } 
        return response()->json($postes);
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
            'nom' => ['required', 'string'],
            'libelle' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $poste = Poste::create($request->all());
            $poste->save();
            return response()->json($poste, 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $poste = Poste::find($id);
        if (!$poste) {
            return response()->json(['message' => 'Poste not found'], 404);
        }
        return response()->json($poste);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Poste $poste)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Poste $poste)
    {
        $validator = $request->validate([
            'nom' => ['required', 'string'],
            'libelle' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);
            $poste->update($validator);
            return response()->json($poste, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $poste = Poste::find($id);
        if (!$poste) {
            return response()->json(['message' => 'Poste not found'], 404);
        }
        $poste->delete();
        return response()->json(['message' => 'Poste deleted successfully'], 200);
    }
}
