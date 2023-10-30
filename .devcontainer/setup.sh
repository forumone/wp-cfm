#!/usr/bin/env sh

set -eux

if [ -z ${CODESPACE_NAME+x} ]; then
	SITE_HOST="http://localhost:8080"
else
	SITE_HOST="https://${CODESPACE_NAME}-8080.${GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN}"
fi

PLUGIN_DIR=/workspace/wp-cfm

# Install Composer dependencies.
cd "${PLUGIN_DIR}"
composer install

# Install NPM dependencies.
cd "${PLUGIN_DIR}"
npm ci

# Setup the WordPress environment.
cd "/app"
echo "Setting up WordPress at $SITE_HOST"
wp core install --url="$SITE_HOST" --title="WP-CFM Plugin Development" --admin_user="admin" --admin_email="wordpress@forumone.com" --admin_password="password" --skip-email
