<?php

use DI\Container;

require_once('database.php');
require_once('auth/core.php');
require_once('auth/DAO.php');

return [
    'db' => Database\create_connection(),
    'user.dao' => function (Container $c) {
        return new AuthMySqlDAO($c->get('db'));
    },
    'user.handler' => function (Container $c) {
        return new DefaultAuth($c->get('user.dao'));
    },
];
