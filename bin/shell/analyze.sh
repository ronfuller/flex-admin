#!/bin/bash
set -e
cd packages/flex-admin
./vendor/bin/phpstan analyze --xdebug
