Zend Framework 2 Tutorial Application
=====================================

Introduction
------------
This is a simple database driven application using the Model-View-Controller paradigm intended to give an
introduction to using Zend Framework 2.

Installation
------------
The recommended way to get a working copy of this project is to clone the repository
and manually invoke `composer` using the shipped `composer.phar`:

    cd my/project/dir
    git clone git://github.com/C0d366/zf2-tutorial.git
    cd zf2-tutorial
    php composer.phar self-update
    php composer.phar install

(The `self-update` directive is to ensure you have an up-to-date `composer.phar`
available.)

Virtual Host
------------
Afterwards, set up a virtual host to point to the public/ directory of the
project and you should be ready to go!

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
The website can then be accessed using http://zf2-tutorial.localhost.

    127.0.0.1   zf2-tutorial.localhost localhost

Restart your web server.

Database
--------
We are going to use MySQL, via PHP's PDO driver, so create a database called zf2tutorial, and run these SQL statements
to create the album table with some data in it.

    CREATE TABLE album (
        id int(11) NOT NULL auto_increment,
        artist varchar(100) NOT NULL,
        title varchar(100) NOT NULL,
        PRIMARY KEY (id)
    );

    INSERT INTO album (artist, title)
    VALUES
    ('The  Military  Wives',  'In  My  Dreams'),
    ('Adele',  '21'),
    ('Bruce  Springsteen',  'Wrecking Ball (Deluxe)'),
    ('Lana  Del  Rey',  'Born  To  Die'),
    ('Gotye',  'Making  Mirrors');

local.php
---------
Put your database credentials in config/autoload/local.php so that they are not in the git repository (as local.php is ignored):

    <?php
    return array(
        'db' => array(
            'username' => 'YOUR USERNAME HERE',
            'password' => 'YOUR PASSWORD HERE',
        )
    );