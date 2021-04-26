Kanvas Packages
============

[![Latest Stable Version](https://poser.pugx.org/gino-pane/composer-package-template/v/stable)](https://packagist.org/packages/gino-pane/composer-package-template)
[![License](https://poser.pugx.org/gino-pane/composer-package-template/license)](https://packagist.org/packages/gino-pane/composer-package-template)
[![composer.lock](https://poser.pugx.org/gino-pane/composer-package-template/composerlock)](https://packagist.org/packages/gino-pane/composer-package-template)
[![Total Downloads](https://poser.pugx.org/gino-pane/composer-package-template/downloads)](https://packagist.org/packages/gino-pane/composer-package-template)
[![Tests](https://github.com/bakaphp/kanvas-packages/workflows/Tests/badge.svg?branch=development)](https://github.com/bakaphp/kanvas-packages/actions?query=workflow%3ATests)

If you are trying to create a new PHP Composer package, whether it is going to be submitted to [packagist.org](packagist.org) 
or just to exist in your Github account, this template package of files will surely help you make the process a lot easier 
and faster.

Requirements
------------

* PHP >= 7.2;
* PhalconPHP.

Features
--------

* PSR-4 autoloading compliant structure;
* PSR-2 compliant code style;
* Unit-Testing with PHPUnit 6;
* Comprehensive guide and tutorial;
* Easy to use with any framework or even a plain php file;
* Useful tools for better code included.

Installation
============
    composer create-project kanvas/libraries
    
This will create a basic project structure for you:


Useful Tools
============

Running Tests:
--------
 
    composer test

# Social Package

## Indexing Elastic Messages

To create a new index for messages use the following command:

``` bash
    php cli/cli.php social indexMessages
```

## Erasing the messages index

In case you want you want to erase the messages index, in your terminal, execute the following:

``` bash
    php cli/cli.php social eraseMessages
```

Notice: The previous commands should only be used when Kanvas Packages in combination with a project already using Kanvas.



Changelog
=========

To keep track, please refer to [CHANGELOG.md](https://github.com/GinoPane/composer-package-template/blob/master/CHANGELOG.md).

Contributing
============

1. Fork it.
2. Create your feature branch (git checkout -b my-new-feature).
3. Make your changes.
4. Run the tests, adding new ones for your own code if necessary (phpunit).
5. Commit your changes (git commit -am 'Added some feature').
6. Push to the branch (git push origin my-new-feature).
7. Create new pull request.

Also please refer to [CONTRIBUTION.md](https://github.com/GinoPane/composer-package-template/blob/master/CONTRIBUTION.md).

License
=======

Please refer to [LICENSE](https://github.com/GinoPane/composer-package-template/blob/master/LICENSE).
