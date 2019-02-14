<?php

use DI\Container;

return [
    'config' => array(
        'jwt_expiration_epoch' => intval($_ENV['JWT_EXPIRATION_EPOCH']),
        'jwt_issuer' => $_ENV['JWT_ISSUER'],
        'jwt_secret' => $_ENV['JWT_SECRET'],
    ),
    'db' => Database\createConnection(),
    'user.dao' => function (Container $c) {
        return new AuthMySqlDAO($c->get('db'));
    },
    'user.handler' => function (Container $c) {
        return new DefaultAuth($c->get('user.dao'), $c->get('config'));
    },
];
