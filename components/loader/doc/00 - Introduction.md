Introduction
============

What is it?
-----------

Yampee Loader is a PHP 5 set of autoloaders which use the most effective tools on
your server. It uses the PEAR-naming convention (http://pear.php.net/).

At the moment, Yampee Loader can use Apc, Xcache and WinCache.

Moreover, if there is no cache tool installed on your server, you still can use the
Yampee_Loader_Dumper, which is an object to dump dynamically loaded classes to
improve perfomances.

### Easy to use

There is only one archive to download, and you are ready to go. No
configuration, no installation. Drop the files in a directory and start using
it today in your projects.

### Open-Source

Released under the MIT license, you are free to do whatever you want, even in
a commercial environment. You are also encouraged to contribute.

### Documented

Yampee Loader is fully documented and of course a full API documentation.

### Fast

One of the goal of Yampee Loader is to find the best way to be as faster as possible.

### Clear error messages

Whenever you have a problem with your loader, the library outputs a
helpful message with the class name and the file where the loader searched.
It eases the debugging a lot.


Installation
------------

The best way to install Yampee Loader is to clone this repository:

`git clone git://github.com/yampee/Loader.git`

The library can be loaded by the built-in autoloader:

``` php
require '../src/Yampee/Loader/Universal.php';

$loader = new Yampee_Loader_Universal();
```

Support
-------

Support questions and enhancements can be discussed on
[GitHub](https://github.com/yampee/Loader/issues).

If you find a bug, you can create a ticket in the
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
