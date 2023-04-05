#!/bin/bash
set -e
./bin/format.sh
./bin/analyze.sh
./bin/test.sh
git add .
git commit -m "$1"
git push -u origin develop --force
