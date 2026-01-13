<?php

return [
    'deal' => [
        'product_id' => [
            'required' => 'Product ID is required',
            'exists' => 'The selected product does not exist',
        ],
        'client_name' => [
            'required' => 'Client name is required',
            'max' => 'Client name must not exceed 100 characters',
        ],
        'client_phone' => [
            'required' => 'Client phone is required',
            'max' => 'Client phone must not exceed 20 characters',
        ],
        'comment' => [
            'max' => 'Comment must not exceed 500 characters',
        ],
        'status' => [
            'in' => 'The selected status is invalid. Allowed values: new, in_progress, done, canceled',
        ],
    ],
];
