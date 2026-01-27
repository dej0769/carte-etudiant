<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Gestion des étudiants</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/liste.css')
</head>
<body>

    <div class="search-container">
        <div class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="searchInput" onkeyup="filtrerTableau()" placeholder="Rechercher un étudiant (Nom, INE, Filière...)...">
        </div>
        <div class="search-stats">
            <span id="resultCount">@if(isset($etudiants)) {{ count($etudiants) }} @endif</span> étudiant(s) trouvé(s)
        </div>
    </div>
    <div class="main-wrapper">
    <div class="list-card">
        <div class="table-section">
            <h1 class="list-title"><i class="fas fa-users"></i> Liste des Étudiants</h1>
            @if(count($etudiants) == 0)
                <p>Aucun étudiant disponible.</p>
            @else
            <div class="table-responsive">
                <table class="modern-table">
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
                            <th>QR Code</th>
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
                            <td class="text-center">
                                {!! QrCode::size(50)->generate($etudiant->ine) !!}
                            </td>
                            <td>
                                @if($etudiant->photo)
                                    <img src="{{ asset('uploads/students/' . $etudiant->photo) }}" style="width: 40px; height: 40px; border-radius: 5px;">
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div> <div class="action-section">
        <div class="toolbar-vertical">
            <a href="{{ route('students.create') }}" class="btn-tool add"> 
                <i class="fas fa-plus"></i> Ajouter
            </a>
            
            <a href="#" id="btn-modifier" class="btn-tool edit " disabled> 
                <i class="fas fa-edit"></i> Modifier
            </a>

            <button type="button" id="btn-supprimer" onclick="confirmerSuppression()" 
                    data-url="{{ url('admin/students/supprimer') }}" 
                    class="btn-tool delete" disabled>
                <i class="fas fa-trash"></i> Supprimer
            </button>

            <a href="{{route('admin.dashboard')}}" class="btn-tool retour">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
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