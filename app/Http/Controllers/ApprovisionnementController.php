<?php

namespace App\Http\Controllers;

use App\Models\Approvisionnement;
use App\Models\Historique_approvisionnements_toiles;
use App\Models\StockToile;
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

        // Vérifie si la collection est vide
        if ($approvisionnements->isEmpty()) {
            return response()->json(['message' => 'No approvisionnements found (vide)'], 404);
        }

        // Pour chaque approvisionnement, récupérer les produits associés
        $all = $approvisionnements->map(function ($approvisionnement) {
            $produits = Historique_approvisionnements_toiles::with('produit')->where('approvi_id', $approvisionnement->id)->get();
            return [
                'approvisionnement' => $approvisionnement,
                'produits' => $produits,
            ];
        });

        return response()->json([
            'approvisionnements' => $all,
        ], 200);
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
        //
        $app = Approvisionnement::create([
            'fournisseur_id' => $request->fournisseur_id,
            'montant_approvisionnement' => $request->montant_approvisionnement,
        ]);

        //Ajoute dans la table stock_toile
        foreach ($request->produits as $produit) {

            //verification s'il y a deja un produit dans la table stock_toile
            $stock = StockToile::where('produit_id', $produit['produit_id'])->first();
            if ($stock) {
                $stock->quantite += $produit['quantite'];
                $stock->save();
                continue;
            }

            StockToile::create([
                'produit_id' => $produit['produit_id'],
                'quantite' => $produit['quantite'],
            ]);

            //Historique_approvisionnements_toiles
            Historique_approvisionnements_toiles::create([
                'approvi_id' => $app->id,
                'produit_id' => $produit['produit_id'],
                'quantite' => $produit['quantite'],
            ]);

        }

        return response()->json([
            'message' => 'Approvisionnement created successfully',
            'approvisionnement' => $app,
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

        $approvisionnement->load('fournisseur');

        $approvisionnement->produits = Historique_approvisionnements_toiles::with('produit')->where('approvi_id', $approvisionnement->id)->get();

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
        // Validation des champs
        $validator = Validator::make($request->all(), [
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'montant_approvisionnement' => 'required|numeric',
            'produits' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Récupérer l'approvisionnement existant
        $app = Approvisionnement::find($id);
        if (!$app) {
            return response()->json(['message' => 'Approvisionnement not found'], 404);
        }

        // Mettre à jour l'approvisionnement
        $app->update([
            'fournisseur_id' => $request->fournisseur_id,
            'montant_approvisionnement' => $request->montant_approvisionnement,
        ]);

        // Mise à jour des produits et du stock
        foreach ($request->produits as $produit) {
            // Vérifie si le produit est déjà dans le stock
            $stock = StockToile::where('produit_id', $produit['produit_id'])->first();
            if ($stock) {
                // Met à jour la quantité dans le stock
                $stock->quantite += $produit['quantite'];
                $stock->save();
            } else {
                // Crée une nouvelle entrée si le produit n'est pas encore dans le stock
                StockToile::create([
                    'produit_id' => $produit['produit_id'],
                    'quantite' => $produit['quantite'],
                ]);
            }

            // Enregistre l'historique pour chaque produit mis à jour
            Historique_approvisionnements_toiles::create([
                'approvi_id' => $app->id,
                'produit_id' => $produit['produit_id'],
                'quantite' => $produit['quantite'],
            ]);
        }

        return response()->json([
            'message' => 'Approvisionnement updated successfully',
            'approvisionnement' => $app,
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

    public function getStockEtat()
    {
        //Afficher notre etat de stock  , genre la quantité de chaque produit dans le stock ,
        $stock = StockToile::all();
        if ($stock->isEmpty()) {
            return response()->json(['message' => 'Notre stock est vide '], 404);
        }
        return response()->json($stock);
    }
}
