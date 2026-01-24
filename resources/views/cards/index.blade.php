<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Cartes Étudiantes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg: #f8fafc;
            --white: #ffffff;
            --primary: #3b82f6;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text-main: #1e293b;
            --text-sub: #64748b;
        }

        body { font-family: 'Segoe UI', system-ui, sans-serif; background-color: var(--bg); color: var(--text-main); margin: 0; padding: 2rem; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { font-size: 1.5rem; margin-bottom: 2rem; color: #0f172a; }

        /* --- STATS (Comme sur ton image) --- */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-item { background: var(--white); padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border-left: 4px solid #e2e8f0; }
        .stat-item.total { border-left-color: var(--primary); }
        .stat-item.active { border-left-color: var(--success); }
        .stat-item.suspended { border-left-color: var(--warning); }
        .stat-item.expired { border-left-color: var(--danger); }
        .stat-item small { color: var(--text-sub); text-transform: uppercase; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.05em; }
        .stat-item h2 { margin: 0.5rem 0 0; font-size: 2rem; }
        .total h2 { color: var(--text-main); }
        .active h2 { color: var(--success); }
        .suspended h2 { color: var(--warning); }
        .expired h2 { color: var(--danger); }

        /* --- FILTRES --- */
        .filter-section { background: var(--white); padding: 1rem; border-radius: 8px; margin-bottom: 2rem; display: flex; align-items: center; gap: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .filter-btn { padding: 8px 16px; border-radius: 6px; border: none; background: #f1f5f9; color: var(--text-sub); cursor: pointer; text-decoration: none; font-size: 0.9rem; transition: 0.2s; }
        .filter-btn.active { background: var(--primary); color: white; }

        /* --- GRILLE DE CARTES --- */
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
        .card { background: var(--white); border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; position: relative; text-align: center; }
        
        .card-header { display: flex; justify-content: space-between; margin-bottom: 1rem; }
        .badge { padding: 4px 10px; border-radius: 6px; font-size: 0.7rem; font-weight: bold; }
        .badge-active { background: #f0fdf4; color: var(--success); }
        .badge-suspended { background: #fffbeb; color: var(--warning); }
        .card-no { font-size: 0.75rem; color: var(--text-sub); font-family: monospace; }

        .qr-code { width: 140px; margin: 1rem auto; display: block; cursor: pointer; }
        .student-name { font-size: 1.1rem; font-weight: 700; margin: 0.5rem 0; }
        .student-info { color: var(--text-sub); font-size: 0.85rem; margin-bottom: 1.5rem; line-height: 1.4; }

        /* --- BOUTONS ACTIONS (Comme sur ton image) --- */
        .card-actions { display: flex; gap: 8px; justify-content: center; }
        .btn { padding: 6px 12px; border-radius: 6px; border: none; color: white; cursor: pointer; font-size: 0.85rem; font-weight: 600; flex: 1; }
        .btn-suspend { background: var(--warning); }
        .btn-expire { background: var(--danger); }
        .btn-view { background: var(--primary); }

        /* --- MODALE --- */
        .modal { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); align-items: center; justify-content: center; z-index: 1000; padding: 1rem; }
        .modal-content { background: white; padding: 2rem; border-radius: 20px; max-width: 400px; width: 100%; text-align: center; position: relative; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
        .close-modal { position: absolute; top: 1rem; right: 1rem; cursor: pointer; font-size: 1.5rem; color: var(--text-sub); }
    </style>
</head>
<body>

<div class="container">
    <h1>Gestion des Cartes Étudiantes</h1>

    <div class="stats-grid">
        <div class="stat-item total"><small>Total Cartes</small><h2>{{ $stats['total'] }}</h2></div>
        <div class="stat-item active"><small>Actives</small><h2>{{ $stats['active'] }}</h2></div>
        <div class="stat-item suspended"><small>Suspendues</small><h2>{{ $stats['suspendue'] }}</h2></div>
        <div class="stat-item expired"><small>Expirées</small><h2>{{ $stats['expiree'] }}</h2></div>
    </div>

    <div class="filter-section">
        <span style="font-size: 0.9rem; color: var(--text-sub)">Filtrer par statut :</span>
        <a href="{{ route('carte.index') }}" class="filter-btn {{ !request('statut') ? 'active' : '' }}">Toutes</a>
        <a href="{{ route('carte.index', ['statut' => 'active']) }}" class="filter-btn {{ request('statut') == 'active' ? 'active' : '' }}">Actives</a>
        <a href="{{ route('carte.index', ['statut' => 'suspended']) }}" class="filter-btn {{ request('statut') == 'suspended' ? 'active' : '' }}">Suspendues</a>
    </div>

    <div class="cards-grid">
    @foreach($cards as $card)
        <div class="card">
            <div class="card-header">
                <span class="badge badge-{{ $card->status }}">{{ ucfirst($card->status) }}</span>
                <span class="card-no">#{{ $card->numero_carte }}</span>
            </div>

            <div class="qr-wrapper" style="margin: 15px 0; display: flex; justify-content: center; background: #fff; padding: 10px;">
                @if($card->student)
                    @php
                        // On prépare les données exactement comme dans ta fonction generateCard
                        $data = "ID: " . $card->student->id . "\n" .
                                "INE: " . $card->student->ine . "\n" .
                                "Nom: " . $card->student->nom . " " . $card->student->prenom;
                    @endphp

                    {{-- Génération en SVG pour être sûr que ça s'affiche sans fichier --}}
                    {!! QrCode::size(130)->generate($data) !!}
                @else
                    <p style="color: red; font-size: 0.8rem;">Étudiant introuvable</p>
                @endif
            </div>

            <div class="student-name">
                {{ $card->student->nom ?? 'N/A' }} {{ $card->student->prenom ?? '' }}
            </div>
            
            <div class="student-info">
                {{ $card->student->filiere ?? 'Filière inconnue' }}<br>
                <strong>INE:</strong> {{ $card->student->ine ?? 'Non défini' }}
            </div>

            <div class="card-actions">
                <button class="btn btn-view" onclick="showCard('{{ $card->student->nom ?? '' }}', '{{ $card->student->prenom ?? '' }}', '{{ $card->student->filiere ?? '' }}', '{{ $card->student->ine ?? '' }}', '{{ $card->numero_carte }}')">
                    Voir
                </button>
            </div>
        </div>
    @endforeach
</div>
</div>

<div id="cardModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <div id="modal-body">
            </div>
        <button class="btn btn-view" style="margin-top: 1.5rem; width: 100%;" onclick="window.print()">Imprimer la carte</button>
    </div>
</div>

<script>
    function showCard(nom, prenom, filiere, ine, qr) {
        const body = document.getElementById('modal-body');
        body.innerHTML = `
            <div style="border: 2px solid #3b82f6; border-radius: 15px; padding: 20px; background: linear-gradient(to bottom, #fff, #f8fafc);">
                <h2 style="margin:0; color:#3b82f6; font-size:1.2rem;">CARTE ÉTUDIANT</h2>
                <img src="${qr}" style="width:180px; margin:20px 0;">
                <div style="font-weight:bold; font-size:1.2rem;">${nom} ${prenom}</div>
                <div style="color:#64748b; margin:10px 0;">${filiere}</div>
                <div style="font-family:monospace; background:#f1f5f9; padding:5px; border-radius:4px;">INE: ${ine}</div>
            </div>
        `;
        document.getElementById('cardModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('cardModal').style.display = 'none';
    }

    // Fermer si on clique à côté
    window.onclick = function(event) {
        if (event.target == document.getElementById('cardModal')) closeModal();
    }
</script>

</body>
</html>