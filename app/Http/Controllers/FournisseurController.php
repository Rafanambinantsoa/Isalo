<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fournisseurs = Fournisseur::all();
        if ($fournisseurs->isEmpty()) {
            return response()->json(['message' => 'No fournisseurs found (vide) '], 404);
        }
        return response()->json(Fournisseur::all());
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
            'contact' => ['required', 'string'],
            'adresse' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $fournisseur = Fournisseur::create($request->all());
        return response()->json($fournisseur, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $fournisseur = Fournisseur::find($id);
        if (!$fournisseur) {
            return response()->json(['message' => 'Fournisseur not found'], 404);
        }
        return response()->json($fournisseur);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fournisseur $fournisseur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string'],
            'contact' => ['required', 'string'],
            'adresse' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $fournisseur->update($request->all());
        $fournisseur->save();

        return response()->json($fournisseur, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fournisseur = Fournisseur::find($id);
        if (!$fournisseur) {
            return response()->json(['message' => 'Fournisseur not found'], 404);
        }
        $fournisseur->delete();
        return response()->json(['message' => 'Fournisseur deleted successfully'], 200);
    }
}
