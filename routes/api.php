<?php

use App\Http\Controllers\ApprovisionnementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\CategorieChambreController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ChambreController;
use App\Http\Controllers\ClientToilesController;
use App\Http\Controllers\CongerController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\PosteController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TypeCongerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VenteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication
// Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
Route::post('/reset-password', [NewPasswordController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

//Related route for users
Route::resource('users', UserController::class);
Route::post('/employee/add/', [UserController::class, 'store']);

//Related route for poste
Route::resource('postes', PosteController::class);
Route::get('/postes/employee/{id}', [PosteController::class, 'getListEmployeeByPoste']);

//Related route for conger
Route::resource('congers', CongerController::class);
Route::resource('type_congers', TypeCongerController::class);
Route::patch('/congers/{id}/accept', [CongerController::class, 'acceptConger']);

//Related route for FOurnisseur
Route::resource('fournisseurs', FournisseurController::class);

//Related route for Methode paiment  fournisseur
// Route::resource('paiments', MethodePaimentController::class);

//Related route  For produit
Route::resource('categorie', CategorieController::class);
Route::get('/categorie/{id}/produits', [ProduitController::class, 'getProduitByCategorie']);
Route::resource('produits', ProduitController::class);

//Related route for vente
Route::resource('ventes', VenteController::class);

// Related route for Stock
Route::resource('stockstoiles', ApprovisionnementController::class);
Route::get('etat/stockstoiles', [ApprovisionnementController::class, 'getStockEtat']);

//Related route for Chambre
Route::resource('chambrecategorie', CategorieChambreController::class);
Route::resource('chambres', ChambreController::class);
Route::post('/uploads', [TestController::class, 'multiUpload']);

//Related route for Client toiles
Route::resource('clienttoiles', ClientToilesController::class);

//Related route for reservation
Route::resource('reservations', ReservationController::class);
