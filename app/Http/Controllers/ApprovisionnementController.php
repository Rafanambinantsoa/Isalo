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
        $approvisionnements = Approvisionnement::with('fournisseur')->get();
        if ($approvisionnements->isEmpty()) {
            return response()->json(['message' => 'No approvisionnements found (vide) '], 404);
        }
        return response()->json($approvisionnements);
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
    public function show($id)
    {
        $approvisionnement = Approvisionnement::find($id);
        if (!$approvisionnement) {
            return response()->json(['message' => 'Approvisionnement not found'], 404);
        }
        return response()->json($approvisionnement);
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
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Valider les données entrantes
        $validator = Validator::make($request->all(), [
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'montant_approvisionnement' => 'required|numeric',
            'produits' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $app = Approvisionnement::findOrFail($id);

        // Mettre à jour les informations de l'approvisionnement
        $app->update([
            'fournisseur_id' => $request->fournisseur_id,
            'montant_approvisionnement' => $request->montant_approvisionnement,
        ]);

        // Gestion des produits associés à cet approvisionnement
        foreach ($request->produits as $produit) {
            $produitInfo = Produit::find($produit['produit_id']);

            if (!$produitInfo) {
                continue; // Si le produit n'existe pas, passer au suivant
            }

            $historiqueApprovisionnement = Approvisionnement_produit::where('approvisionnement_id', $app->id)
                ->where('produit_id', $produit['produit_id'])
                ->first();

            if ($historiqueApprovisionnement) {
                // Si le produit est déjà dans l'historique, ajuster la quantité
                $ancienneQuantite = $historiqueApprovisionnement->quantite;
                $produitInfo->quantite -= $ancienneQuantite; // Retirer l'ancienne quantité du stock

                // Mettre à jour la nouvelle quantité dans le stock
                $produitInfo->quantite += $produit['quantite'];
                $produitInfo->save();

                // Mettre à jour la quantité dans l'historique d'approvisionnement
                $historiqueApprovisionnement->update([
                    'quantite' => $produit['quantite'],
                ]);
            } else {
                // Si le produit n'est pas dans l'historique, ajouter le produit avec la nouvelle quantité
                $produitInfo->quantite += $produit['quantite'];
                $produitInfo->save();

                // Enregistrer dans l'historique d'approvisionnement
                Approvisionnement_produit::create([
                    'approvisionnement_id' => $app->id,
                    'produit_id' => $produit['produit_id'],
                    'quantite' => $produit['quantite'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Approvisionnement mis à jour avec succès',
            'data' => $app,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $app = Approvisionnement::find($id);
        if (!$app) {
            return response()->json(['message' => 'Approvisionnement not found'], 404);
        }
        $app->delete();
        return response()->json(['message' => 'Approvisionnement deleted successfully'], 200);

    }
}
