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
        'users' => [
            'index' => [
                'title' => 'Członkowie'
            ],
            'attach' => [
                'title' => 'Dołącz użytkownika'
            ],
            'detach' => [
                'title' => 'Odłącz użytkownika: :name'
            ],
            'detach_multi' => [
                'title' => '{1} Odłącz :number zaznaczonego użytkownika|{2,*} Odłącz :number zaznaczonych użytkowników'
            ],
        ]
    ],
    'messages' => [
        'delete' => [
            'success' => 'Pomyślnie usunięto projekt: :name',
            'wrong_name' => 'Nieprawidłowo potwierdzona nazwa projektu'
        ],
        'create' => 'Pomyślnie dodano projekt: :name',
        'edit' => 'Pomyślnie edytowano projekt: :name',
        'users' => [
            'attach' => [
                'success' => 'Pomyślnie dołączono użytkownika: :name'
            ],
            'detach' => [
                'success' => 'Pomyślnie odłączono użytkownika: :name'
            ],
            'detach_multi' => [
                'success' => '{1} Pomyślnie odłączono :number użytkownika|{2,*} Pomyślnie odłączono :number użytkowników',
            ]
        ]
    ],
    'current' => 'Aktualny projekt',
    'name' => [
        'label' => 'Nazwa projektu'
    ]
];
