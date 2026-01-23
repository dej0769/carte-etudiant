<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdimController;
use App\Http\Controllers\StudentController;

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

// Route pour la déconnexion (POST)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// Inscription
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
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
    });