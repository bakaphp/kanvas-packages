Kanvas Packages
============

[![Latest Stable Version](https://poser.pugx.org/kanvas/packages/v)](//packagist.org/packages/kanvas/packages) [![Total Downloads](https://poser.pugx.org/kanvas/packages/downloads)](//packagist.org/packages/kanvas/packages) [![Latest Unstable Version](https://poser.pugx.org/kanvas/packages/v/unstable)](//packagist.org/packages/kanvas/packages) 
[![Tests](https://github.com/bakaphp/kanvas-packages/workflows/Tests/badge.svg?branch=development)](https://github.com/bakaphp/kanvas-packages/actions?query=workflow%3ATests)

kanvas Ecosystem Packages to expand your app specific use case

Requirements
------------

* PHP >= 7.4;
* PhalconPHP.
* Kanvas Core >= 0.3

Packages
--------

* Social : Add social network feature to any of your apps
* Payments : Add plaid payment functionality to your app
* AppSearch : Integrate app search for your app
* MagicImports : Easy CSV import for SPA Kanvas App
* Wallet
* WorkFlow
* Mobile Payments

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

License
=======

Please refer to [LICENSE](https://github.com/GinoPane/kanvas/packages/blob/master/LICENSE).
