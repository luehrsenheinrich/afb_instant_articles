#!/bin/bash

# Exit if any command fails
set -e

# Change to the expected directory
cd "$(dirname "$0")/.."

export $(cat .env | grep -v ^# | xargs)
