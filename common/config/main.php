<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'stripe' => [ //autoload Stripe pay system
            //'class' => 'stripe\stripe-php\lib\Stripe',
            'class' => 'ruskid\stripe\Stripe',
            'publicKey' => "pk_test_jgmwLo0RtxV342m0e5sfmxwY",
            'privateKey' => "sk_test_Pmtiqut8msdIXyyZqGniDvBy",
        ],
    ],
];
