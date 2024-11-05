<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use App\Models\PaimentReservation;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::all();
        if ($reservations->isEmpty()) {
            return response()->json(['message' => 'No reservations found (vide) '], 404);
        }
        return response()->json(Reservation::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Chambre_id' => ['required', 'numeric', 'exists:chambres,id'],
            'Client_id' => ['required', 'numeric'],
            'date_arrive' => ['required', 'date', 'after_or_equal:today'],
            'date_depart' => ['required', 'date', 'after:date_arrive'],
            'is_avance_paid' => ['required', 'numeric'],

        ]);

        if ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors(),
            ], 400);
        }

        $chambreId = $request->Chambre_id;
        $dateArrivee = Carbon::parse($request->date_arrive);
        $dateDepart = Carbon::parse($request->date_depart);

        // Vérifier les réservations existantes pour cette chambre
        $chevauchement = Reservation::where('Chambre_id', $chambreId)
            ->where(function ($query) use ($dateArrivee, $dateDepart) {
                $query->whereBetween('date_arrive', [$dateArrivee, $dateDepart])
                    ->orWhereBetween('date_depart', [$dateArrivee, $dateDepart])
                    ->orWhere(function ($q) use ($dateArrivee, $dateDepart) {
                        $q->where('date_arrive', '<=', $dateArrivee)
                            ->where('date_depart', '>=', $dateDepart);
                    });
            })->exists();

        if ($chevauchement) {
            return response()->json([
                'message' => 'La chambre n\'est pas disponible pour les dates demandées.',
            ], 409); // 409 Conflict
        }

        //Determination de la saison  saison basse si janvier jusqu'a juin ou saison haute si juillet à decembre
        $date = Carbon::now();
        $saison = ($date->month >= 1 && $date->month <= 6) ? "basse" : "haute";

        $dateArrive = Carbon::parse($request->date_arrive);
        $dateDepart = Carbon::parse($request->date_depart);

        $chambre = Chambre::find($request->Chambre_id);

        // Calculate the difference in days
        $nombreDeNuits = $dateDepart->diffInDays($dateArrive);

        if ($nombreDeNuits < 1) {
            $nombreDeNuits = $nombreDeNuits * -1;
        }

        // Calculate the total price
        $prixTotal = $chambre->prix_nuitee * $nombreDeNuits;

        //Avance requise selon la saison
        $avance = $saison == "basse" ? ($prixTotal * 20) / 100 : ($prixTotal * 50) / 100;

        //Creation de la reservation
        $reservation = Reservation::create([
            'Chambre_id' => $request->Chambre_id,
            'Client_id' => $request->Client_id,
            'date_arrive' => $request->date_arrive,
            'date_depart' => $request->date_depart,
            'is_avance_paid' => $request->is_avance_paid,
            'avance_requise' => $avance,
            'prix_total' => $prixTotal,
        ]);

        //Mise a jour de l'etat de la chambre
        $chambre->etat_chambre = "occupee";
        $chambre->save();

        //Enregistrement du paiment du client
        $is_avance_paid = $request->is_avance_paid;

        PaimentReservation::create([
            'reservation_id' => $reservation->id,
            'client_id' => $request->Client_id,
            'montant' => $prixTotal,
            'montant_restant' => $prixTotal - $avance,
        ]);

        return response()->json([
            'message' => "Reservation effectuee avec succes",
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                'message' => 'Réservation introuvable.',
            ]);
        }
        return response()->json($reservation);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                'message' => 'Réservation introuvable.',
            ], 404); // 404 Not Found
        }

        $validator = Validator::make($request->all(), [
            'Chambre_id' => ['required', 'numeric', 'exists:chambres,id'],
            'Client_id' => ['required', 'numeric'],
            'date_arrive' => ['required', 'date', 'after_or_equal:today'],
            'date_depart' => ['required', 'date', 'after:date_arrive'],
            'is_avance_paid' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400); // 400 Bad Request
        }

        $chambreId = $request->Chambre_id;
        $dateArrivee = Carbon::parse($request->date_arrive);
        $dateDepart = Carbon::parse($request->date_depart);

        // Vérifier les réservations existantes pour cette chambre
        $chevauchement = Reservation::where('Chambre_id', $chambreId)
            ->where('id', '!=', $id) // Exclure la réservation en cours de mise à jour
            ->where(function ($query) use ($dateArrivee, $dateDepart) {
                $query->whereBetween('date_arrive', [$dateArrivee, $dateDepart])
                    ->orWhereBetween('date_depart', [$dateArrivee, $dateDepart])
                    ->orWhere(function ($q) use ($dateArrivee, $dateDepart) {
                        $q->where('date_arrive', '<=', $dateArrivee)
                            ->where('date_depart', '>=', $dateDepart);
                    });
            })->exists();

        if ($chevauchement) {
            return response()->json([
                'message' => 'La chambre n\'est pas disponible pour les dates demandées.',
            ], 409); // 409 Conflict
        }

        // Détermination de la saison
        $date = Carbon::now();
        $saison = ($date->month >= 1 && $date->month <= 6) ? "basse" : "haute";

        $dateArrive = Carbon::parse($request->date_arrive);
        $dateDepart = Carbon::parse($request->date_depart);

        $chambre = Chambre::find($request->Chambre_id);

        // Calcul du nombre de nuits
        $nombreDeNuits = $dateDepart->diffInDays($dateArrive);
        $nombreDeNuits = $nombreDeNuits < 0 ? $nombreDeNuits * -1 : $nombreDeNuits;

        // Calcul du prix total
        $prixTotal = $chambre->prix_nuitee * $nombreDeNuits;

        // Avance requise selon la saison
        $avance = $saison == "basse" ? ($prixTotal * 20) / 100 : ($prixTotal * 50) / 100;

        // Mise à jour de la réservation
        $reservation->update([
            'Chambre_id' => $request->Chambre_id,
            'Client_id' => $request->Client_id,
            'date_arrive' => $request->date_arrive,
            'date_depart' => $request->date_depart,
            'is_avance_paid' => $request->is_avance_paid,
            'avance_requise' => $avance,
            'prix_total' => $prixTotal,
        ]);

        // Mise à jour de l'état de la chambre
        $chambre->etat_chambre = "occupee";
        $chambre->save();

        // Enregistrement du paiment du client
        $paiment = PaimentReservation::where('reservation_id', $id)->first();

        $paiment->update([
            'reservation_id' => $id,
            'client_id' => $request->Client_id,
            'montant' => $prixTotal,
            'montant_restant' => $prixTotal - $avance,
        ]);

        return response()->json([
            'message' => "Réservation mise à jour avec succès",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Trouver la réservation
        $reservation = Reservation::find($id);

        // Vérifier si la réservation existe
        if (!$reservation) {
            return response()->json([
                'message' => 'Réservation introuvable.',
            ], 404); // 404 Not Found
        }

        // Définir la date d'arrivée et l'heure actuelle
        $dateArrivee = Carbon::parse($reservation->date_arrive);
        $now = Carbon::now();

        // Calculer la différence en heures
        $diff = $dateArrivee->diffInHours($now);
        if ($diff < 0) {
            $diff = $diff * -1;
        }

        // Vérifier que l'annulation n'est pas effectuée moins de 24 heures avant la date d'arrivée
        if ($diff < 24) {
            return response()->json([
                'message' => 'L\'annulation ne peut pas être effectuée moins de 24 heures avant la date d\'arrivée.',
            ], 403); // 403 Forbidden
        }

        $avance = $reservation->avance_requise; // Montant de l'avance
        $penalite = 0;

        // Si l'annulation est faite moins de 48 heures avant, appliquer une pénalité de 20%
        if ($diff < 48) {
            $penalite = $avance * 0.20; // Pénalité de 20% de l'avance
            $avance -= $penalite; // Réduire l'avance par la pénalité
        }

        // Mettre à jour l'état de la chambre
        $chambre = Chambre::find($reservation->Chambre_id);
        if ($chambre) {
            $chambre->etat_chambre = "disponible";
            $chambre->save();
        }

        // Enregistrement du paiement du client
        $paiment = PaimentReservation::where('reservation_id', $id)->first();
        if ($paiment) {
            $paiment->update([
                'montant' => $penalite, // Pénalité si applicable
                'montant_restant' => 0, // Montant restant après pénalité
                'type' => 'penalite', // Type en fonction de la pénalité
            ]);
            $paiment->save();
        }

        // Répondre avec succès
        $message = $penalite > 0
        ? "Réservation annulée avec succès. Une pénalité de $penalite a été appliquée. Montant remboursé : $avance."
        : "Réservation annulée avec succès. Le montant de l'avance a été remboursé.";

        // Supprimer la réservation
        $reservation->delete();

        return response()->json([
            'message' => $message,
        ]);
    }

}
