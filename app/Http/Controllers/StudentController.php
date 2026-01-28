<?php

namespace App\Http\Controllers;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Activity; // Importe le modèle Activity
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // Affiche la liste des étudiants
    public function index()
    {
        $students = Student::all();// Récupère tous les étudiants de la table student
        return view('students.list_students', ['etudiants' => $students]);//retourne la vue list_students.blade.php 
    }

    // Gère l'ajout d'un nouvel étudiant
    //formulaire d'ajout d'un étudiant
    public function add(){

        return view('students.ajouter');
    }
    //enregistrer un nouvel étudiant

    public function store(Request $request)
    {
        $request->validate([
        'ine' => 'required|unique:students',
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'date_naissance' => 'required|date', // Laravel comprendra le format du calendrier
        'lieu_naissance' => 'required|string|max:255',
        'filiere' => 'required',
        'annee_academique' => 'required',
        'photo' => 'nullable|file|mimes:jpeg,png,jpg|max:2048'
    ]);
    $data = $request->all();

    // 2. Gestion de la photo (Assure-toi d'avoir l'enctype dans le HTML)
    if ($request->hasFile('photo')) {
        $imageName = time().'.'.$request->photo->extension();
        $request->photo->move(public_path('uploads/students'), $imageName);
        $data['photo'] = $imageName;
    }

    // --- NOUVELLES LOGIQUES AUTOMATIQUES ---

    // 2. Génération du Numéro de Carte (Ex: UJKZ-2026-0001)
    $anneeActuelle = date('Y');
    $dernierEtudiant = Student::whereYear('created_at', $anneeActuelle)->latest()->first();
    $sequence = $dernierEtudiant ? ($dernierEtudiant->id + 1) : 1;
    $data['card_number'] = "UJKZ-" . $anneeActuelle . "-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);

    // 3. Calcul de la date d'expiration (31 Décembre de l'année en cours)
    $data['expired_at'] = Carbon::create($anneeActuelle, 12, 31)->format('Y-m-d');

    // 4. Statut par défaut
    $data['status'] = 'active';

    // ---------------------------------------

    // 3. Utilisation d'un bloc Try/Catch pour attraper les erreurs
    try {
        // On crée l'étudiant et on stocke le résultat dans $student
        $student = Student::create($data);

        // 4. LOG DE L'ACTION (Seulement si $student a été créé avec succès)
        if ($student) {
            Activity::create([
                'user_id' => auth()->id(), // On utilise l'ID de l'utilisateur connecté
                'action'  => 'Création Étudiant',
                'details' => "Ajout de l'étudiant {$student->nom} {$student->prenom} (INE: {$student->ine})"
            ]);
        }

        return redirect()->route('students.index')->with('success', 'Étudiant enregistré !');

    } catch (\Exception $e) {
        // Si la base de données refuse (ex: colonne manquante), on voit l'erreur ici
        return back()->withInput()->withErrors(['error' => "Erreur base de données : " . $e->getMessage()]);
    }
    }


    
    // Formulaire de modification d'un étudiant
    public function edit(Request $request){
        $id = $request->id;
        $student = Student::findOrFail($id);
        return view('students.edit', ['student' => $student]);
    }
    // Mise à jour des informations de l'étudiant
    public function update(Request $request, $id)
    {
    // 1. Trouver l'étudiant ou renvoyer une erreur 404
    $student = Student::findOrFail($id);

    // 2. Validation (Note : pour l'INE, on ignore l'ID actuel pour éviter l'erreur "déjà pris")
    $request->validate([
        'ine' => 'required|unique:students,ine,' . $student->id,
        'nom' => 'required|string|max:255',
        'prenom' => 'required|string|max:255',
        'date_naissance' => 'required|date',
        'lieu_naissance' => 'required|string|max:255',
        'filiere' => 'required',
        'niveau' => 'required',
        'annee_academique' => 'required',

        'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
        'status' => 'nullable|string|in:active,suspended,expired'
    ]);

    $data = $request->all();

    // 3. Gestion de la photo (si une nouvelle photo est envoyée)
    if ($request->hasFile('photo')) {
        // Supprimer l'ancienne photo du dossier si elle existe
        if ($student->photo && file_exists(public_path('uploads/students/' . $student->photo))) {
            unlink(public_path('uploads/students/' . $student->photo));
        }

        // Enregistrer la nouvelle photo
        $imageName = time() . '.' . $request->photo->extension();
        $request->photo->move(public_path('uploads/students'), $imageName);
        $data['photo'] = $imageName;
    } else {
        // Si pas de nouvelle photo, on garde l'ancienne
        $data['photo'] = $student->photo;
    }

    // 4. Mise à jour en base de données
    $student->update($data);

    // 5. LOG DE L'ACTION DANS L'HISTORIQUE
    Activity::create([
        'user_id' => Auth::id(),
        'action'  => 'Modification Étudiant',
        'details' => "Mise à jour de l'étudiant {$student->nom} (INE: {$student->ine})"
    ]);

    return redirect()->route('students.index')->with('success', 'Informations mises à jour avec succès !');
}

public function destroy($id)
{
    // 1. Trouver l'étudiant
    $student = Student::findOrFail($id);

    // 2. Supprimer la photo physiquement sur le serveur
    if ($student->photo && file_exists(public_path('uploads/students/' . $student->photo))) {
        unlink(public_path('uploads/students/' . $student->photo));
    }

    // 3. Sauvegarder les infos pour le log avant la suppression
    $nomComplet = $student->nom . ' ' . $student->prenom;
    $ine = $student->ine;

    // 4. Supprimer l'enregistrement en base de données
    $student->delete();

    // 5. LOG DE L'ACTION DANS L'HISTORIQUE
    Activity::create([
        'user_id' => Auth::id(),
        'action'  => 'Suppression Étudiant',
        'details' => "Suppression définitive de l'étudiant $nomComplet (INE: $ine)"
    ]);

    return redirect()->route('students.index')->with('success', 'Étudiant supprimé avec succès.');
}
//
public function generateCard($id)
{
    $student = Student::findOrFail($id);

    // Au lieu de texte simple, on met l'URL de profil de l'étudiant
    // Cela permet à un contrôleur de scanner la carte et de vérifier si elle est vraie
    $urlVerification = route('students.show', $student->id); 
    
    $qrcode = QrCode::size(120)->generate($urlVerification);

    return view('admin.students.carte', compact('student', 'qrcode'));
}

public function cardManagement()
    {
        $statut = request('statut');

        $query = Student::query();
        if ($statut) {
            $query->where('status', $statut);
        }
        $students = $query->get();

        $stats = [
            'total'     => Student::count(),
            'active'    => Student::where('status', 'active')->count(),
            'suspendue' => Student::where('status', 'suspended')->count(),
            'expiree'   => Student::where('status', 'expired')->count(),
        ];

        // On passe 'cards' à la vue car c'est ce que ton @foreach attend
        return view('cards.index', [
            'cards'  => $students, 
            'stats'  => $stats,
            'statut' => $statut
        ]);
    }
}


