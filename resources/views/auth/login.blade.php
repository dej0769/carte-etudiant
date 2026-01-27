<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Connexion - Gestion Cartes Étudiants</title>
    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>
<body>
    <div class="titre">
        <h2>Connexion Admin</h2>
    </div>
    <div class="formulaire">
        <div class="form">
            <form class="form-pro" action="{{ route('login') }}" method="post">
                 <div class="form-control">
                        
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="" require>
                        <label for="email">Email</label>
                        <i class="fas fa-envelope"></i>
                        
                    </div>

                    <div class="form-control">
                        
                        <input type="password" id="password" name="password" value="{{ old('password') }}" placeholder="" required>
                        <label for="password">Mot de passe</label>
                        <i class="fas fa-lock"></i>
                        
                    </div>

                    
                    <input class="btn" type="submit" value="Se connecter">
                        
             @csrf       
            </form>
            

            @if ($errors->any())
    <div style="background-color: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
        <ul style="list-style: none; margin: 0; padding: 0;">
            @foreach ($errors->all() as $error)
                <li><i class="fas fa-exclamation-triangle"></i> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div style="background-color: #d1fae5; color: #065f46; padding: 10px; margin-bottom: 15px; border-radius: 5px; text-align: center;">
        {{ session('success') }}
    </div>
@endif
        </div>
         <p class="sous-titre">
            Pas de compte ? <a href="{{ route('register') }}" ">S'inscrire</a>
        </p>
        
    </div>
</body>
</html>