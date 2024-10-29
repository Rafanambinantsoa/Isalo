<?php

namespace App\Http\Controllers;

use App\Models\Conger;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CongerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $congers = Conger::all();
        // check if its empty
        if ($congers->isEmpty()) {
            return response()->json(['message' => 'No congers found (vide) '], 404);
        }
        return response()->json($congers);
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
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'type_conger_id' => ['required', 'numeric', 'exists:type_congers,id'],
            'date_debut' => ['required', 'date', 'after_or_equal:' . Carbon::now()->toDateString()], // La date de début doit être dans le futur ou aujourd'hui
            'date_fin' => ['required', 'date', 'after_or_equal:date_debut'], // La date de fin doit être après la date de début
            'motif' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Calcul automatique du nombre de jours
        $dateDebut = Carbon::parse($request->input('date_debut'));
        $dateFin = Carbon::parse($request->input('date_fin'));
        $nombreJours = $dateDebut->diffInDays($dateFin) + 1;

        $conger = Conger::create([
            'user_id' => $request->input('user_id'),
            'type_conge_id' => $request->type_conger_id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'motif' => $request->motif,
            'nombre_jours' => $nombreJours,
        ]);
        return response()->json($conger, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $conger = Conger::find($id);
        if (!$conger) {
            return response()->json(['message' => 'Conger not found'], 404);
        }
        return response()->json($conger);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conger $conger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $conger = Conger::find($id);

        // check if its empty
        if (!$conger) {
            return response()->json(['message' => 'Conger not found'], 404);
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'string'],
            'type_conger_id' => ['required', 'string', 'exists:type_congers,id'],
            'date_debut' => ['required', 'date', 'after_or_equal:' . Carbon::now()->toDateString()], // La date de début doit être dans le futur ou aujourd'hui
            'date_fin' => ['required', 'date', 'after_or_equal:date_debut'], // La date de fin doit être après la date de début
            'motif' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Calcul automatique du nombre de jours
        $dateDebut = Carbon::parse($request->input('date_debut'));
        $dateFin = Carbon::parse($request->input('date_fin'));
        $nombreJours = $dateDebut->diffInDays($dateFin) + 1;

        //Mis a jour du conger
        $conger->update([
            'user_id' => $request->user_id,
            'type_conger_id' => $request->type_conger_id,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'motif' => $request->motif,
            'nombre_jours' => $nombreJours,
        ]);
        return response()->json($conger, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $conger = Conger::find($id);
        if (!$conger) {
            return response()->json(['message' => 'Conger not found'], 404);
        }
        $conger->delete();
        return response()->json(['message' => 'Conger deleted successfully'], 200);
    }

    public function acceptConger($id)
    {
        $conger = Conger::find($id);

        // dd($conger);
        if (!$conger) {
            return response()->json(['message' => 'Conger not found'], 404);
        }

        if ($conger->statut == 'accepte') {
            return response()->json(['message' => 'Conger already accepted'], 400);
        }
        //recheche de l'employé
        $user = User::find($conger->user_id);

        //Verification si l'employe a deja un conger en cours
        if ($user->est_en_conge == 1) {
            return response()->json(['message' => "L'employé a un conger en cours"], 400);
        }

        // Si le nombre de conger annuel est atteint
        if ($user->nombre_jours_conges == 0) {
            return response()->json(['message' => 'No conger available'], 400);
        }

        //Verification si le type de conger est specifique
        if ($conger->type_conge_id == 1) {
            $user->nombre_jours_conges = $user->nombre_jours_conges - $conger->nombre_jours;
            //Mise a jour de l'etat de l'employé en statut en cours de conger
            $user->est_en_conge = 1;
            $user->save();

            $conger->statut = 'accepte';
            $conger->save();
            return response()->json(['message' => 'Conger accepted successfully'], 200);
        }

        //Mise a jour du jour de conger restant
        // recherche de l'employé pour avoir son jour restant et le mettre à jour
        $nombreJoursTypeConger = $conger->typeConge->duree;

        $disponible = $user->nombre_jours_conges;

        if ($disponible < $nombreJoursTypeConger) {
            return response()->json(['message' => 'Not enough days left'], 400);
        }

        $user->nombre_jours_conges = $user->nombre_jours_conges - $nombreJoursTypeConger;
        //Mise a jour de l'etat de l'employé en statut en cours de conger
        $user->est_en_conge = 1;
        $user->save();
        $conger->statut = 'accepte';
        $conger->save();

        return response()->json(['message' => 'Conger accepted successfully'], 200);
    }
}
