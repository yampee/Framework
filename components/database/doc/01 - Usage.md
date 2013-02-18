Using Yampee Loader
===================

Basic usage
-----------------------

The Yampee Database library consists of a main class, `Yampee_Db_Manager`,
which is a factory of all the other classes. Thus you will only use `Yampee_Db_Manager`.

To create a instance of `Yampee_Db_Manager`, you need to know your database parameters.

You have two ways to create an instance of your manager: using the DSN generator or
using a classic DSN string.

### Using the DSN generator

The DSN generator build your DSN string based on the parameters you give to it.
For instance, here, we create an instance of `Yampee_Db_Manager` for a MySQL
database called `test`, using user `root`.

``` php
<?php
$db = new Yampee_Db_Manager(new Yampee_Db_Dsn(Yampee_Db_Dsn::DRIVER_MYSQL, 'test'), 'root', '');
```

However, you can build many kind of DSN for many drivers:

```
Yampee_Db_Dsn::DRIVER_DBLIB, Yampee_Db_Dsn::DRIVER_FIREBIRD, Yampee_Db_Dsn::DRIVER_INFORMIX,
Yampee_Db_Dsn::DRIVER_MSSQL, Yampee_Db_Dsn::DRIVER_MYSQL, Yampee_Db_Dsn::DRIVER_OCI, Yampee_Db_Dsn::DRIVER_ODBC,
Yampee_Db_Dsn::DRIVER_PGSQL, Yampee_Db_Dsn::DRIVER_SQLITE, Yampee_Db_Dsn::DRIVER_SYBASE,
```

### Using a classic DSN string

The DSN generator is completely optionnal:

``` php
<?php
$db = new Yampee_Db_Manager('mysql:host=localhost;dbname=test', 'root', '');
```

Execute a query
-----------------------

To execute a query, it's very easy:

``` php
<?php
$results = $db->query('SELECT * FROM test');
```

Here, `$results` will contain a list of `Yampee_Db_Record` instances:

``` php
<?php
$results = $db->query('SELECT * FROM test');

foreach ($results as $result) {
	echo $result->getFirstField();
	echo $result->getDateField()->format('d/m/Y H:i');
}

// Or with parameters

$results = $db->query('SELECT * FROM test WHERE field = :test', array('test' => $value));

foreach ($results as $result) {
	echo $result->getFirstField();
	echo $result->getDateField()->format('d/m/Y H:i');
}
```

> **Note**: The `query()` method use prepared requests to avoid SQL injections.

> **Note**: The date fields are converted to DateTime objects on the fly.

The QueryBuilder
-----------------------

The most powerful feature of Yampee Database is probably the query builder. With it,
you can build your own query very easily:

``` php
<?php
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

The QueryBuilder is much more flexible than a simple query: you can use it in a loop,
to add WHERE elements dynamically for instance:

``` php
<?php
$query = $db->createQueryBuilder()
	->select('t.field, t.otherField, ot.foreignField')
	->from('table t')
	->limit(5);

foreach ($parameters as $name => $value) {
	$query->andWhere($name.' = :'.$name)
		->setParameter($name, $value);
}

$results = $query->execute();
```

> **Note**: As the QueryBuilder use prepared requests, this sample is completely secured
> against SQL injections.