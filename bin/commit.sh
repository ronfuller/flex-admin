#!/bin/bash
./vendor/bin/sail shell ./bin/shell/format.sh
./vendor/bin/sail shell ./bin/shell/analyze.sh
./vendor/bin/sail shell ./bin/shell/test.sh
git add .
git commit -m "$1"
git push -u origin develop --force
