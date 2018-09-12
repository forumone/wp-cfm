VERSION := 1.0.0
PLUGINSLUG := woocart-defaults
SRCPATH := $(shell pwd)/src

bin/linux/amd64/github-release:
	wget https://github.com/aktau/github-release/releases/download/v0.7.2/linux-amd64-github-release.tar.bz2
	tar -xvf linux-amd64-github-release.tar.bz2
	chmod +x bin/linux/amd64/github-release
	rm linux-amd64-github-release.tar.bz2

ensure:
vendor: src/vendor
	composer install

tests: vendor
	bin/phpunit

src/vendor:
	cd src && composer install

build: ensure
	sed -i "s/@##VERSION##@/${VERSION}/" src/index.php
	mkdir -p build
	cp -ar $(SRCPATH) $(PLUGINSLUG)
	zip -r $(PLUGINSLUG).zip $(PLUGINSLUG)
	rm -rf $(PLUGINSLUG)
	mv $(PLUGINSLUG).zip build/
	sed -i "s/${VERSION}/@##VERSION##@/" src/index.php

publish: build bin/linux/amd64/github-release
	bin/linux/amd64/github-release upload \
		--user woocart \
		--repo $(PLUGINSLUG) \
		--tag "v$(VERSION)" \
		--name $(PLUGINSLUG)-$(VERSION).zip \
		--file build/$(PLUGINSLUG).zip

release:
	git stash
	git fetch -p
	git checkout master
	git pull -r
	git tag v$(VERSION)
	git push origin v$(VERSION)
	git pull -r

fmt: ensure
	bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs
	bin/phpcbf --standard=WordPress src/framework
	bin/phpcbf --standard=WordPress src/index.php

lint: ensure
	bin/phpcs --config-set installed_paths vendor/wp-coding-standards/wpcs
	bin/phpcs --standard=WordPress src/framework
	bin/phpcs --standard=WordPress src/index.php
