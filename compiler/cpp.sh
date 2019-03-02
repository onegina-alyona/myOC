#!/bin/bash

# For Ubuntu 18.10
# description: Compiles the main.cpp into main with Docker

###############################################################################
# Copyright 2019 by Onegina Alyona
# Authors: Onegina Alyona


# running_dir
RUNNING_DIR=${1}

cd $RUNNING_DIR

docker run --rm -v "$PWD":$RUNNING_DIR -w $RUNNING_DIR gcc:4.9 g++ main.cpp -o main -O2 -Wall -lm --static 2>error.txt

