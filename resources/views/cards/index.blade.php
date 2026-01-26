<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Cartes Étudiantes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/card.css')
</head>
<body>

<div class="container">
    <h1>Gestion des Cartes Étudiantes</h1>

    <div class="stats-grid">
        <div class="stat-card blue"><small>Total</small><h2>{{ $stats['total'] }}</h2></div>
        <div class="stat-card green"><small>Actives</small><h2>{{ $stats['active'] }}</h2></div>
        <div class="stat-card orange"><small>Suspendues</small><h2>{{ $stats['suspendue'] }}</h2></div>
        <div class="stat-card red"><small>Expirées</small><h2>{{ $stats['expiree'] }}</h2></div>
    </div>

    <div class="filter-section">
        <span>Filtrer par :</span>
        <a href="{{ route('carte.index') }}" class="filter-btn {{ !$statut ? 'active' : '' }}">Toutes</a>
        <a href="{{ route('carte.index', ['statut' => 'active']) }}" class="filter-btn {{ $statut == 'active' ? 'active' : '' }}">Actives</a>
        <a href="{{ route('carte.index', ['statut' => 'suspended']) }}" class="filter-btn {{ $statut == 'suspended' ? 'active' : '' }}">Suspendues</a>
    </div>

    <div class="cards-grid">
    @foreach($students as $student)
    <div class="student-card">
        <span class="status-badge {{ $student->card->status ?? 'no-card' }}">
            {{ $student->card->status ?? 'Pas de carte' }}
        </span>
        
        <div class="qr-area">
            {{-- Génération en direct avec les infos de l'étudiant --}}
            {!! QrCode::size(120)->generate("INE: " . $student->ine) !!}
        </div>

        <div class="student-name">{{ $student->nom }} {{ $student->prenom }}</div>
        <div class="student-info">
            {{ $student->filiere }}<br>
            <strong>INE : {{ $student->ine }}</strong>
        </div>
                @php
            // Si l'étudiant a une date de création, on ajoute 1 an
            $dateExp = \Carbon\Carbon::parse($student->created_at)->addYear()->format('d/m/Y');
        @endphp

        <div class="expiration-info">
            Expire le: <span class="expiry-date">{{ $dateExp }}</span>
        </div>

        <div class="card-footer">
            @if($student->card)
                <button class="btn" style="background: #f59e0b; color: white; padding: 5px 10px; border: none; border-radius: 4px; font-size: 12px;">Suspendre</button>
                <button class="btn" style="background: #ef4444; color: white; padding: 5px 10px; border: none; border-radius: 4px; font-size: 12px;">Expirer</button>
<div id="qr-source-{{ $student->id }}" style="display: none;">
            {!! QrCode::size(70)->margin(0)->generate($student->ine) !!}
        </div>
              <button class="btn-voir" style="background: #2979ff; color: white; padding: 8px 12px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;" 
    onclick="ouvrirDesignCarte(
        '{{ $student->id }}', 
        '{{ addslashes($student->nom) }}', 
        '{{ addslashes($student->prenom) }}', 
        '{{ $student->ine }}', 
        '{{ addslashes($student->filiere) }}', 
        '{{ $student->photo }}',
        '30/06/2026',
        '{{ $dateExp }}'
    )">
    Voir
</button>
            @else
                <form action="{{ route('cards.activate', $student->id) }}" method="POST" style="width: 100%;">
                    @csrf
                    <button type="submit" style="background: #10b981; color: white; width: 100%; padding: 8px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
                        Générer & Activer la carte
                    </button>
                </form>
            @endif
        </div>

    </div>
    @endforeach
</div>
</div>

    <div id="cardModal" class="modal">
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
                    <div class="info-row">
                        <label>NOM & PRÉNOM</label>
                        <div id="modal-name" class="val">--</div>
                    </div>
                    
                    <div class="info-row">
                        <label>N° MATRICULE (INE)</label>
                        <div id="modal-ine" class="val">--</div>
                    </div>
                    
                    <div class="info-row">
                        <label>FILIÈRE / DÉPARTEMENT</label>
                        <div id="modal-filiere" class="val">--</div>
                    </div>

                    <div class="info-row">
                        <label>VALIDE JUSQU'AU</label>
                        <div id="modal-expiry" class="val-expiry"></div>
                    </div>
                </div>

                <div id="modal-qr-destination" style="position: absolute; bottom: 20px; right: 20px; width: 80px; height: 80px;">
                    </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <button onclick="fermerModal()" style="background:rgba(255,255,255,0.2); color:white; border:1px solid white; padding:10px 20px; border-radius:5px; cursor:pointer; margin-right: 10px;">Fermer</button>
            <button onclick="window.print()" style="background:#2979ff; color:white; border:none; padding:10px 25px; border-radius:5px; cursor:pointer; font-weight:bold;">
                <i class="fas fa-print"></i> Imprimer la Carte
            </button>
        </div>
    </div>
</div>
<script>

function ouvrirDesignCarte(id, nom, prenom, ine, filiere, photo, dateExp) {
    console.log("Date reçue : ", dateExp); // Vérifie ici dans la console (F12)

    document.getElementById('modal-name').innerText = nom + " " + prenom;
    document.getElementById('modal-ine').innerText = "INE: " + ine;
    document.getElementById('modal-filiere').innerText = filiere;
    
    // On force l'affichage de la date reçue
    const expiryElement = document.getElementById('modal-expiry');
    expiryElement.innerText = dateExp;

    document.getElementById('modal-photo').src = "/uploads/students/" + photo;

    // Gestion du QR Code
    const qrSource = document.getElementById('qr-source-' + id);
    const qrDest = document.getElementById('modal-qr-destination');
    if (qrSource && qrDest) {
        qrDest.innerHTML = qrSource.innerHTML;
    }

    document.getElementById('cardModal').style.display = "block";


    // 5. Affichage du modal
    document.getElementById('cardModal').style.display = "block";
}

function fermerModal() {
    document.getElementById('cardModal').style.display = "none";
}

// Fermer le modal si on clique à l'extérieur de la carte
window.onclick = function(event) {
    const modal = document.getElementById('cardModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

</script>

</body>
</html>