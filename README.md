Helpers for PHP Application
===========================

[![Join the chat at https://gitter.im/rhosocial/helpers](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/rhosocial/helpers?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
The Helpers for PHP Application.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist rhosocial/helpers "*"
```

or add

```
"rhosocial/helpers": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \rhosocial\base\helpers\IP::IPv4toInteger('192.168.1.1'); ?>
```

