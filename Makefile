VERSION := 1.0.16
PLUGINSLUG := spiderblocker
MAINFILE := index.php
SRCPATH := $(shell pwd)/src
SVNUSER := niteoweb

test:
	bin/phpunit

release:
	mkdir -p build
	cp -ar src $(PLUGINSLUG)
	zip -r $(PLUGINSLUG).zip $(PLUGINSLUG)
	rm -rf $(PLUGINSLUG)
	mv $(PLUGINSLUG).zip build/

deploy:
	@rm -fr /tmp/$(PLUGINSLUG)/
	svn co http://plugins.svn.wordpress.org/$(PLUGINSLUG)/ /tmp/$(PLUGINSLUG)
	cp -ar $(SRCPATH)/* /tmp/$(PLUGINSLUG)/trunk/
	cd /tmp/$(PLUGINSLUG)/trunk/; svn add * --force
	cd /tmp/$(PLUGINSLUG)/trunk/; svn commit --username=$(SVNUSER) -m "Updating to $(VERSION)"
	cd /tmp/$(PLUGINSLUG)/; svn copy trunk/ tags/$(VERSION)/
	cd /tmp/$(PLUGINSLUG)/tags/$(VERSION)/; svn commit --username=$(SVNUSER) -m "Tagging version $(VERSION)"
	# rm -fr /tmp/$(PLUGINSLUG)/