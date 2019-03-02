#!/bin/bash

# For Ubuntu 18.10
# description: Compiles the main.c into main with Docker

###############################################################################
# Copyright 2019 by Onegina Alyona
# Authors: Onegina Alyona


# running_dir
RUNNING_DIR=${1}

cd $RUNNING_DIR
# docker run --rm -v "$PWD":/usr/src/main -w /usr/src/main gcc:4.9 gcc -o main main.c 2>error.txt
docker run --rm -v "$PWD":$RUNNING_DIR -w $RUNNING_DIR gcc:4.9 gcc -o main main.c 2>error.txt
