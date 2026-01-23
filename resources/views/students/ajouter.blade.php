<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Petite astuce pour que ça ressemble au style Bootstrap */
        .select2-container .select2-selection--single {
            height: 38px !important;
            border: 1px solid #ced4da !important;
        }
    </style>
    <title>Ajouter un étudiant</title>
    @Vite('resources/css/style.css')
</head>
<body>
    <div class="main-wrapper">
        <div class="formulaire-side">
            <div class="illustration-side">
                <img src="{{ asset('images/login-img.png.jpg') }}" alt="UJKZ Student">
                <div class="illustration-text">
                    <h2>Université Joseph Ki-Zerbo</h2>
                    <p>Gestion des cartes étudiant</p>
                </div>
            </div>

            <div class="form-container">
                <div class="titre">
                    <h1>Nouvel étudiant</h1> 
                </div>
    
    <div class="formulaire">
        <div class="form-container">
            <form action="{{ route('students.store') }}" method="post" class="form-content" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="ine">INE</label>
                    <input type="text" name="ine" /><br/>
                </div>
                <div class="row-custom">
                    <div class="form-group" style="flex:1;">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" />
                        <br/>
                    </div>
                    <div class="form-group" style="flex:1; ">
                        <label for=" prenom">Prénom</label>
                        <input type="text" name="prenom" />
                        <br/>
                    </div>
                </div>
                <div class="row-custom">
                    <div class="form-group " style="flex:1; ">
                        <label for="date_naissance">Date de naissance</label>
                        <input type="date" 
                            name="date_naissance" 
                            id="date_naissance" 
                            class="form-control @error('date_naissance') is-invalid @enderror" 
                            value="{{ old('date_naissance') }}"
                            max="{{ date('Y-m-d', strtotime('-10 years')) }}"> 
                        {{-- Le "max" empêche de choisir une date trop récente (moins de 10 ans) --}}
                        
                        @error('date_naissance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group " style="flex:1; ">
                        <label for="lieu_naissance">Lieu de naissance</label>
                        <input type="text" name="lieu_naissance" id="lieu_naissance" class="form-control @error('lieu_naissance') is-invalid @enderror" value="{{ old('lieu_naissance') }}">
                        @error('lieu_naissance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group ">
                    <label for="filiere">Filière (UJKZ)</label>
                    <select name="filiere" id="filiere" class="form-control @error('filiere') is-invalid @enderror">
                        <option value="">-- Sélectionnez la filière --</option>

                        <optgroup label="UFR/SDS (Sciences de la Santé)">
                            <option value="Médecine" {{ old('filiere') == 'Médecine' ? 'selected' : '' }}> UFR/SDS-Médecine</option>
                            <option value="Pharmacie" {{ old('filiere') == 'Pharmacie' ? 'selected' : '' }}> UFR/SDS-Pharmacie</option>
                            <option value="Odontostomatologie" {{ old('filiere') == 'Odontostomatologie' ? 'selected' : '' }}> UFR/SDS-Odontostomatologie</option>
                        </optgroup>

                        <optgroup label="UFR/SEA (Sciences Exactes et Appliquées)">
                            <option value="Mathématiques" {{ old('filiere') == 'Mathématiques' ? 'selected' : '' }}> UFR/SEA-Mathématiques</option>
                            <option value="Physique" {{ old('filiere') == 'Physique' ? 'selected' : '' }}> UFR/SEA-Physique</option>
                            <option value="Chimie" {{ old('filiere') == 'Chimie' ? 'selected' : '' }}> UFR/SEA-Chimie</option>
                            <option value="Informatique" {{ old('filiere') == 'Informatique' ? 'selected' : '' }}> UFR/SEA-Informatique</option>
                        </optgroup>

                        <optgroup label="UFR/SEG (Sciences Économiques et de Gestion)">
                            <option value="Économie" {{ old('filiere') == 'Économie' ? 'selected' : '' }}> UFR/SEG-Économie</option>
                            <option value="Gestion" {{ old('filiere') == 'Gestion' ? 'selected' : '' }}>UFR/SEG-Gestion</option>
                        </optgroup>

                        <optgroup label="UFR/SVT (Sciences de la Vie et de la Terre)">
                            <option value="Biologie" {{ old('filiere') == 'Biologie' ? 'selected' : '' }}> UFR/SVT-Biologie</option>
                            <option value="Géologie" {{ old('filiere') == 'Géologie' ? 'selected' : '' }}>UFR/SVT-Géologie</option>
                        </optgroup>

                        <optgroup label="UFR/LAC (Lettres, Arts et Communication)">
                            <option value="Lettres Modernes" {{ old('filiere') == 'Lettres Modernes' ? 'selected' : '' }}> UFR/LAC-Lettres Modernes</option>
                            <option value="Anglais" {{ old('filiere') == 'Anglais' ? 'selected' : '' }}> UFR/LAC-Anglais</option>
                            <option value="Communication" {{ old('filiere') == 'Communication' ? 'selected' : '' }}>UFR/LAC-Communication</option>
                            <option value="Arts" {{ old('filiere') == 'Arts' ? 'selected' : '' }}>UFR/LAC-Arts</option>
                        </optgroup>

                        <optgroup label="UFR/SH (Sciences Humaines)">
                            <option value="Histoire" {{ old('filiere') == 'Histoire' ? 'selected' : '' }}> UFR/SH-Histoire</option>
                            <option value="Géographie" {{ old('filiere') == 'Géographie' ? 'selected' : '' }}>UFR/SH-Géographie</option>
                            <option value="Philosophie" {{ old('filiere') == 'Philosophie' ? 'selected' : '' }}>UFR/SH-Philosophie</option>
                            <option value="Sociologie" {{ old('filiere') == 'Sociologie' ? 'selected' : '' }}>UFR/SH-Sociologie</option>
                        </optgroup>

                        <optgroup label="IBAM (Institut Burkinabè des Arts et Métiers)">
                            <option value="Comptabilité" {{ old('filiere') == 'Comptabilité' ? 'selected' : '' }}> IBAM-Comptabilité</option>
                            <option value="Assistanat de Direction" {{ old('filiere') == 'Assistanat de Direction' ? 'selected' : '' }}>IBAM-Assistanat de Direction</option>
                            <option value="Marketing" {{ old('filiere') == 'Marketing' ? 'selected' : '' }}>IBAM-Marketing</option>
                        </optgroup>
                    </select>

                    @error('filiere')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row-custom">
                    <div class="form-group" style="flex:1; ">
                        <label for="niveau">Niveau</label>
                        <select name="niveau" id="niveau" class="form-control">
                            <option value="Licence 1">Licence 1</option>
                            <option value="Licence 2">Licence 2</option>
                            <option value="Licence 3">Licence 3</option>
                            <option value="Master 1">Master 1</option>
                            <option value="Master 2">Master 2</option>
                            
                        </select>
                    </div>
                    <div class="form-group" style="flex:1; ">
                        <label for="annee_academique">Année Académique</label>
                        <select name="annee_academique" id="annee_academique" class="form-control @error('annee_academique') is-invalid @enderror">
                            <option value="">-- Sélectionner l'année --</option>
                            @php
                                $currentYear = date('Y');
                                $startFrom = $currentYear - 5; // On commence 5 ans en arrière
                            @endphp

                            @for ($i = 0; $i <= 10; $i++) {{-- 10 itérations pour couvrir passé et futur --}}
                                @php
                                    $year = $startFrom + $i;
                                    $nextYear = $year + 1;
                                    $label = "$year-$nextYear";
                                @endphp
                                <option value="{{ $label }}" {{ old('annee_academique') == $label ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="photo">Photo d'identité</label>
                    <input type="file" name="photo" />
                    <br/>
                </div>
                <button type="submit" class="button-submit">Enregistrer l'étudiant</button>
            </div>
        </div>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // On initialise Select2 sur ton id "filiere"
            $('#filiere').select2({
                placeholder: "Rechercher une filière de l'UJKZ...",
                allowClear: true
            });
        });
    </script>
    @if ($errors->any())
    <div style="background: #ffcccc; color: red; padding: 15px; border-radius: 10px; margin: 20px;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
</body>
</html>
