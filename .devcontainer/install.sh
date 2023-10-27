#!/usr/bin/env sh

set -eux

# Install the WP-CLI.
if [ ! -f /usr/local/bin/wp ]; then
	echo "Installing WP-CLI..."
	sudo curl -sS -o /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
	sudo chmod +x /usr/local/bin/wp
fi

# Install specific Composer version.
COMPOSER_VERSION=2.3.10
if [ ! -f /usr/local/bin/composer ]; then
	echo "Installing Composer ${COMPOSER_VERSION}..."
	sudo curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer --2 --version=${COMPOSER_VERSION}
fi

# Copy the welcome message
if [ ! -f /usr/local/etc/vscode-dev-containers/first-run-notice.txt ]; then
	sudo cp .devcontainer/welcome-message.txt /usr/local/etc/vscode-dev-containers/first-run-notice.txt
fi
