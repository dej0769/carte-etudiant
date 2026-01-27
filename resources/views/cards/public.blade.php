<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Étudiant</title>
    @vite('resources/css/card.css')
</head>
<body>
    <div class="container">
        <h2>Carte Étudiant</h2>
         <button class="btn-voir"  
                            style="background: #2979ff; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;" 
                            data-id="{{ $carte->student->id }}"
                            data-nom="{{ addslashes($carte->student->nom) }}"
                            data-prenom="{{ addslashes($carte->student->prenom) }}"
                            data-ine="{{ $carte->student->ine }}"
                            data-filiere="{{ addslashes($carte->student->filiere) }}"
                            data-photo="{{ $carte->student->photo }}"
                            data-expiry="{{ $carte->dateExp }}"
                            onclick="ouvrirDesignCarte(this)">
                            Voir
                        </button>
                       

                        <div id="qr-source-{{ $carte->student->id }}" style="display: none;" >
                            {!! QrCode::size(70)->margin(0)->generate($carte->student->ine) !!}
                        </div>
    </div>


   <div id="cardModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-modal" onclick="fermerModal()">&times;</span>
        <div class="id-card-view">
            <div class="side-bar">
                <div class="uni-logo">
                    <img src="{{ asset('images/UJKZ.jpg') }}" alt="Logo">
                    <div class="uni-name">UNIVERSITÉ<br>JOSEPH KI-ZERBO</div>
                </div>
                <div class="photo-box">
                    <img id="modal-photo" src="" alt="Photo">
                </div>
            </div>
            <div class="card-body">
                <div class="card-header">CARTE D'ÉTUDIANT</div>
                <div class="info-container">
                    <div class="info-row"><label>NOM & PRÉNOM</label><div id="modal-name" class="val">--</div></div>
                    <div class="info-row"><label>N° MATRICULE (INE)</label><div id="modal-ine" class="val">--</div></div>
                    <div class="info-row"><label>FILIÈRE / DÉPARTEMENT</label><div id="modal-filiere" class="val">--</div></div>
                    <div class="info-row"><label>VALIDE JUSQU'AU</label><div id="modal-expiry" class="val-expiry"></div></div>
                </div>
                <div id="modal-qr-destination" style="position: absolute; bottom: 20px; right: 20px; width: 80px; height: 80px;"></div>
            </div>
        </div>
        <div style="text-align: center; margin-top: 20px;">
            <button onclick="fermerModal()" style="background:rgba(0,0,0,0.5); color:white; border:none; padding:10px 20px; border-radius:5px; cursor:pointer;">Fermer</button>
            <button onclick="window.print()" style="background:#2979ff; color:white; border:none; padding:10px 25px; border-radius:5px; cursor:pointer; font-weight:bold;">Imprimer</button>
        </div>
    </div>
</div>

<script>
function ouvrirDesignCarte(btn) {
    const id = btn.getAttribute('data-id');
    const nom = btn.getAttribute('data-nom');
    const prenom = btn.getAttribute('data-prenom');
    const ine = btn.getAttribute('data-ine');
    const filiere = btn.getAttribute('data-filiere');
    const photo = btn.getAttribute('data-photo');
    const expiry = btn.getAttribute('data-expiry');

    document.getElementById('modal-name').innerText = nom + " " + prenom;
    document.getElementById('modal-ine').innerText = "INE: " + ine;
    document.getElementById('modal-filiere').innerText = filiere;
    document.getElementById('modal-expiry').innerText = expiry;
    document.getElementById('modal-photo').src = "/uploads/students/" + photo;

    const qrSource = document.getElementById('qr-source-' + id);
    const qrDest = document.getElementById('modal-qr-destination');
    
    if (qrSource && qrDest) {
        qrDest.innerHTML = qrSource.innerHTML;
        const svg = qrDest.querySelector('svg');
        if(svg) {
            svg.setAttribute('width', '100%');
            svg.setAttribute('height', '100%');
        }
    }
    document.getElementById('cardModal').style.display = "block";
}

function fermerModal() {
    document.getElementById('cardModal').style.display = "none";
}
</script> 
</body>
</html>