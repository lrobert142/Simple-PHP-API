<?php

use DI\Container;

return [
    'config' => array(
        'db_host' => $_ENV['DB_HOST'],
        'db_name' => $_ENV['DB_NAME'],
        'db_password' => $_ENV['DB_PASSWORD'],
        'db_user' => $_ENV['DB_USER'],
        'jwt_expiration_epoch' => intval($_ENV['JWT_EXPIRATION_EPOCH']),
        'jwt_issuer' => $_ENV['JWT_ISSUER'],
        'jwt_secret' => $_ENV['JWT_SECRET'],
    ),
    'db' => function (Container $c) {
        return Database\createConnection($c->get('config'));
    },
    'user.dao' => function (Container $c) {
        return new AuthMySqlDAO($c->get('db'));
    },
    'user.handler' => function (Container $c) {
        return new DefaultAuth($c->get('user.dao'), $c->get('config'));
    },
];
