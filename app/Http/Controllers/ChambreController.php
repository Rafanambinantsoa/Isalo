<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChambreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = Chambre::all();
        if ($all->isEmpty()) {
            return response()->json(['message' => 'No rooms found (vide) '], 404);
        }
        return response()->json(Chambre::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'numero_chambre' => ['required', 'string'],
            'id_categorie_chambre' => ['required', 'integer', 'exists:categorie_chambres,id'],
            'nombre_lits' => ['required', 'integer'],
            'type_lits' => ['required', 'string'],
            'prix_nuitee' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $chambre = Chambre::create($request->all());

        return response()->json($chambre, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Chambre::find($id);
        if (!$data) {
            return response()->json([
                'message' => 'Chambre not found',
            ], 404);
        }
        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chambre $chambre)
    {
        $validator = Validator::make($request->all(), [
            'numero_chambre' => ['required', 'string'],
            'id_categorie_chambre' => ['required', 'integer', 'exists:categorie_chambres,id'],
            'nombre_lits' => ['required', 'integer'],
            'type_lits' => ['required', 'string'],
            'prix_nuitee' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $chambre->update($request->all());
        $chambre->save();
        return response()->json($chambre, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $chambre = Chambre::find($id);
        if (!$chambre) {
            return response()->json(['message' => 'Chambre not found'], 404);
        }
        $chambre->delete();
        return response()->json(['message' => 'Chambre deleted successfully'], 200);
    }
}
