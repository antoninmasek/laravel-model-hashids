<?php

return [
    /*
     * The following column will be filled with the generated hash id. If you decide to also bind
     * models to hash_id, then this column will be used as route key name.
     */
    'hash_id_column' => 'hash_id',

    /*
     * Define the column name, which will be used to generate the hash id. This column has to contain
     * a numeric value or an array of numbers and should be unique for each model to prevent
     * potential collisions.
     */
    'model_key' => 'id',

    /*
     * If true, then the model won't fire `updated` event upon setting the hash id
     */
    'save_quietly' => true,
];
