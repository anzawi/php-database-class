PDO Database Class
============================

A database class which uses the PDO extension.
* Allows one connection with the database and deny duplicate connection, 
* this speeds up to use the database and reduces the load on the server.
* supports many drivers (mysql, sqlite, PostgreSQL, mssql, sybase, Oracle Call Interface -oci-)

If you have any issue please open issue to fix it.

```
any suggestions would you like added or modified write to us at <m.anzawi2013@gmail.com>
```
### install via composer 

```
composer require phptricks/database_class
```

```json
{
	"require": {
		"phptricks/database_class": ">=5.0.0"
	}
}
```

--------------------

### Create First Migration : 

```
php phptricks migrate:make UsersTable create -table=users
```

### Migrate Tables : 

```
php phptricks migrate
```

### Create First Model :

```
php phptricks model UserModel --table=users
```

results : 

```php
use PHPtricks\Orm\Model;
class UserModel extends Model
{
    protected $_table = 'users';
}
```


### Select All Data : 

```php
$user = new UserModel();
$allUsers = $user->select();
```

### Insert Data : 

```php
$user = new UsersModel();
$user->insert([
    'username' => 'al-anzawi',
    'email' => 'm.anzawi2013@gmail.com'
]);
```


## Full Documentation : 

[PHPtricks-ORM Full Documentation](https://anzawi.github.io/phptricksorm-docs)


#### Change Log : 

#### 5.0.0
* ADD : Modeling System  
* ADD : Migrations
* ADD : Commands-line
* ADD : Joins [Models Relation]
* ADD : `toJsonFormatted()` method
* ADD : `drop()` method - to delete tables
* MODIFY : Database Class 
* MODIFY : `toArray()` Method 

> please note: version 5.0.0 ** not ** compatible with old versions.
> if you using PHPtricks-ORM v4.x.x or older in your project and you want to upgrade to v5.0.0,
> you need to change a lot of things to meet new version.


---

#### 4.1.0
* ADD : `parseWhere(array $cons, $type = "AND")` method
* ADD : `lastInsertedId()` method
* ADD : `createOrUpdate($values, $conditionColumn = [])` method
* ADD : `findBy($column, $value)` method
* REMOVE: `empty()` method

---

#### 4.0.0
* MODIFY : namespace to `PHPtricks\Orm`
* MODIFY : files structure

---

#### 3.1.0
* FIX : Duplicate connection
* ADD : Some methods
    * `each()` -> to each all collection values
    * `map()`  -> to map all results
    * `all()`  -> to get all results
    * `last()` -> to get last selected record
    * `filter()` -> to filter values
    * `keys()` -> to get collection keys
    * `toJson()` -> to convert results to json format
* ADD : convert results to json format when use collection as string automatically


---


#### 3.0.0
* ADD    : direct update functionality
* FIX    : `dataView` method with first method
* MODIFY : methods chaining technique
    * `select`, `first`, `find`, `paginate` NOW return Database Object
    * but you can use results as array or object
    * any time you can add `->results()` to convert to array or object


---

#### 2.1.0
* Add : pagination functionality
* Add : count method
* Add : `dataView` method (to display 'selected results' in a table)
* FIX : `in()` method
* FIX : `notIn()` method


---


#### 2.0.0
* ADD : supports multi `drivers`
    * mysql
    * PostgreSQL
    * sqlite
    * msSql
    * sybase
    * Oracle Call Interface (OCI)
* ADD : multi where
* ADD : type of where
* ADD : show query
* FIX : default constraint
* ADD : limit function
* ADD : offset function
* rebuilt 80% of methods
* change License terms


---


#### 1.1.0

* ADD Some Data Definition Language (DDL) functions.
  * ADD Create New Table 
  * ADD Drop Table
  * ADD Alter Table
    * ADD new Column
    * Change Column Name
    * Drop Column
    * Rename Column


---


#### 1.0.1
* FIX: `first()` method to compatible with PHP V +5.3.0


---


#### 1.0.0
* First Release