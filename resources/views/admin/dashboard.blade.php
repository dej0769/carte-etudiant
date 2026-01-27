<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Université Joseph Ki-Zerbo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite('resources/css/style.css')
</head>
<body>

<nav class="topbar">
    <div class="user-info">
        <span class="user-name">👤 Admin: <strong>{{ Auth::user()->name }}</strong></span>
    </div>
    
    <div class="nav-actions">
        <a href="{{ route('logout') }}" 
           class="logout-link"
          onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) { document.getElementById('final-form').submit(); }">
        <i class="fas fa-right-from-bracket"></i> 
        <span>Déconnexion</span>
        </a>
    </div>
</nav>

<form id="final-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<style>
.logout-link {
    background-color: #ef4444;
    color: white !important;
    padding: 8px 16px;
    border-radius: 4px;
    font-weight: bold;
    text-decoration: none !important; /* ENLÈVE LE SOULIGNEMENT */
    display: inline-block;
    transition: 0.3s;
}
.logout-link:hover {
    background-color: #dc2626;
    text-decoration: none !important;
}
</style>
  

    <div class="container">
        <header>
             <button type="submit" class="btn primary">Historique Admin</button>
            <h1>TABLEAU DE BORD <span>PROSPECTIF</span></h1>
            <p class="subtitle">Ce tableau de bord aide l'administration à gérer les cartes d'étudiants numériques et à planifier les mises à jour du système.</p>
        </header>

        <div class="dashboard-grid">
            
            <div class="card lime">
                <div class="icon-circle">
                    <img src="https://img.icons8.com/ios-filled/50/000000/group.png" alt="Etudiants">
                </div>
                <h2>GESTION DES ÉTUDIANTS</h2>
                <p>Créer, modifier et supprimer les dossiers étudiants (INE, Filière, Photo).</p>
                <a href="{{ route('students.index') }}" class="btn">Gérer</a>
            </div>

            <div class="card teal">
                <div class="icon-circle">
                    <img src="https://img.icons8.com/ios-filled/50/000000/qr-code.png" alt="Cartes">
                </div>
                <h2>CARTES NUMÉRIQUES</h2>
                <p>Génération automatique des QR Codes et numéros de cartes uniques.</p>
                <a href="{{ route('carte.index') }}" class="btn">Gérer</a>
            </div>

            <div class="card teal">
                <div class="icon-circle">
                    <img src="https://img.icons8.com/ios-filled/50/000000/security-shield.png" alt="Statuts">
                </div>
                <h2>STATUTS & ACCÈS</h2>
                <p>Activer, suspendre ou expirer une carte. Sécurisation des accès admin.</p>
                <a href="acces.php" class="btn">Configurer</a>
            </div>

            <div class="card lime">
                <div class="icon-circle">
                    <img src="https://img.icons8.com/ios-filled/50/000000/visible.png" alt="Aperçu">
                </div>
                <h2>PAGE PUBLIQUE</h2>
                <p>Visualiser ce que voient les contrôleurs lors du scan du QR Code.</p>
                <a href="aperçu.php" class="btn">Voir</a>
            </div>

        </div>
      
</div>
<script>
function forceLogout() {
    const form = document.getElementById('real-logout-form');
    if (form) {
        console.log('Tentative de déconnexion POST...');
        form.submit();
    } else {
        alert('Erreur: Formulaire de déconnexion introuvable.');
    }
}
</script>

</body>
</html>