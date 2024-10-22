<?php

namespace App\Console\Commands;

use App\Models\Conger;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MiseAjourCongerEmployeeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:mise-ajour-conger-employee-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Statut des congés mis à jour avec succès.');
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

        // Mail::raw('EveryMin ', function ($message) {
        //     $message->to('tsukasashishiosama@gmail.com')
        //         ->subject('Test Email');
        // });

        //Send a message to logs

        // $this->info('Statut des congés mis à jour avec succès.');

    }
}
