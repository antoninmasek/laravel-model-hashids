<?php

return [
    /*
     * By default, the following column is considered to be hash_id. If you decide to also bind
     * models to hash_id, then this column will be used as route key name.
     */
    'hash_id_column' => 'hash_id',

    /*
     * This alphabet will be used by default if you won't overwrite it
     * on a per model basis.
     */
    'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',

    /*
     * This salt will be used by default if you won't overwrite it
     * on a per model basis.
     */
    'salt' => '',

    /*
     * Define minimum length for generated Hashids. Please note, that this is minimum length
     * and not exact length. That means, that if you specify 5 the resulting Hashid can
     * have length of 5 characters or more.
     */
    'min_length' => 0,

    /*
     * Define column name, that should be encoded.
     */
    'model_key' => 'id',
];
