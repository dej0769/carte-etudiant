<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Afficher le formulaire de connexion
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Gérer la tentative de connexion
    public function login(Request $request)
    {
        // 1. Validation des données
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
        // Vos messages personnalisés ici
        'email.required' => 'L\'adresse email est obligatoire.',
        'email.email' => 'Veuillez entrer une adresse email valide.',
        'password.required' => 'Le mot de passe est obligatoire.',
    ]);
        // 2. Tentative d'authentification
        // La méthode Auth::attempt vérifie le mot de passe hashé automatiquement
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Sécurité contre la fixation de session

            return redirect()->intended('admin/dashboard'); // Redirige vers la page demandée ou le dashboard
        }
        // Message d'erreur si les identifiants sont faux
    return back()->withErrors([
        'email' => 'Désolé, votre email ou mot de passe est incorrect.',
    ])->onlyInput('email');

        
    
    // 3. Échec de connexion
        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('email');
    }

    // Gérer la déconnexion
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Afficher le formulaire d'inscription
public function showRegistrationForm()
{
    return view('auth.register');
}

// Gérer la création du compte
public function register(Request $request)
{
    // 1. Validation
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' cherche un champ password_confirmation
    ]);

    // 2. Création de l'utilisateur


    // Création directe sans validation pour tester
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // 3. Redirection vers la page de connexion avec un message de succès
    return redirect()->route('login')->with('success', 'Votre compte a été créé ! Vous pouvez maintenant vous connecter.');
}
}
