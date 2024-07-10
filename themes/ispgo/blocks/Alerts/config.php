<?php
return [
    'label' => 'Separador',
    'category' => 'General',
    'icon' => 'fa fa-hand-peace-o',
    'settings' => [
        'color' => [
            'label'=> 'Height',
            'type' => 'select',
            'options' => [
                ['id' => 'primary', 'name' => 'Primary'],
                ['id' => 'secondary', 'name' => 'Secondary'],
                ['id' => 'danger', 'name' => 'Danger'],
            ]
        ]
    ]
];
