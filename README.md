Zend Framework 2 Tutorial Application
=====================================

Introduction
------------
This is a simple database driven application using the Model-View-Controller paradigm intended to give an
example on how to use Zend Framework 2 and Doctrine 2.

Installation
------------
The recommended way to get a working copy of this project is to clone the repository
and manually invoke `composer` using the shipped `composer.phar`:

    cd my/project/dir
    git clone git://github.com/C0d366/zf2-tutorial.git
    cd zf2-tutorial
    php composer.phar self-update
    php composer.phar install

(The `self-update` directive is to ensure you have an up-to-date `composer.phar` available.)

Take a look at the `composer.json`.

Virtual Host
------------
Afterwards, set up a virtual host to point to the public/ directory of the project.

    <VirtualHost *:80>
        ServerName zf2-tutorial.localhost
        DocumentRoot /path/to/zf2-tutorial/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/zf2-tutorial/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>

Make sure that you update your /etc/hosts or c:\windows\system32\drivers\etc\hosts file so that zf2-tutorial.localhost
is mapped to 127.0.0.1.

The website can then be accessed using http://zf2-tutorial.localhost

    127.0.0.1   zf2-tutorial.localhost localhost

Restart your web server.

Zend Developer Tools
--------------------
Note the `ZendDeveloperTools` module added to the module section of the `application.config.php`.

Note the following line added to the `index.php`:

    define('REQUEST_MICROTIME', microtime(true));

Copy distributed local settings and change them if you like to:

    cp vendor/zendframework/zend-developer-tools/config/zenddevelopertools.local.php.dist config/autoload/zenddevelopertools.local.php

Database
--------
We are going to use MySQL via Doctrine 2 ORM.

Note the `DoctrineModule` and `DoctrineORMModule` modules added to the module section of the `application.config.php`.

Put your database credentials in config/autoload/doctrine.local.php so that they are not in the git repository
(as *.local.php is ignored):

    <?php

    return array(
        'doctrine' => array(
            'connection' => array(
                'orm_default' => array(
                    'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                    'params' => array(
                        'host' => 'localhost',
                        'port' => '3306',
                        'user' => 'username',
                        'password' => 'password',
                        'dbname' => 'database',
                    )
                )
            )
        )
    );

Now create the `album` table via command line tool:

    ./vendor/bin/doctrine-module orm:schema-tool:create

Validate:

    ./vendor/bin/doctrine-module orm:validate-schema

Run this SQL statement to put some data into it:

    INSERT INTO album (artist, title)
    VALUES
    ('The  Military  Wives',  'In  My  Dreams'),
    ('Adele',  '21'),
    ('Bruce  Springsteen',  'Wrecking Ball (Deluxe)'),
    ('Lana  Del  Rey',  'Born  To  Die'),
    ('Gotye',  'Making  Mirrors');
