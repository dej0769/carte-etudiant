<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Université Joseph Ki-Zerbo</title>
    @vite('resources/css/style.css')
</head>
<body>

<nav class="topbar">
    <div class="user-info">
        <span class="user-name">👤 Admin: <strong>{{ Auth::user()->name }}</strong></span>
        <span class="user-role">ID: {{ Auth::user()->id }}</span>
    </div>
        <div class="nav-actions">   
            <form action="{{ route('logout') }}" method="get">
                @csrf
                <button type="submit" class="logout-btn">Déconnexion ⏻</button>
               
            </form>
        </div>
    </nav>
  

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
                <a href="cartes.php" class="btn">Générer</a>
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


</body>
</html>