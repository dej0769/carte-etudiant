<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

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

        // Filtrer par statut si demandé
        if ($statut) {
            $query->whereHas('card', function ($q) use ($statut) {
                $q->where('status', $statut);
            });
        }
        
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

public function suspend($id)
{
    $card = Card::findOrFail($id);
    $card->update(['status' => 'suspended']); // ou 'suspendue' selon ton énumération

    return back()->with('success', 'La carte a été suspendue.');
}

public function expire($id)
{
    $card = Card::findOrFail($id);
    $card->update([
        'status' => 'expired',
        'expiry_date' => now() // On force la date à aujourd'hui
    ]);

    return back()->with('success', 'La carte est désormais marquée comme expirée.');
}

public function reactivate($id)
{
    // On récupère la carte par son ID
    $card = Card::findOrFail($id);
    
    // Mise à jour du statut et prolongation de la validité
    $card->update([
        'status' => 'active',
        'expiry_date' => Carbon::now()->addYear() // Ajoute exactement 1 an à partir d'aujourd'hui
    ]);

    // Retourne à la page précédente avec un message de confirmation
    return back()->with('success', 'La carte de ' . $card->student->nom . ' a été réactivée avec succès jusqu\'au ' . $card->expiry_date->format('d/m/Y') . '.');
}

//Afficher la carte publique via QR Code
     
    public function showPublic($numeroCarte)
    {
        $carte = Card::where('numero_carte', $numeroCarte)->with('student')->firstOrFail();

        return view('cards.public', compact('carte'));
    }
}
