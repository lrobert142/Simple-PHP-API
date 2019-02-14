#!/usr/bin/env bash

usage() {
    cat << EOF
Usage
    run_tests <test-selector>: Run tests with the given selector. Run all tests if no selector given.
EOF

}

var_set () {
    local val=$(eval "echo \$$1")
    test -n "$val"
}

require_var () {
    if ! var_set $1;then
        echo "$1 not defined"
        exit 1
    fi
}

run_tests() {
    #TODO: Flags for different types of tests
    #Run local MySQL instance: docker run -it -p 3306:3306 --name mysql-local -e MYSQL_ROOT_PASSWORD=root -d mysql:5.7
    ./vendor/bin/phpunit --bootstrap vendor/autoload.php --testdox tests
}

if [ $# -eq 0 ]; then
    usage
else
    eval $@
fi
