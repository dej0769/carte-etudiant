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
    
    // INDISPENSABLE POUR VITE :
window.selectionnerEtudiant = selectionnerEtudiant;
window.confirmerSuppression = confirmerSuppression;