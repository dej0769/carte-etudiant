<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Lignes de langue de validation
    |--------------------------------------------------------------------------
    */

    'accepted'        => 'Le champ :attribute doit être accepté.',
    'active_url'      => "Le champ :attribute n'est pas une URL valide.",
    'after'           => 'Le champ :attribute doit être une date postérieure au :date.',
    'alpha'           => 'Le champ :attribute doit seulement contenir des lettres.',
    'alpha_dash'      => 'Le champ :attribute doit seulement contenir des lettres, des chiffres et des tirets.',
    'alpha_num'       => 'Le champ :attribute doit seulement contenir des lettres et des chiffres.',
    'array'           => 'Le champ :attribute doit être un tableau.',
    'before'          => 'Le champ :attribute doit être une date antérieure au :date.',
    'between'         => [
        'numeric' => 'La valeur de :attribute doit être comprise entre :min et :max.',
        'file'    => 'Le fichier :attribute doit avoir une taille entre :min et :max kilo-octets.',
        'string'  => 'Le texte :attribute doit avoir entre :min et :max caractères.',
        'array'   => 'Le tableau :attribute doit avoir entre :min et :max éléments.',
    ],
    'boolean'         => 'Le champ :attribute doit être vrai ou faux.',
    'confirmed'       => 'La confirmation du champ :attribute ne correspond pas.',
    'date'            => "Le champ :attribute n'est pas une date valide.",
    'date_format'     => 'Le champ :attribute ne respecte pas le format :format.',
    'different'       => 'Les champs :attribute et :other doivent être différents.',
    'digits'          => 'Le champ :attribute doit avoir :digits chiffres.',
    'digits_between'  => 'Le champ :attribute doit avoir entre :min et :max chiffres.',
    'email'           => 'Le champ :attribute doit être une adresse e-mail valide.',
    'exists'          => 'Le champ :attribute sélectionné est invalide.',
    'image'           => 'Le champ :attribute doit être une image.',
    'in'              => 'Le champ :attribute est invalide.',
    'integer'         => 'Le champ :attribute doit être un entier.',
    'max'             => [
        'numeric' => 'La valeur de :attribute ne peut être supérieure à :max.',
        'file'    => 'Le fichier :attribute ne peut être plus gros que :max kilo-octets.',
        'string'  => 'Le texte de :attribute ne peut contenir plus de :max caractères.',
        'array'   => 'Le tableau :attribute ne peut avoir plus de :max éléments.',
    ],
    'min'             => [
        'numeric' => 'La valeur de :attribute doit être au moins :min.',
        'file'    => 'Le fichier :attribute doit être au moins de :min kilo-octets.',
        'string'  => 'Le texte :attribute doit contenir au moins :min caractères.',
        'array'   => 'Le tableau :attribute doit avoir au moins :min éléments.',
    ],
    'not_in'          => "Le champ :attribute sélectionné n'est pas valide.",
    'numeric'         => 'Le champ :attribute doit être un nombre.',
    'present'         => 'Le champ :attribute doit être présent.',
    'regex'           => 'Le format du champ :attribute est invalide.',
    'required'        => 'Le champ :attribute est obligatoire.',
    'same'            => 'Les champs :attribute et :confirm doivent correspondre.',
    'size'            => [
        'numeric' => 'La valeur de :attribute doit être :size.',
        'file'    => 'La taille du fichier de :attribute doit être de :size kilo-octets.',
        'string'  => 'Le texte de :attribute doit contenir :size caractères.',
        'array'   => 'Le tableau :attribute doit contenir :size éléments.',
    ],
    'string'          => 'Le champ :attribute doit être une chaîne de caractères.',
    'timezone'        => 'Le champ :attribute doit être un fuseau horaire valide.',
    'unique'          => 'Cette valeur pour :attribute est déjà utilisée.',
    'url'             => "Le format de l'URL de :attribute est invalide.",

    /*
    |--------------------------------------------------------------------------
    | Noms des attributs personnalisés
    |--------------------------------------------------------------------------
    | Ici, on remplace "email" par "adresse e-mail" dans les messages.
    */

    'attributes' => [
        'email'    => 'adresse e-mail',
        'password' => 'mot de passe',
        'name'     => 'nom complet',
        'phone'    => 'numéro de téléphone',
        'student_id' => 'matricule',
    ],
];