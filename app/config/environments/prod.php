<?php
// production environment configuration
return array(
    'components' => array(
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=kotipolku',
            'username' => 'root',
            'password' => 'pHwe10LB',
        ),
        'errorHandler' => array(
            'connection' => array(
                'environmentName' => 'production',
            ),
        ),
    ),
);