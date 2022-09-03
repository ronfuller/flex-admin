#!/bin/bash
git checkout main
git merge develop
git push -u origin main --force
git checkout develop
