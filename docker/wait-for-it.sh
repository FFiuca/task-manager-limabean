#!/bin/sh

HOST=$1
PORT=$2
shift 2
CMD="$@"

echo "Waiting for $HOST:$PORT to be ready..."

while ! nc -z $HOST $PORT 2>&1; do
    echo "Try connect for $HOST:$PORT ..."

    # Check DNS resolution
    echo "Checking DNS resolution for $HOST..."
    getent hosts $HOST || echo "DNS resolution failed for $HOST"

    # Check if the port is open
    echo "Checking if port $PORT is open on $HOST..."
    nc -z $HOST $PORT 2>&1 || echo "Error: $(nc -z $HOST $PORT 2>&1)"

    sleep 1
done

echo "$HOST:$PORT is ready. Running command: $CMD"
exec $CMD
