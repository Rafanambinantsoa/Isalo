<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Vente;
use App\Models\VenteProduit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Vente::all();
        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'No data found',
            ], 404);
        }
        return response()->json($data, 200);
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
            'mode_paiment' => 'required',
            'produits' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        //Verification si le stock n'est pas suffisant
        foreach ($request->produits as $produit) {
            $produitInfo = Produit::find($produit['produit_id']);
            if ($produitInfo->quantite < $produit['quantite']) {
                return response()->json([
                    'message' => 'Il ne rest que ' . $produitInfo->quantite . ' ' . $produitInfo->nom,
                ], 400);
            }
        }

        // Calculer le montant total
        $montantTotal = 0;
        foreach ($request->produits as $produit) {
            // Récupérer le prix du produit depuis la base de données
            $produitInfo = Produit::find($produit['produit_id']);
            $montantTotal += $produitInfo->prix * $produit['quantite'];
        }

        $data = Vente::create([
            'mode_paiment' => $request->mode_paiment,
            'date_paiement' => now(),
            'montant' => $montantTotal,
        ]);

        foreach ($request->produits as $produit) {
            VenteProduit::create([
                'vente_id' => $data->id,
                'produit_id' => $produit['produit_id'],
                'quantite' => $produit['quantite'],
            ]);
        }

        //Mise a jour du stock
        foreach ($request->produits as $produit) {
            $produitInfo = Produit::find($produit['produit_id']);
            $produitInfo->quantite = $produitInfo->quantite - $produit['quantite'];
            $produitInfo->save();
        }

        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = Vente::find($id);
        if ($data == null) {
            return response()->json([
                'message' => 'No data found',
            ], 404);
        }
        $produits = $data->venteProduits;
        return response()->json([
            'data' => $data,
            'produits' => $produits,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vente $vente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'mode_paiment' => 'required',
            'produits' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $vente = Vente::find($id);
        if (!$vente) {
            return response()->json([
                'message' => 'Vente non trouvée',
            ], 404);
        }

        // 1. Restaurer l'ancien stock
        $anciensProduits = VenteProduit::where('vente_id', $vente->id)->get();
        foreach ($anciensProduits as $ancienProduit) {
            $produit = Produit::find($ancienProduit->produit_id);
            $produit->quantite += $ancienProduit->quantite;
            $produit->save();
        }

        // 2. Vérifier si le nouveau stock est suffisant
        foreach ($request->produits as $produit) {
            $produitInfo = Produit::find($produit['produit_id']);
            if ($produitInfo->quantite < $produit['quantite']) {
                return response()->json([
                    'message' => 'Il ne reste que ' . $produitInfo->quantite . ' ' . $produitInfo->nom,
                ], 400);
            }
        }

        // 3. Calculer le nouveau montant total
        $montantTotal = 0;
        foreach ($request->produits as $produit) {
            $produitInfo = Produit::find($produit['produit_id']);
            $montantTotal += $produitInfo->prix * $produit['quantite'];
        }

        // 4. Mettre à jour la vente
        $vente->update([
            'mode_paiment' => $request->mode_paiment,
            'montant' => $montantTotal,
        ]);

        // 5. Supprimer les anciens produits de la vente
        VenteProduit::where('vente_id', $vente->id)->delete();

        // 6. Ajouter les nouveaux produits
        foreach ($request->produits as $produit) {
            VenteProduit::create([
                'vente_id' => $vente->id,
                'produit_id' => $produit['produit_id'],
                'quantite' => $produit['quantite'],
            ]);
        }

        // 7. Mettre à jour le stock avec les nouvelles quantités
        foreach ($request->produits as $produit) {
            $produitInfo = Produit::find($produit['produit_id']);
            $produitInfo->quantite -= $produit['quantite'];
            $produitInfo->save();
        }

        return response()->json([
            'message' => 'Vente mise à jour avec succès',
            'data' => $vente,
        ], 200);
    }

    public function destroy($id)
    {
        $data = Vente::find($id);
        if (!$data) {
            return response()->json([
                'message' => 'Vente non trouvée',
            ], 404);
        }
        $data->delete();
        return response()->json([
            'message' => 'Vente supprimée avec succès',
        ], 200);

    }
}
