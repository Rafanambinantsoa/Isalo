<?php

namespace App\Http\Controllers;

use App\Models\Conger;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
    // public function multiUpload(Request $request)
    // {
    //     $request->validate([
    //         'files.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Adjust the validation rules as needed
    //     ]);

    //     $filePaths = [];
    //     foreach ($request->file('files') as $file) {
    //         $path = $file->store('uploads', 'public');
    //         $filePaths[] = $path;

    //         // // Save file info in the database
    //         // Upload::create([
    //         //     'file_name' => $file->getClientOriginalName(),
    //         //     'file_path' => $path,
    //         // ]);
    //     }

    //     return response()->json(['file_paths' => $filePaths], 200);
    //
    public function multiUpload(Request $request)
    {
        // $request->validate([ // Commenter la validation pour le test
        //     'files.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        // ]);

        if (!$request->hasFile('files')) {
            return response()->json(['error' => 'No files were uploaded.'], 400);
        }

        $filePaths = [];
        foreach ($request->file('files') as $file) {
            if ($file->isValid()) { // Vérifier si le fichier est valide
                $path = $file->store('uploads', 'public');
                $filePaths[] = $path;

                // Sauvegarde dans la base de données (décommenter si nécessaire)
                // Upload::create([
                //     'file_name' => $file->getClientOriginalName(),
                //     'file_path' => $path,
                // ]);
            } else {
                return response()->json(['error' => 'Invalid file uploaded.'], 400);
            }
        }

        return response()->json(['file_paths' => $filePaths], 200);
    }
}
