<?php

namespace App\Console\Commands;

use App\Models\Produit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AlertProduitInsuffisant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:alert-produit-insuffisant';

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
        Log::info('Alerte des produits insuffisants.');

        $produits = Produit::where('quantite', '<', 5)->get();

        //ENvoyer par email les produits le noms des produits qui auraient besoin de reapprovisionnement
        Mail::raw('Test email body' . $produits, function ($message) {
            $message->to('tsukasashishiosama@gmail.com')
                ->subject('Test Email');
        });

    }
}
