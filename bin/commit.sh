#!/bin/bash
./format.sh
./analyze.sh
./test.sh
git add .
git commit -m "$1"
git push -u origin develop --force
