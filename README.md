# TuBones #

This is the basic cakephp 2.0 app for a site with users. Very basic MVC has been included for
extending the CakeDC users plugin.

## Installing ##
* clone the repo into /path/to/project/
* replace weboot/index.php with one that is configured for your environment
* create the empty database and Config/database.php
* execute from /path/to/project/: cake Migrations.migration --plugin users

## Included plugins ##
* [CakePHP Debug Kit][]
* CakeDC migrations
* CakeDC users
[CakePHP Debug Kit]: https://github.com/cakephp/debug_kit.git

## Extending the users plugin ##

No changes need to be made to the users plugin. Any changes to existing methods should be made
by overriding the methods in the AppUsersController and AppUser. Changes to the views should be made by
creating app/View/AppUsers directory and overriding the plugin views.

## Updating the included plugins ##

The plugins were included in this repo using "fake submodules". Use the techniques outlined by:
http://debuggable.com/posts/git-fake-submodules:4b563ee4-f3cc-4061-967e-0e48cbdd56cb
