<?php

namespace App\Http\Controllers;

use App\Models\typeConger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeCongerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = typeConger::all();
        if ($data->isEmpty()) {
            return response()->json(['message' => 'No type conges found (vide) '], 404);
        }
        return response()->json($data);
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
            'nom' => ['required', 'string' , 'unique:type_congers'],
            'libelle' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $typeConge = typeConger::create($request->all());
        return response()->json($typeConge, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $typeConge = typeConger::find($id);
        if (!$typeConge) {
            return response()->json(['message' => 'Type conge not found'], 404);
        }
        return response()->json($typeConge);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(typeConger $typeConge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $typeConge = typeConger::find($id);
        if (!$typeConge) {
            return response()->json(['message' => 'Type conge not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string'],
            'libelle' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        // Directement mettre à jour l'enregistrement
        $typeConge->update([
            'nom' => $request->nom,
            'libelle' => $request->libelle,
            'description' => $request->description,
        ]);
        $typeConge->save();

    
        return response()->json($typeConge, 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $typeConge = typeConger::find($id);
        if (!$typeConge) {
            return response()->json(['message' => 'Type conge not found'], 404);
        }
        $typeConge->delete();
        return response()->json(['message' => 'Type conge deleted successfully'], 200);
    }
}
