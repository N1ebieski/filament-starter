<?php

use App\ValueObjects\User\StatusEmail;

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
        'delete' => 'Pomyślnie usunięto użytkownika :name',
        'delete_multi' => '{1} Pomyślnie usunięto :number użytkownika|{2,*} Pomyślnie usunięto :number użytkowników',
        'create' => 'Pomyślnie dodano użytkownika :name',
        'edit' => 'Pomyślnie edytowano użytkownika :name',
        'toggle_status_email' => [
            StatusEmail::VERIFIED->value => 'Pomyślnie zweryfikowano adres email :email użytkownika :name'
        ],
    ],
    'groups' => [
        'settings' => 'Ustawienia'
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
        StatusEmail::VERIFIED->value => 'zweryfikowane',
        StatusEmail::UNVERIFIED->value => 'niezweryfikowane'
    ],
];
