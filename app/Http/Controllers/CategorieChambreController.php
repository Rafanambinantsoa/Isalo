<?php

namespace App\Http\Controllers;

use App\Models\CategorieChambre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategorieChambreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = CategorieChambre::all();
        if ($all->isEmpty()) {
            return response()->json(['message' => 'No categories found (vide) '], 404);
        }
        return response()->json(CategorieChambre::all());
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

        $categorieChambre = CategorieChambre::create($request->all());

        return response()->json($categorieChambre, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $categorieChambre = CategorieChambre::find($id);
        if (!$categorieChambre) {
            return response()->json([
                'message' => 'Categorie not found',
            ], 404);
        }
        return response()->json($categorieChambre);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $categorieChambre = CategorieChambre::find($id);
        if (!$categorieChambre) {
            return response()->json(['message' => 'Categorie not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // dd($categorieChambre->type);

        $categorieChambre->update($request->all());
        $categorieChambre->save();

        return response()->json($categorieChambre, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categorieChambre = CategorieChambre::find($id);
        if (!$categorieChambre) {
            return response()->json(['message' => 'Categorie not found'], 404);
        }
        $categorieChambre->delete();
        return response()->json(['message' => 'Categorie deleted successfully'], 200);
    }
}
