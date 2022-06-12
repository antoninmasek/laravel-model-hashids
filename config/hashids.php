<?php

return [
    /*
     * By default, the following columns are considered to be hash_id. If you decide to also bind
     * models to hash_id, then by default the first column specified here will be used as route
     * key name.
     */
    'hash_id_columns' => ['hash_id'],

    /*
     * Define alphabet that will be used by default. If not specified then Hashids default value
     * is used which is abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890.
     * You may also define the value as an array and each entry will be used in order
     * for above specified columns.
     */
    'alphabets' => null,

    /*
     * Define salt that will be used by default. If not specified then Hashids default value
     * is used which is just an empty string. You may also define the value as an array
     * and each entry will be used in order for above specified columns.
     */
    'salts' => null,

    /*
     * Define minimum length for generated Hashids. Please note, that this is minimum length
     * and not exact length. That means, that if you specify 5 the resulting Hashid can
     * have length of 5 characters or more. You may also define the value as an array
     * and each entry will be used in order for above specified columns.
     */
    'min_lengths' => null,

    /*
     * Define column name, that should be encoded. You may also define the value as
     * an array and each entry will be used in order for above specified columns.
     */
    'model_keys' => 'id',
];
