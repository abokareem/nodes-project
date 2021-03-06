<?php

return [
    'emails' => [
        'registered' => 'You have sent the maximum number of mail',
        'confirm' => 'Please, confirm your email'
    ],

    'two_fa' => [
        'code' => 'Code is invalid.',
        'reserve' => 'Code is invalid.',
        'server' => 'Internal error. Please contact support.'
    ],
    'admin' => [
        'access' => 'Access denied.',
        'wallets' => 'Min length wallet must be ' . config('admin.wallet_length') . ' symbols.'
    ],
    'server' => [
        'extension' => ':extension extension not installed.'
    ],
    'user' => [
        'insolvent' => 'You do not have enough money in your account.',
        'bill' => 'The wallet for this currency does not exist. Please create a new one.',
        'free_wallet' => 'Free wallets not exist now. Please try again later.'
    ],
    'node' => [
        'free' => 'Free share price exceeded.',
        'type' => 'This masternode type is not supported.',
        'max' => 'In the system for this coin, it is possible to create only :count incomplete masternode.'
    ],
    'withdrawal' => [
        'max' => 'The application for withdrawal can be submitted only by one investor per day.',
        'not_processing' => 'This withdrawal not processing already.'
    ],
    'bill' => [
        'not_processing' => 'This withdrawal not processing.'
    ]
];
