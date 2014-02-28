[![Stories in Ready](https://badge.waffle.io/thesponge/glider.png?label=ready&title=Ready)](https://waffle.io/thesponge/glider)
## Installation

First, clone the repository and be sure to do it recursively, so all the
submodules will be pulled too.

    git clone --recursive git@github.com:nightsh/glider.git

Then, create the database and some user for it.

Time to import the database schema:

    mysql -u <USER> -p <DATABASE_NAME> < glider-schema.sql


## Config

Configuration is stored in `protected/etc/*`. You might want to tweak the
folowing:

* `config.base.php` is used to indicate the environment
* copy `config.env.php` to a new file, named for example `config.development.php`
* in `config.base.php` define the environment as the `ENV` constant
* in the newly created environment file, fill in your database login details

## TODO

Create an initial login and fix the inviting system. At the moment, you must
hack the way in for new users, like this:

* create according records in the `auth_user*` tables
* password is stored in plain MD5 for the moment
* login using <BASE_URL>/?login

