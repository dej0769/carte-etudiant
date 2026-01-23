/*script des boutons d'action verticale*/
var selectedId = null;
function selectionnerEtudiant(id, element) {
    console.log("Étudiant sélectionné ID : " + id); // Regarde dans la console (F12)

    // 1. Retirer la classe de toutes les lignes
    var toutesLesLignes = document.querySelectorAll('tr');
    toutesLesLignes.forEach(function(ligne) {
        ligne.classList.remove('ligne-selectionnee');
    });

    // 2. Ajouter la classe à la ligne cliquée
    element.classList.add('ligne-selectionnee');

    // 3. Activer le bouton modifier
    var btnModif = document.getElementById('btn-modifier');
    if(btnModif) {
        btnModif.style.opacity = "1";
        btnModif.style.pointerEvents = "auto";
        // On construit l'URL vers ton formulaire de modification
        btnModif.href = "/admin/students/modifier/" + id;
    }
}


function submitDelete() {
    if(selectedId && confirm('Voulez-vous vraiment supprimer cet étudiant ?')) {
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = "/admin/students/" + selectedId;
        form.innerHTML = `@csrf @method('DELETE')`;
        document.body.appendChild(form);
        form.submit();
    }
}
