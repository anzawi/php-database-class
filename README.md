PDO Database Class
============================

A database class which uses the PDO extension.
* Allows one connection with the database and deny duplicate connection, 
* this speeds up to use the database and reduces the load on the server.
* supports many drivers (mysql, sqlite, PostgreSQL, mssql, sybase, Oracle Call Interface -oci-)

If you have any issue please open isseu to fix it.

* To Arabic latst version go to : http:/www.phptricks.org/PDO-class-v-2-xp  : للشرح باللغة العربية للاصدار  الحالي توجه الى


* To Arabic v-2 go to : http://www.phptricks.org/PDO-class-v-2  : للشرح باللغة العربية للاصدار 2 توجه الى

* To Arabic v-1 go to : http://www.phptricks.org/PDO-class  : للشرح بالغة العربية للاصدار 1 توجه الى


### install via composer 
```json
{
	"require" : 
	{
		"phptricks/database_class" : "dev-master"
	}
}
```

--------------------
# to use class :

## (config) :
- go to (database_config.php) file
- config class as your project need

## describe configuration :
 - fetch : PDO Fetch Style By default, database results will be returned as instances of the PHP stdClass object.
 - default : Default Database Connection Name (driver) by default (mysql)
 - connections : Database Connections (drivers).
 
###### <<<<<< set database conection information..!  >>>>>>

------------------
# how to use :
### step 1 : 
 - Include the class in your project
 
```php
    <?php
    include_once('phptricks/Database.php');
```
### step 2 :
- Create the instance (connect with database)
```php
    use PHPtricks\Database\Database;
    $db = Database::connect();
```

# how it work (methods):

## select() :
 - select all data from `test` table :
    ```php
    $allData = $db->table('test')->select();
    
    print_r($allData);
    ```
- select `id`, `name`, `email` fro all users from `users` table
    ```php
    $coustomFields = $db->table('users')->select(['id', 'name', 'email']);
    
    print_r($coustomFields);
    ```
- select `post` where its `id` is equal 5
    ```php
    $post = $db->table('posts')->where('id', '=', 5)->select();
    // or
    $post = $db->table('posts')->where('id', 5)->select();
    // Custom fields
    $post = $db->table('posts')->where('id', 5)->select(['id', 'title', 'body']);
    ```
- multi where :
    ```php
    $post = $db->table('posts')
    ->where('vote', '>', 5)
        ->where('visetors', '>', 200)
        ->select();
    // Custom fields
     $post = $db->table('posts')
        ->where('vote', '>', 5)
        ->where('visetors', '>', 200)
        ->select(['id', 'title', 'body']);
    ```
    you can use `where` method an infinite :)
    
### where types :
- whereBetween() :
    ```php
    $db->->table('posts')
        ->whereBetween('data', [$start, $end])
        ->select();
    ```
- likeWhere() :
    ```php
    $db->->table('users')
        ->likeWhere('name', 'mohammad')
        ->select();
    ```
- orWhere() :
    ```php
    $db->table('posts')
        ->where('id', 5)
        ->orWhere('id', 3)
        ->select(['title', 'body']);
    ```
### get first row :
```php
 $db->table('posts')
        ->where('id', 5)
        ->orWhere('id', 3)
        ->first();
```
all examples above you can replace `select` with `first` to get only first row selected.

### find method :
find where `id`
```php
$db->table('users')->find(1);
// SELECT * FROM `users` where `id` = 1
```
## insert :
insert new user to `users` table:
```php
$db->table('users')
    ->insert([
        'name' => 'mohammad',
        'email' => 'mohammad@email.com',
        'password' => 'secret',
    ]);

```
insert new post to `posts` table:
```php
$db->table('posts')
    ->insert([
        'title' => 'my post title',
        'body' => 'post body and description',
        // ....
    ]);
```

## update :
if we need to update user name to 'ali' where his id is 5 :
```php
$db->table('users')
    ->where('id', 5)
    ->update([
        'name' => 'ali'
    ]);
```
update all posts title like (`test`) to (`this is a test post`)
```php
$db->table('posts')
    ->likeWhere('title', 'test')
    ->update([
        'title' => 'this is a test post'
    ]);
```
## delete :
delete user has id 105
```php
$db->table('users')
    ->where('id', 105)
    ->delete();
```
delete all posts `voted < 2 ` and `visetors < 200 ` or `id is 2`
```php
$db->table('posts')
    ->where('vote', "<", 2)
    ->where('visetors', '<', 200)
    ->orWhere('id', 2)
    ->delete();
```


--------------------------------

# Data Definition Language (DDL) :

### Create Table : 

```php
$db = DB::get();

$db->table('my_new_table_name')->schema('schema as array')->create();
```
EX : 

```php
$db = DB::get();

$db->table('students')->schema([
		'id' => 'increments',
		'name' => 'string:255 | not_null',
		'number' => 'int|unsigned';
	])->create();
```
the SQL Statment for this :
CREATE TABLE students (
						id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL,
						name VARCHAR(255) NOT NULL,
						number INT UNSIGNED
					
					)
					
#### PLEAS NOTE: 
'id' => 'increments'
mean the id column well be integer,primary key, auto increment not null,  and unsigned

### ADD Constraints
'number' => 'int|my_constraint|other_constraint|more_constraint';

SO the first one is a column type and other well be Constraints

### Default Value

to set defualt value type :
```php
'number' => 'int|unsigned|default:222';
'name' => 'int|unsigned|default:hello-this-a-default-value';

// note : the charecter (-) replaced with white space
```
### Full Example :
```php
$db = DB::get();

$schema = [
	'id' => 'increments',
	'username' => 'string:100|not_null',
	'full_name' => 'string:255|defualt:no-name',
	'joind' => 'timestamps',
	'user_email' => 'string:100|not_null',
];

$db->table('users')->schema($schema)->create();

```
# ADD Column :
```php
$db->table('target_table')->alterSchema('condetions is array')->alter();
$db->table('table')->alterSchema(['add', 'column_name', 'type'])->alter();
```
#### EX:
```php
$db->table('users')->alterSchema(['add', 'last_login', 'date'])->alter();
```

# RENAME Column :
```php
$db->table('target_table')->alterSchema('condetions is array')->alter();
$db->table('table')->alterSchema(['rename', 'column_name', 'new_column_name' ,'type'])->alter();
```
#### EX:
```php
$db->table('users')->alterSchema(['rename', 'last_login', 'last_session', 'date'])->alter();
```

# EDIT Column  type:
```php
$db->table('table')->alterSchema(['modify', 'column_name', 'new_type'])->alter();
```
#### EX:
```php
$db->table('users')->alterSchema(['modify', 'full_name', 'text'])->alter();
```

# DROP Column :
```php
$db->table('table')->alterSchema(['drop', 'column_name'])->alter();
```
#### EX:
```php
$db->table('users')->alterSchema(['drop', 'full_name'])->alter();
```


# Advanced Usage
 - `COMING SOON`

### THATS IT :) 

### I HOPE THIE HELP YOU.

=============================
# Change Log
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
* rebuilt 80% of methods
* change License terms
#### 1.1.0

* ADD Some Data Definition Language (DDL) functions.
  * ADD Create New Table 
  * ADD Drop Table
  * ADD Alter Table
    * ADD new Column
    * Change Column Name
    * Drop Column
    * Rename Column

#### 1.0.1
* FIX first method -> to compatible with PHP V +5.3.0

#### 1.0.0
* First Release


=============================
# License
### MIT



