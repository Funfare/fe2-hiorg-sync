<?php
return [
    'type' => 'user',
    'id' => 'cbc67ed36c1dc7ba8e1ede45d530cfcb',
    'links' => [
        'self' => 'https://api.hiorg-server.de/core/v1/personal/cbc67ed36c1dc7ba8e1ede45d530cfcb',
    ],
    'attributes' => [
        'anrede' => 'Herr',
        'vorname' => 'Vorname',
        'name' => 'Vorname Nachname',
        'nachname' => 'Nachname',
        'username' => 'NachnameV',
        'telpriv' => '',
        'teldienst' => '',
        'handy' => '+491324342344',
        'email' => 'email@example.com',
        'adresse' => 'Musterstraße 3',
        'plz' => '97276',
        'ort' => 'Margetshöchheim',
        'land' => 'Deutschland',
        'gebdat' => '2000-05-21',
        'gebort' => '',
        'gruppen_namen' => [
            'Gruppe 1',
            'Gruppe 2',
        ],
        'orgakuerzel' => 'juhw',
        'leitung' => false,
        'status' => 'aktiv',
        'gesperrt' => false,
        'rechte' => [
            'helfer',
        ], 'qualifikationen' => [
            [
                'liste' => 'Medizinische Qualifikation',
                'liste_id' => 2537,
                'name' => 'Rettungs-Sanitäter/in',
                'name_kurz' => 'RS',
                'rang' => 5,
                'erwerb_datum' => '2018-07-28',
                'zentralliste_id' => null,
                'zentralliste_rang' => null,
            ], [
                'liste' => 'Führungsqualifikation',
                'liste_id' => 2538,
                'name' => 'Zugführer*in',
                'name_kurz' => 'ZF',
                'rang' => 4,
                'erwerb_datum' => '2022-11-06',
                'zentralliste_id' => null,
                'zentralliste_rang' => null,
            ], [
                'liste' => 'Leitungsfunktionen',
                'liste_id' => 12085,
                'name' => '1. Fachbereichsleiter*in / Fachgruppenleiter*in',
                'name_kurz' => '1. FBL',
                'rang' => 1,
                'erwerb_datum' => null,
                'zentralliste_id' => null,
                'zentralliste_rang' => null,
            ],
        ],
        'fahrerlaubnis' => [
            'klassen' => [
            ],
            'beschraenkung' => '',
            'fuehrerscheinnummer' => '',
            'fuehrerscheindatum' => '',
        ],
        'angehoerige' => '',
        'ernaehrung' => '',
        'allerg_intol' => '',
        'verpflegung_weiteres' => '',
        'kontoinhaber' => '',
        'iban' => '',
        'bic' => '',
        'kreditinstitut' => '',
        'mitgliednr' => '',
        'mitglied_seit' => '2012-03-29',
        'austritt_datum' => '',
        'wechseljgddat' => '',
        'beruf' => '',
        'arbeitgeber' => '',
        'bemerkung' => '',
        'funktion' => 'Fachgruppenleiter IuK',
        'benutzerdefinierte_felder' => [
            ['id' => 'user1', 'name' => 'Melder', 'value' => '',],
            ['id' => 'user3', 'name' => 'Foto', 'value' => '',],
            ['id' => 'user4', 'name' => 'Vegetarisch/Vegan', 'value' => '',],
            ['id' => 'user5', 'name' => 'aPager E-Mail', 'value' => 'apager@example.com',],
        ],
    ],
    'relationships' => [
        'organisation' => [
            'data' => [
                'type' => 'organisation', 'id' => '13409'
            ]
        ]
    ]
];
