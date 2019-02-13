<?php

use DI\Container;

return [
    'db' => Database\createConnection(),
    'user.dao' => function (Container $c) {
        return new AuthMySqlDAO($c->get('db'));
    },
    'user.handler' => function (Container $c) {
        return new DefaultAuth($c->get('user.dao'));
    },
];
