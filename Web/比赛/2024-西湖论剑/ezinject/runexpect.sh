#!/bin/sh

# Set LD_LIBRARY_PATH

export LD_LIBRARY_PATH=$1
echo "Assuming LD_LIBRARY_PATH in runexpect :" $LD_LIBRARY_PATH
shift
echo "Running command: $*"
#Running command: /app/expect/expect /app/call.sh 1 whoami
$*

exit $?
