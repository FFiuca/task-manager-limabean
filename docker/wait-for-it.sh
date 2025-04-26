#!/bin/sh

HOST=$1
PORT=$2
shift 2
CMD="$@"

echo "Waiting for $HOST:$PORT to be ready..."

while ! nc -z $HOST $PORT; do
    sleep 1
done

echo "$HOST:$PORT is ready. Running command: $CMD"
exec $CMD
