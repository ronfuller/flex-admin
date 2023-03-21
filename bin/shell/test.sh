#!/bin/bash
./vendor/bin/pest --filter=setup
./vendor/bin/pest --exclude-group=setup --parallel --processes=2
./vendor/bin/pest --exclude-group=setup --coverage --min=60
