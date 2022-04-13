<?php

return [
    /**
     * This value when be shown when the example command is executed
     */
    'resource_path' => 'Http\Resources',
    'command_output' => 'Output configured in config file',
    'render' => [
        'default_component' => 'text-field',
        'default_panel' => 'details'
    ],
    'search' => [
        'attribute' => 'search'
    ],
    'sort' => [
        'attribute' => 'sort',
        'direction' => [
            /**
             * Name of attribute that determines the direction, text attribute will have either 'desc' or 'asc', if attribute is boolean, use flag attribute
             */
            'attribute' => 'descending',
            /**
             * Indicates attribute is true/false and true value indicates direction as specified by attribute, set to null if sort attribute contains text 'asc' or 'desc
             */
            'flag' => 'desc'
        ]
    ],
    'pagination' => [
        'per_page_options' => [5, 15, 25, 50, 75, 100]
    ]
];
