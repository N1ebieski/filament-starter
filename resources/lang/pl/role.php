<?php

declare(strict_types=1);

return [
    'pages' => [
        'index' => [
            'title' => 'Role i uprawnienia',
            'description' => 'Role i uprawnienia',
            'keywords' => 'Role i uprawnienia'
        ],
        'create' => [
            'title' => 'Utwórz role'
        ],
        'edit' => [
            'title' => 'Edycja roli: :name'
        ],
        'delete' => [
            'title' => 'Usuń rolę :name'
        ],
        'delete_multi' => [
            'title' => '{1} Usuń :number zaznaczoną rolę|{2,4} Usuń :number zaznaczone role|{5,*} Usuń :number zaznaczonych ról'
        ],
        'search' => [
            'title' => 'Szukaj roli'
        ]
    ],
    'messages' => [
        'delete_multi' => [
            'success' => '{1} Pomyślnie usunięto :number rolę|{2,4} Pomyślnie usunięto :number role|{5,*} Pomyślnie usunięto :number ról',
        ],
        'delete' => [
            'success' => 'Pomyślnie usunięto rolę :name',
        ],
        'create' => [
            'success' => 'Pomyślnie dodano rolę :name',
        ],
        'edit' => [
            'success' => 'Pomyślnie edytowano rolę :name',
        ]
    ],
    'name' => [
        'label' => 'Nazwa'
    ],
    'permissions' => [
        'label' => 'Uprawnienia'
    ]
];
