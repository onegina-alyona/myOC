#!/bin/bash

# For Ubuntu 18.10
# description: Compiles the main.cpp into main with Docker

###############################################################################
# Copyright 2019 by Onegina Alyona
# Authors: Onegina Alyona


# running_dir
RUNNING_DIR=$2
cd $RUNNING_DIR

set -um

[[ $# -gt 0 ]] || { sed -n '2,/^#$/ s/^# //p' <"$0"; exit 1; }

pgid=$(ps -o pgid= $$)

case $(uname) in
    Darwin|*BSD) sizes() { /bin/ps -o rss= -g $1; } ;;
    Linux) sizes() { /bin/ps -o rss= -$1; } ;;
    *) echo "$(uname): unsupported operating system" >&2; exit 2 ;;
esac

(
peak=0
while sizes=$(sizes $pgid)
do
    set -- $sizes
    sample=$((${@/#/+}))
    let peak="sample > peak ? sample : peak"
    sleep 0.1
done
echo "$peak"
) &
monpid=$!

exec timeout 5s $1<in.txt >out.txt 2>error.txt

# exec "$@"