<?php

namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\Card;
use SimpleSoftwareIO\QrCode\Facades\QrCode; 

use Illuminate\Http\Request;

class CardController extends Controller
{
//     public function showPublic(Student $student) 
// {
//     // On retourne une vue simple qui affiche les infos de l'étudiant
//     // C'est cette page qui sera liée au QR Code
//     return view('cards.public', compact('student'));
// }
// // Gestion des cartes d'étudiants
// public function cardManagement()
// {
//     $statut = request('statut');

//     // On filtre les étudiants si un statut est présent dans l'URL
//     $query = Student::query();
//     if ($statut) {
//         $query->where('status', $statut);
//     }
//     $students = $query->get();

//     $stats = [
//         'total'     => Student::count(),
//         'active'    => Student::where('status', 'active')->count(),
//         'suspendue' => Student::where('status', 'suspended')->count(),
//         'expiree'   => Student::where('status', 'expired')->count(),
//     ];

//     return view('cards.index', [
//         'cards'  => $students, // On passe $students à la vue sous le nom $cards
//         'stats'  => $stats,
//         'statut' => $statut
//     ]);
// }

public function index()
    {
        $statut = request('statut');
        $query = Card::with('student'); // On charge l'étudiant lié

        if ($statut) {
            $query->where('status', $statut);
        }

        $cards = $query->get();

        $stats = [
            'total'     => Card::count(),
            'active'    => Card::where('status', 'active')->count(),
            'suspendue' => Card::where('status', 'suspended')->count(),
            'expiree'   => Card::where('status', 'expired')->count(),
        ];

        return view('cards.index', compact('cards', 'stats', 'statut'));
    }

    // Fonction pour suspendre la carte
    public function suspend(Card $card)
    {
        $card->update(['status' => 'suspended']);
        return back()->with('success', 'Carte suspendue avec succès.');
    }
}
