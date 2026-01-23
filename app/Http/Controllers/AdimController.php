<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;

class AdimController extends Controller
{
    public function index()
    {
        // 1. Récupérer les activités depuis la base de données
        // On prend les 5 dernières avec les infos de l'admin (user)
        $activites = Activity::with('user')->latest()->take(5)->get();

        // 2. Envoyer la variable à la vue via compact()
        return view('admin.dashboard', compact('activites'));
    }
}
