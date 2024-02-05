<?php

return [
    'pages' => [
        'create' => [
            'title' => 'Dodaj projekt'
        ],
        'edit' => [
            'title' => 'Edycja projektu: :name'
        ],
        'delete' => [
            'title' => 'Usuń projekt: :name'
        ],
        'morphs' => [
            'index' => [
                'title' => 'Członkowie'
            ],
            'attach' => [
                'title' => 'Dołącz użytkownika'
            ]
        ]
    ],
    'messages' => [
        'delete' => [
            'success' => 'Pomyślnie usunięto projekt: :name',
            'wrong_name' => 'Nieprawidłowo potwierdzona nazwa projektu'
        ],
        'create' => 'Pomyślnie dodano projekt: :name',
        'edit' => 'Pomyślnie edytowano projekt: :name',
        'morphs' => [
            'attach' => [
                'success' => 'Pomyślnie dołączono użytkownika: :name'
            ]
        ]
    ],
    'current' => 'Aktualny projekt',
    'name' => [
        'label' => 'Nazwa projektu'
    ]
];
