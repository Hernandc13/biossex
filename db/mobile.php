<?php

$addons = [
    'theme_biossex' => [
        'handlers' => [
            'capacitacionmobile' => [
                'delegate' => 'CoreMainMenuHomeDelegate',
                'method' => 'show_capacitacion',
                'displaydata' => [
                    'title' => 'capacitacion_mobile',
                ],
                'priority' => 1,
            ],
        ],
        'lang' => [
            ['capacitacion_mobile', 'theme_biossex'],
        ],
    ],
];