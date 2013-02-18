Introduction
============

What is it?
-----------

Yampee Database is a PHP library that implements Active Record design
pattern to manage your database.

As Yampee Database use PDO by default, it is compatible with any database system supported by PDO.
However, the QueryBuilder is designed for MySQL, so you may won't be able to use it in a specific context.

### Easy to use

There is only one archive to download, and you are ready to go. No
configuration, no installation. Drop the files in a directory and start using
it today in your projects.

### Open-Source

Released under the MIT license, you are free to do whatever you want, even in
a commercial environment. You are also encouraged to contribute.

### Documented

Yampee Database is fully documented and of course a full API documentation.

### Fast

One of the goal of Yampee Database is to find the best way to be as faster as possible.

### Clear error messages

Whenever you have a problem with your database, the library outputs a
helpful message. It eases the debugging a lot.


Installation
------------

The best way to install Yampee Database is to clone this repository:

`git clone git://github.com/yampee/Database.git`

The library can be loaded using the built-in autoloader:

``` php
require 'autoloader.php';

$db = new Yampee_Db_Manager();
```

If you have already an autoloader for PEAR-naming convention, you can of course use it.

Support
-------

Support questions and enhancements can be discussed on
[GitHub](https://github.com/yampee/Database/issues).

If you find a bug, you can create a Database in the
[GitHub issues](https://github.com/yampee/Loader/issues).

License
-------

This component is licensed under the *MIT license*:

>Copyright (c) 2013 Titouan Galopin
>
>Permission is hereby granted, free of charge, to any person obtaining a copy
>of this software and associated documentation files (the "Software"), to deal
>in the Software without restriction, including without limitation the rights
>to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
>copies of the Software, and to permit persons to whom the Software is furnished
>to do so, subject to the following conditions:
>
>The above copyright notice and this permission notice shall be included in all
>copies or substantial portions of the Software.
>
>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
>IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
>FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
>AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
>LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
>OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
>THE SOFTWARE.
