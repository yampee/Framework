Yampee Database: a PHP 5.2 Active Record implementation
=============================================================

What is Yampee Database ?
----------------------------

Yampee Database is a PHP library that implements Active Record design
pattern to manage your database.

An example ?

``` php
<?php
$db = new Yampee_Db_Manager('mysql:host=localhost;dbname=test', 'root', '');

$records = $db->createQueryBuilder()
	->select('t.field, t.otherField, ot.foreignField')
	->from('table t')
	->leftJoin('otherTable ot ON ot.table_id = t.id')
	->where('t.id = :id')
	->setParameter('id', 4)
	->limit(5)
	->execute();

$db->createQueryBuilder()
	->insert('table t')
	->set('t.firstField', $firstValue)
	->set('t.secondField', $secondValue)
	->execute();

$db->createQueryBuilder()
	->update('table t')
	->set('t.firstField', $firstValue)
	->set('t.secondField', $secondValue)
	->where('t.id = :id')
	->setParameter('id', 4)
	->execute();
```

As Yampee Database use PDO by default, it is compatible with any database system supported by PDO.
However, the QueryBuilder is designed for MySQL, so you may won't be able to use it in a specific context.

Documentation
-------------

The documentation is to be found in the `doc/` directory.

About
-------

Yampee Database is licensed under the MIT license (see LICENSE file).
The Yampee Database library is developed and maintained by the Titouan Galopin.
