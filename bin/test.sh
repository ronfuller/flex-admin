#!/bin/bash
./vendor/bin/pest --filter=setup
./vendor/bin/pest --exclude-group=setup --parallel --processes=12