<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdimController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
// Authentification 
// Route pour afficher le formulaire (GET)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Route pour traiter la connexion (POST)
Route::post('/login', [AuthController::class, 'login']);
;



// Inscription
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Déconnexion
// Tout en haut du fichier, juste après les "use"
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
// Routes protégées par le middleware d'authentification
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Dashboard admin
    Route::get('/dashboard', [AdimController::class, 'index'])->name('admin.dashboard');// Tableau de bord admin
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');// Liste des étudiants
//crud étudiants
    Route::get('/students/ajouter', [StudentController::class, 'add'])->name('students.create');// Formulaire d'ajout d'un nouvel étudiant
    Route::post('/students/ajouter', [StudentController::class, 'store'])->name('students.store');// Sauvegarde du nouvel étudiant
    Route::get('/students/modifier/{id}', [StudentController::class, 'edit'])->name('students.edit');// Formulaire de modification d'un étudiant
    Route::put('/students/modifier/{id}', [StudentController::class, 'update'])->name('students.update');// Mise à jour des informations de l'étudiant
    Route::delete('/students/supprimer/{id}', [StudentController::class, 'destroy'])->name('students.destroy');//Suppression d'un étudiant

    Route::get('/students/carte/{id}', [StudentController::class, 'generateCard'])->name('students.carte');// Génération de la carte d'étudiant

    Route::get('/gestion-cartes', [CardController::class, 'index'])->name('carte.index');

    Route::post('/cards/activate/{student}', [CardController::class, 'activate'])->name('cards.activate');
    Route::patch('/cards/{id}/suspend', [CardController::class, 'suspend'])->name('cards.suspend');
    Route::patch('/cards/{id}/expire', [CardController::class, 'expire'])->name('cards.expire');

    Route::patch('/cards/{id}/reactivate', [App\Http\Controllers\CardController::class, 'reactivate'])->name('cards.reactivate');

    // Accessible via QR Code
    Route::get('/carte/{numero}', [CardController::class, 'showPublic'])->name('cards.public');

});
