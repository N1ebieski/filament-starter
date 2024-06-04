<?php

use App\ValueObjects\User\StatusEmail\StatusEmail;

return [
    'pages' => [
        'panel' => [
            'title' => 'Panel użytkownika',
        ],
        'index' => [
            'title' => 'Użytkownicy',
            'description' => 'Użytkownicy',
            'keywords' => 'Użytkownicy'
        ],
        'create' => [
            'title' => 'Dodaj użytkownika'
        ],
        'edit' => [
            'title' => 'Edycja użytkownika: :name'
        ],
        'delete' => [
            'title' => 'Usuń użytkownika :name'
        ],
        'delete_multi' => [
            'title' => '{1} Usuń :number zaznaczonego użytkownika|{2,*} Usuń :number zaznaczonych użytkowników'
        ],
    ],
    'messages' => [
        'delete' => [
            'success' => 'Pomyślnie usunięto użytkownika :name',
        ],
        'delete_multi' => [
            'success' => '{1} Pomyślnie usunięto :number użytkownika|{2,*} Pomyślnie usunięto :number użytkowników',
        ],
        'create' => [
            'success' => 'Pomyślnie dodano użytkownika :name',
        ],
        'edit' => [
            'success' => 'Pomyślnie edytowano użytkownika :name',
        ],
        'toggle_status_email' => [
            StatusEmail::Verified->value => [
                'success' => 'Pomyślnie zweryfikowano adres email :email użytkownika :name'
            ]
        ],
    ],
    'name' => [
        'label' => 'Nazwa'
    ],
    'email' => [
        'label' => 'Adres email'
    ],
    'password' => [
        'label' => 'Hasło'
    ],
    'password_confirmation' => [
        'label' => 'Potwierdź hasło'
    ],
    'roles' => [
        'label' => 'Typ konta'
    ],
    'email_verified_at' => [
        'label' => 'Data weryfikacji adresu email',
    ],
    'status_email' => [
        'label' => 'Status weryfikacji',
        StatusEmail::Verified->value => 'zweryfikowane',
        StatusEmail::Unverified->value => 'niezweryfikowane'
    ],
    'permissions' => [
        'label' => 'Uprawnienia'
    ]
];
