<?php

namespace App\Http\Controllers;

use App\Models\Conger;
use App\Models\User;
use Carbon\Carbon;

class TestController extends Controller
{
    public function index()
    {
        // Étape 1 : Trouver tous les employés dont le congé est en cours
        $congesEnCours = Conger::where('date_debut', '<=', Carbon::now()->toDateString())
            ->where('date_fin', '>=', Carbon::now()->toDateString())
            ->get();

        foreach ($congesEnCours as $conger) {
            // Mettre à jour le statut de l'utilisateur à "en congé"
            $user = User::find($conger->user_id);
            $user->est_en_conge = 1;
            $user->save();
        }

        // Étape 2 : Mettre à jour les employés dont le congé est terminé
        $congesTermines = Conger::where('date_fin', '<', Carbon::now()->toDateString())->get();

        foreach ($congesTermines as $conger) {
            $user = User::find($conger->user_id);
            // Si l'utilisateur n'a pas d'autres congés en cours, on le marque comme "non en congé"
            $congeActuel = Conger::where('user_id', $conger->user_id)
                ->where('date_debut', '<=', Carbon::now()->toDateString())
                ->where('date_fin', '>=', Carbon::now()->toDateString())
                ->exists();

            if (!$congeActuel) {
                $user->est_en_conge = 0;
                $user->save();
            }
        }

        return response()->json("ok");
    }
}
