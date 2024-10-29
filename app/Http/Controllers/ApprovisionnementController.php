<?php

namespace App\Http\Controllers;

use App\Models\Approvisionnement;
use App\Models\Approvisionnement_produit;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApprovisionnementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'montant_approvisionnement' => 'required|numeric',
            'produits' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $app = Approvisionnement::create([
            'fournisseur_id' => $request->fournisseur_id,
            'montant_approvisionnement' => $request->montant_approvisionnement,
        ]);

        //Ajouter les produits dans la base de données stock
        foreach ($request->produits as $produit) {
            $produitInfo = Produit::find($produit['produit_id']);
            $produitInfo->quantite += $produit['quantite'];
            $produitInfo->save();

            //enregistrement dans l'historique approvisionnement produit
            Approvisionnement_produit::create([
                'approvisionnement_id' => $app->id,
                'produit_id' => $produit['produit_id'],
                'quantite' => $produit['quantite'],
            ]);
        }

        return response()->json([
            'message' => 'Approvisionnement creé avec succès',
            'data' => $app,
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Approvisionnement $approvisionnement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Approvisionnement $approvisionnement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Approvisionnement $approvisionnement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Approvisionnement $approvisionnement)
    {
        //
    }
}
