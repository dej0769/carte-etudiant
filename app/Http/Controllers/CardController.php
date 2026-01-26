<?php

namespace App\Http\Controllers;

use App\Models\Student; // Ajoute l'import de Student
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        $statut = request('statut');
        
        // On récupère les ÉTUDIANTS (ceux que tu as déjà créés)
        // et on charge leur carte s'ils en ont une
        $query = Student::with('card');

        // On récupère tout
        $students = $query->get();

        // Statistiques (basées sur les étudiants pour l'instant)
        $stats = [
            'total'     => Student::count(),
            'active'    => Card::where('status', 'active')->count(),
            'suspendue' => Card::where('status', 'suspended')->count(),
            'expiree'   => Card::where('status', 'expired')->count(),
        ];

        return view('cards.index', compact('students', 'stats', 'statut'));
    }

    public function activate($studentId)
{
    \App\Models\Card::updateOrCreate(
        ['student_id' => $studentId],
        [
            'numero_carte' => 'CARD-' . strtoupper(substr(uniqid(), 7)),
            'status' => 'active',
            'date_expiration' => '2027-01-23' // Date fixe comme sur ton modèle
        ]
    );

    return back()->with('success', 'Carte activée !');
}
}