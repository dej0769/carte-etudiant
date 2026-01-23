<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Gestion des étudiants</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/style.css')
    <style>
    /* La couleur de la ligne quand on clique dessus */
    .selected-row {
        background-color: #A2D2FF !important; /* Bleu ciel */
        color: #003366 !important; /* Texte bleu foncé */
        transition: 0.2s; /* Petit effet de transition fluide */
    }

    /* Optionnel : change la couleur au survol de la souris */
    tr:hover {
        background-color: #f5f5f5;
        cursor: pointer;
    }

    /* Style pour les boutons désactivés au début */
    .btn-tool.disabled {
        opacity: 0.5;
        pointer-events: none; /* Empêche de cliquer tant qu'on n'a pas sélectionné quelqu'un */
        background-color: #ccc !important;
    }
</style>
</head>
<body>
    <div class="container-main">
        <div class="header-table" >
            <h1><i class="fas fa-users"></i> Liste des Étudiants</h1>
        </div>
        @if(count($etudiants) == 0)
            <p>Aucun étudiant disponible.</p>
        @else
        <div class="tableau">
            <table border="1" class="table">
                <thead>
                    <tr>
                        <th>Sélection</th>
                        <th>INE</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de Naissance</th>
                        <th>Lieu de Naissance</th>
                        <th>Filière</th>
                        <th>Niveau</th>
                        <th>Année Académique</th>
                        <th>Photo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($etudiants as $etudiant)
                    <tr onclick="selectionnerEtudiant('{{ $etudiant->id }}', this)">
                        <td style="text-align: center;">
                        <input type="radio" name="etudiant_radio" id="radio-{{ $etudiant->id }}">
                        </td>
                        <td>{{ $etudiant->ine }}</td>
                        <td>{{ $etudiant->nom }}</td>
                        <td>{{ $etudiant->prenom }}</td>
                        <td>{{ $etudiant->date_naissance }}</td>
                        <td>{{ $etudiant->lieu_naissance }}</td>
                        <td>{{ $etudiant->filiere }}</td>
                        <td>{{ $etudiant->niveau }}</td>
                        <td>{{ $etudiant->annee_academique }}</td>
                        <td>
                            @if($etudiant->photo)
                                <img src="{{ asset('uploads/students/' . $etudiant->photo) }}" 
                                    alt="Photo de {{ $etudiant->nom }}" 
                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                            @else
                                <span>Pas de photo</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    <div class="toolbar">
                        <a href="{{ route('students.create') }}" class="btn-tool add"> <i class="fas fa-plus"></i>Ajouter</a>
                        
                        <a href="#" id="btn-modifier" class="btn-tool disabled"> <i class="fas fa-edit"></i>Modifier</a>
                        <button type="button" 
                                id="btn-supprimer" 
                                onclick="confirmerSuppression()" 
                                data-url="{{ url('admin/students/supprimer') }}" 
                                class="btn-tool disabled" 
                                disabled>
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                    
                        
                </div>
                </tbody>

            </table>
        
            
            @endif
        </div>
    </div>
    <script>
    
        let currentId = null; // Variable globale indispensable

    function selectionnerEtudiant(id, element) {
        currentId = id; // Stockage de l'ID pour la suppression et la modif
        
        // 1. Visuel : on marque la ligne en bleu
        document.querySelectorAll('tr').forEach(tr => tr.classList.remove('ligne-selectionnee'));
        element.classList.add('ligne-selectionnee');

        // 2. Activer le bouton MODIFIER avec la bonne URL
        const btnModif = document.getElementById('btn-modifier');
        if(btnModif) {
            // AJOUT du préfixe /admin/ ici aussi
            btnModif.href = "/admin/students/modifier/" + id;
            
            // On retire la classe qui bloque le clic
            btnModif.classList.remove('disabled');
            btnModif.style.opacity = "1";
            btnModif.style.pointerEvents = "auto";
        }

        // 3. Activer le bouton SUPPRIMER
        const btnSuppr = document.getElementById('btn-supprimer');
        if(btnSuppr) {
            btnSuppr.classList.remove('disabled');
            btnSuppr.disabled = false;
            btnSuppr.style.opacity = "1";
            btnSuppr.style.pointerEvents = "auto";
        }
    }

        function confirmerSuppression() {
            const btn = document.getElementById('btn-supprimer');
            const baseUrl = btn.getAttribute('data-url'); // Récupère l'URL propre de Laravel

            if (!currentId) {
                Swal.fire('Erreur', 'Veuillez sélectionner un étudiant', 'error');
                return;
            }

            Swal.fire({
                title: 'Confirmation',
                text: "Voulez-vous vraiment supprimer cet étudiant ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer !'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.method = 'POST';
                    
                    // Construction dynamique sécurisée
                    form.action = "/admin/students/supprimer/" + currentId;

                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                    `;

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    
    </script>

</body>
</html>