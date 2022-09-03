#!/bin/bash
composer fix
composer analyze
git add .
git commit -m "$1"
git push -u origin develop --force
