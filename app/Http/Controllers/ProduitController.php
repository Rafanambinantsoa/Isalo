<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = Produit::all();
        if ($all->isEmpty()) {
            return response()->json(['message' => 'No products found (vide) '], 404);
        }
        return response()->json(Produit::all());
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
            'categorie_id' => ['required', 'integer', 'exists:categories,id'],
            'quantite' => ['required', 'integer'],
            'prix' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $produit = Produit::create($request->all());

        return response()->json($produit, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $produit = Produit::find($id);

        if (!$produit) {
            return response()->json([
                'message' => 'Produit not found',
            ]);
        }
        return response()->json($produit);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produit $produit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produit $produit)
    {
        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string'],
            'categorie_id' => ['required', 'integer', 'exists:categories,id'],
            'quantite' => ['required', 'integer'],
            'prix' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $produit->update($request->all());
        $produit->save();

        return response()->json($produit, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produit = Produit::find($id);
        if (!$produit) {
            return response()->json(['message' => 'Produit not found'], 404);
        }

        if ($produit->quantite > 0) {
            return response()->json(['message' => 'Le produit ne peut pas etre supprimé car il y en a dans le stock'], 400);
        }

        $produit->delete();
        return response()->json(['message' => 'Produit deleted successfully'], 200);
    }

    public function getProduitByCategorie($id)
    {
        $categorie = Categorie::find($id);

        if (!$categorie) {
            return response()->json(['message' => 'Categorie not found'], 404);
        }
        $data = $categorie->produits;
        return response()->json($data);
    }
}
