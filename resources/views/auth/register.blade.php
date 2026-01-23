<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/style.css', 'resources/js/app.js'])
</head>
<body>
    <div class="titre">
        <h2>Créer un compte Admin</h2>
    </div>
    <div class="formulaire">
        <div class="form">
            <form  class="form-pro" action="{{ route('register') }}" method="post">
                @csrf
                <div class="form-control">
                    
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="" required>
                    <label for="name">Nom d'utilisateur</label>
                    <i class="fas fa-user"></i>
                </div>
                     @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                

                <div class="form-control">
                    
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="" required>
                    <label for="email">Email</label>
                    <i class="fas fa-envelope"></i>
                </div>
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <div class="form-control">
                    
                    <input type="password" id="password" name="password" value="{{ old('password') }}" placeholder="" required>
                    <label for="password">Mot de passe</label>
                    <i class="fas fa-lock"></i>
                </div>
                 @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <div class="form-control">
                    
                    <input type="password" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="" required>
                    <label for="password_confirmation">Confirmer mot de passe</label>
                    <i class="fas fa-check-double"></i>
                </div>

                
                <input class="btn " type="submit"  value="S'inscrire">
            
            </form>
        </div>
        <p class="sous-titre">
            Déjà un compte ? <a href="{{ route('login') }}" ">Se connecter</a>
        </p>

        @if (session('success'))
    <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; margin-bottom: 1rem; border-radius: 0.5rem;">
        {{ session('success') }}
    </div>
@endif

    </div>
</body>
</html>