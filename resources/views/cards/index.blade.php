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
    <div class="container"> <div class="return-container"> <a href="{{ route('admin.dashboard') }}" class="return-btn"> <i class="fa fa-arrow-left"></i> Retour </a> </div>
    <h1>Gestion des Cartes Étudiantes</h1>
    
    
    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #34d399; text-align: center; font-weight: bold;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

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
        <a href="{{ route('carte.index', ['statut' => 'expired']) }}" class="filter-btn {{ $statut == 'expired' ? 'active' : '' }}">Expirées</a>
    </div>

    <div class="cards-grid">
        @foreach($students as $student)
            <div class="student-card">
               @if($student->card)
                <span class="status-badge {{ $student->card->status }}">
                    {{ ucfirst($student->card->status) }}
                </span>
            @else
                <span class="status-badge no-card">Pas de carte</span>
            @endif
            
            <div class="qr-area">
                {{-- QR Code cliquable qui pointe vers la page publique --}}
                @if($student->card)
                    <a href="{{ route('cards.public', $student->card->numero_carte) }}">
                        {!! QrCode::size(120)->generate(route('cards.public', $student->card->numero_carte)) !!}
                    </a>
                @else
                    {!! QrCode::size(120)->generate("INE: " . $student->ine) !!}
                @endif
            </div>
                <div class="student-name">{{ $student->nom }} {{ $student->prenom }}</div>
                <div class="student-info">
                    {{ $student->filiere }}<br>
                    <strong>INE : {{ $student->ine }}</strong>
                </div>

                @php
                    // Si l'étudiant a une carte, on prend sa date d'expiration réelle.
                    // Sinon, on simule une date (par exemple +1 an après inscription).
                    $dateExpValue = $student->card && $student->card->expiry_date 
                        ? \Carbon\Carbon::parse($student->card->expiry_date)
                        : \Carbon\Carbon::parse($student->created_at)->addYear();

                    $dateExpFormatted = $dateExpValue->format('d/m/Y');
                    
                    // Déterminer si la carte est bientôt expirée (pour mettre en rouge)
                    $isNearlyExpired = $dateExpValue->isPast() || $dateExpValue->diffInDays(now()) < 30;
                @endphp

                <div class="expiration-info">
                    Expire le : <span class="expiry-date" style="{{ $isNearlyExpired ? 'color: #ef4444; font-weight: bold;' : '' }}">
                        {{ $dateExpFormatted }}
                    </span>
                </div>

                <div class="card-footer" style="display: flex; gap: 5px; flex-wrap: wrap; align-items: center;">
                    @if($student->card)
                        @if($student->card->status !== 'active')
                            <form action="{{ route('cards.reactivate', $student->card->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Réactiver cette carte ?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn" style="background: #10b981; color: white; padding: 5px 10px; border: none; border-radius: 4px; font-size: 12px; cursor: pointer;">Réactiver</button>
                            </form>
                        @endif

                        @if($student->card->status === 'active')
                            <form action="{{ route('cards.suspend', $student->card->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Suspendre cette carte ?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn" style="background: #f59e0b; color: white; padding: 5px 10px; border: none; border-radius: 4px; font-size: 12px; cursor: pointer;">Suspendre</button>
                            </form>
                        @endif

                        <form action="{{ route('cards.expire', $student->card->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Marquer comme expirée ?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn" style="background: #ef4444; color: white; padding: 5px 10px; border: none; border-radius: 4px; font-size: 12px; cursor: pointer;">Expirer</button>
                        </form>
                        <a href="{{ route('cards.public', $student->card->numero_carte) }}" class="btn-voir" style="background: #2979ff; color: white; padding: 5px 10px; border: none; border-radius: 4px; font-size: 12px; cursor: pointer;">Voir</a>
                        
                    @else
                        <form action="{{ route('cards.activate', $student->id) }}" method="POST" style="width: 100%;">
                            @csrf
                            <button type="submit" style="background: #10b981; color: white; width: 100%; padding: 8px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
                                Générer & Activer
                            </button>
                        </form>
                    @endif
                </div> {{-- card-footer --}}
            </div> {{-- student-card --}}
        @endforeach
    </div> {{-- cards-grid --}}
</div> {{-- container --}}


</body>
</html>