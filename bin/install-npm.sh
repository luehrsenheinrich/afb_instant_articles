#!/bin/bash

# Exit if any command fails
set -e

# Include useful functions
. "$(dirname "$0")/includes.sh"

echo "";

# Install/update packages
echo $(status_message "Installing and updating NPM packages..." )
npm install
