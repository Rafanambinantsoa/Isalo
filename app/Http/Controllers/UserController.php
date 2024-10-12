<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validators = Validator::make($request->all(), [
            'matricule' => ['required', 'numeric' , 'unique:users'],
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'date_naiss' => ['required', 'string', 'max:255'],
            'num_cin' => ['required', 'digits:12', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255' , 'unique:users'],
            'contact' => ['required', 'string', 'max:255'],
            'situation_mat' => ['required', 'string', 'max:255'],
            'nombre_enf' => ['required', 'string', 'max:255'],
            'date_embauche' => ['required', 'string', 'max:255'],
            'numero_cnaps' => ['required', 'string', 'max:255'],
            'numero_omsi' => ['required', 'string', 'max:255'],
            'banque' => ['required', 'string', 'max:255'],
            'num_compte_bancaire' => ['required', 'string', 'max:255'],
            'salaires_brut' => ['required', 'numeric'],
            'photo' => ['required', 'mimes:jpeg,png,jpg'],
            'poste_id' => ['required', 'numeric'],
            'nombre_jours_conges' => ['required', 'numeric'],
            // 'password' => ['required', 'string', 'max:255'],
        ]);

        
        if ($validators->fails()) {
            return response()->json($validators->errors(), 400);
        } else {
            //gener l'upload de l'image
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $file->storeAs('public/photos', $filename);
            $user = User::create([
                'matricule' => $request->matricule,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'date_naiss' => $request->date_naiss,
                'num_cin' => $request->num_cin,
                'email' => $request->email,
                'contact' => $request->contact,
                'situation_mat' => $request->situation_mat,
                'nombre_enf' => $request->nombre_enf,
                'date_embauche' => $request->date_embauche,
                'numero_cnaps' => $request->numero_cnaps,
                'numero_omsi' => $request->numero_omsi,
                'banque' => $request->banque,
                'num_compte_bancaire' => $request->num_compte_bancaire,
                'salaires_brut' => $request->salaires_brut,
                'photo' => $filename,
                'poste_id' => $request->poste_id,
                'nombre_jours_conges' => $request->nombre_jours_conges,
                // 'password' => Hash::make($request->password),
            ]);
            return response()->json($user, 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        //if the user don't exist
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validators = Validator::make($request->all(), [
            'matricule' => ['required', 'string', 'max:255'],
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'date_naiss' => ['required', 'date'],
            'num_cin' => ['required', 'string', 'size:12', 'regex:/^[0-9]+$/'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'situation_mat' => ['required', 'string', 'max:255'],
            'nombre_enf' => ['required', 'numeric', 'max:255'],
            'date_embauche' => ['required', 'date', 'max:255'],
            'numero_cnaps' => ['required', 'numeric', 'max:255'],
            'numero_omsi' => ['required', 'string', 'max:255'],
            'banque' => ['required', 'string', 'max:255'],
            'num_compte_bancaire' => ['required', 'string', 'max:255'],
            'salaires_brut' => ['required', 'numeric'],
            'photo' => ['string', 'max:255' , 'mimes:jpeg,png,jpg'],
            'poste_id' => ['required', 'numeric', 'exists:postes,id', 'max:255'],
        ]);

        if ($validators->fails()) {
            return response()->json($validators->errors(), 400);
        } else {
            $user->update($request->all());
            $user->save();
            return response()->json($user, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
