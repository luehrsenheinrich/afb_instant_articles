#!/bin/bash

# Exit if any command fails
set -e

# Include useful functions
. "$(dirname "$0")/includes.sh"

echo "";

# Load NVM
if [ -n "$NVM_DIR" ]; then
	# The --no-use option ensures loading NVM doesn't switch the current version.
	if [ -f "$NVM_DIR/nvm.sh" ]; then
		. "$NVM_DIR/nvm.sh" --no-use
	elif command_exists "brew" && [ -f "$(brew --prefix nvm)/nvm.sh" ]; then
		# use homebrew if that's how nvm was installed
		. "$(brew --prefix nvm)/nvm.sh" --no-use
	fi
fi

# Check if the current node version is up to date.
if [ "$TRAVIS" != "true" ] && [ "$(nvm current)" != "$(nvm version-remote --lts)" ]; then
	echo $(warning_message "Node version does not match the latest long term support version. Please run this command to install and use it:" )
	echo $(warning_message "$(action_format "nvm install --lts")" )
	echo $(warning_message "After that, re-run the setup script to continue." )
	exit 1
fi

# Make sure npm is up-to-date
npm install npm -g

# Install/update package
echo $(status_message "Installing and updating NPM packages..." )
npm install
