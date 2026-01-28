<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte Étudiant</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/card.css')
</head>
<body>
    <div class="container">
        <h2>Carte Étudiant</h2>
        @php
            // On s'assure d'avoir une date d'expiration valide pour le bouton
            $dateExpiration = $carte->student->card && $carte->student->card->expiry_date 
                ? \Carbon\Carbon::parse($carte->student->card->expiry_date)->format('d/m/Y')
                : \Carbon\Carbon::parse($carte->student->created_at)->addYear()->format('d/m/Y');

            $statutReel = $carte->student->card ? $carte->student->card->status : 'active';
        @endphp

        <button class="btn-voir" style="background: blue; color: white; padding: 5px 10px; border: none; border-radius: 4px; font-size: 12px; cursor: pointer;"
                data-id="{{ $carte->student->id }}"
                data-nom="{{ addslashes($carte->student->nom) }}"
                data-prenom="{{ addslashes($carte->student->prenom) }}"
                data-ine="{{ $carte->student->ine }}"
                data-filiere="{{ addslashes($carte->student->filiere) }}"
                data-photo="{{ $carte->student->photo }}"
                data-expiry="{{ $dateExpiration }}" {{-- Correction ici : utilise la variable calculée --}}
                data-created="{{ $carte->student->card ? \Carbon\Carbon::parse($carte->student->card->created_at)->format('d/m/Y') : now()->format('d/m/Y') }}"
                data-status="{{ $statutReel }}"
                data-cardnum="{{ $carte->student->card ? $carte->student->card->numero_carte : 'MAT-' . ($carte->student->id + 1000) }}"
                data-naissance="{{ \Carbon\Carbon::parse($carte->student->date_naissance)->format('d/m/Y') }}"
                data-lieu="{{ addslashes($carte->student->lieu_naissance) }}"
                data-niveau="{{ $carte->student->niveau }}" {{-- ex: Licence 1, Master 2 --}}
                onclick="ouvrirDesignCarte(this)">
            Voir
        </button>

<div id="cardModal" class="modal" style="display:none;">
    <div class="modal-content-wrapper">
        <div class="id-card-view">
            <div class="side-bar">
                <div class="uni-logo">
                    <img src="{{ asset('images/UJKZ.jpg') }}" alt="Logo">
                    <div class="uni-name">UNIVERSITE JOSEPH<br><span>KI-ZERBO</span></div>
                </div>
                <div class="photo-box">
                    <img id="modal-photo" src="" alt="Photo">
                </div>
            </div>

            <div class="card-body">
    <div class="brand-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 10px;">
        <span class="brand-text" style="font-weight: bold; font-size: 16px;">CARTE ETUDIANT</span>
        <div id="modal-status-badge" class="status-badge" style="position: static !important;">ACTIVE</div>
    </div>

    <div class="info-section">
        <div class="info-group">
            <label>Nom & Prénoms :</label>
            <div id="modal-name" class="val-name" style="text-transform: uppercase; font-weight: bold;">--</div>
        </div>
        <div class="info-group">
            <label>Né(e) le :</label>
            <div class="val"><span id="modal-birth">--</span> à <span id="modal-place">--</span></div>
        </div>

        <div class="row-flex" style="display: flex; gap: 20px;">
            <div class="info-group">
                <label>N° INE :</label>
                <div id="modal-ine" class="val">--</div>
            </div>
            <div class="info-group">
                <label>N° Carte :</label>
                <div id="modal-cardnum" class="val-id">--</div>
            </div>
        </div>

        <div class="info-group">
            <label>Filière  :</label>
            <div id="modal-filiere" class="val">--</div>
        </div>
        <div class="info-group">
            <label>Niveau :</label>
            <div id="modal-niveau" class="val-id">--</div>
        </div>

        <div class="row-flex bottom-row" style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 10px;">
            <div class="date-group">
                <div class="date-item">
                    <label>Établie le :</label> <span id="modal-created">--</span>
                </div>
                <div class="date-item">
                    <label>Expire le :</label> <span id="modal-expiry" class="expiry-date" style="font-weight: bold;">--</span>
                </div>
            </div>
            
            <div id="modal-qr-destination" class="qr-code-container" style="width: 75px; height: 75px; background: white; border: 1px solid #ddd; padding: 2px;"></div>
        </div>
    </div>
</div>
        
    </div>
</div>
<div id="qr-source-{{ $carte->student->id }}" style="display: none;">
    {!! QrCode::size(100)->generate($carte->student->ine) !!}
</div>
<div class="modal-actions">
    <button class="btn-close" onclick="fermerModal()"><i class="fas fa-times"></i> Fermer</button>
    <button class="btn-download" onclick="telechargerCarte()"><i class="fas fa-download"></i> Télécharger</button>
        </div>

<script>
function ouvrirDesignCarte(btn) {
    const rawStatus = btn.getAttribute('data-status').toLowerCase(); // Récupère 'active', 'suspended' ou 'expired'
    
    // Remplissage des textes
    document.getElementById('modal-name').innerText = btn.getAttribute('data-nom') + " " + btn.getAttribute('data-prenom');
    document.getElementById('modal-ine').innerText = btn.getAttribute('data-ine');
    document.getElementById('modal-cardnum').innerText = btn.getAttribute('data-cardnum');
    document.getElementById('modal-filiere').innerText = btn.getAttribute('data-filiere');
    document.getElementById('modal-expiry').innerText = btn.getAttribute('data-expiry');
    document.getElementById('modal-created').innerText = btn.getAttribute('data-created');
    // Nouveaux champs
    document.getElementById('modal-birth').innerText = btn.getAttribute('data-naissance');
    document.getElementById('modal-place').innerText = btn.getAttribute('data-lieu');
    document.getElementById('modal-niveau').innerText = btn.getAttribute('data-niveau');
    document.getElementById('modal-photo').src = "/uploads/students/" + btn.getAttribute('data-photo');
    

    // Synchronisation dynamique du Statut et de la Couleur
    const badge = document.getElementById('modal-status-badge');
    
    if (rawStatus === 'suspended' || rawStatus === 'suspendue') {
        badge.innerText = "SUSPENDUE";
        badge.style.backgroundColor = "#f59e0b"; // Orange index
    } else if (rawStatus === 'expired' || rawStatus === 'expiree') {
        badge.innerText = "EXPIRÉE";
        badge.style.backgroundColor = "#ef4444"; // Rouge
    } else {
        badge.innerText = "ACTIVE";
        badge.style.backgroundColor = "#10b981"; // Vert
    }

    // Gestion du QR Code (Fix pour éviter qu'il soit coupé)
    const qrSource = document.getElementById('qr-source-' + btn.getAttribute('data-id'));
    const qrDest = document.getElementById('modal-qr-destination');
    if (qrSource && qrDest) {
        qrDest.innerHTML = qrSource.innerHTML;
        const svg = qrDest.querySelector('svg');
        if (svg) {
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