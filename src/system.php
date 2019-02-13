<?php

use DI\Container;

return [
    'db' => Database\create_connection(),
    'user.dao' => function (Container $c) {
        return new AuthMySqlDAO($c->get('db'));
    },
    'user.handler' => function (Container $c) {
        return new DefaultAuth($c->get('user.dao'));
    },
];
