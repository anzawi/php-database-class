PDO Database Class
============================

A database class for PHP-MySQL which uses the PDO extension.
* Allows one connection with the database and deny duplicate connection, 
* this speeds up to use the database and reduces the load on the server.
If you have any questions go to : http://www.phptricks.org/PDO-class

* To Arabic latst version go to : http://www.t3lam.net/PDO-class-v-2  : للشرح باللغة العربية للاصدار  الحالي توجه الى


* To Arabic last version go to : http://www.t3lam.net/PDO-class  : للشرح باللغة العربية للاصدار السابق توجه الى
* 
##To use the class

=======

* To Arabic go to : http://www.phptricks.org/PDO-class  : للشرح باللغة العربية توجه الى

##To use class

###declare constant variables

```php
<?php
define(HOST,     'localhost');
define(DBNAME,   'your_database_name');
define(USERNAME, 'database_username');
define(PASSWORD, 'database_password');
define(CHARSET,  'charst'); // utf8 RECOMMENDED
```
### Include the class in your project
```php
<?php
include_once('DB.class.php');
```
###Create the instance
```php
<?php
//  Get The Instance
$db = DB::get();
```

## How it Work
###query

```php
// get all -> return object
$db->query("SELECT * FROM table_name")->results();

// get first row -> return object
$db->query("SELECT * FROM table_name WHERE field = value")->first();
```

###Insert

suppose you want to insert (id, name, username, email) in ( users )table
```php
<?php
$userInformation = [
	'id'       => 1,
	'name'     => 'Mohammad',
	'username' => 'Anzawi',
	'email'    => 'email@example.com',
];

$db->table('users')->insert($userInformation );
```

#### Note
* $userInformation = [field name  , value]

###update
suppose you want to update name for Mohammad

* you can update where id or username or any field

```php
<?php
$newValues  [
	'name' => 'Ahmed',
];
$db->->table('users')->update($newValues, ['id', '=', 1]);
```
####OR
```php
<?php
$newValues  array(
	'name' => 'Ahmed',
);
$db->table('users')->update($newValues, ['username', '=', 'Mohammad']);
```

####Note
You can update more than one field in the same array
```php
$newValues  [
	'name' => 'Ahmed',
	'username' => 'plapla',
	'email' => 'pla@plalpa.com',
	...
];

$db->table('users')->update($newValues, ['username', '=', 'Mohammad']);
```
update method look like this
update->table('tablename')->update($newVaules = [], $whereCondition = []);

* $newVaules = [field name  , new value]
* $whereCondition = [field name  , operator  ,  value]

###delete
```php
<?php
$db->table('users')->delete(['id', '>=', 1]);
```
you can set where condition same update 
* delete('users', [field name  , operator  ,  value]);
```php
<?php
$db->table('users')->delete(['name', 'LIKE', 'mohammad)]);
```
========================

### Note
You Can Insert , Update, Delete uses query method
```php
<?php
$db->query("INSERT INTO users (name) VALUES (?)", ['mohammad']);

$db->query("UPDATE  users SET (name =?) WHERE id=1", ['AIi']);
```

## Get First X number Rows
methode getFirst accept 3 parameters 
1- rows number (required) by default 10 if kept empty
2- where condition (optional)
```php
<?php

$db->table('table_name')->getFirst(5, $where);
```

## Get Last X number Rows
methode getLast accept 3 parameters 
1- rows number (required) by default 10 if kept empty
2- where condition (optional)
```php
<?php

$db->table('table_name')->getLast(5, $where);
```
=============

## How to user returned data
```php
$allUsers = $db->query("SELECT * FROM table_name")->results();

foreach($allUsers as $singleUser) {
	echo $singleUser->name;
	echo "<br>";
	echo $singleUser->username;
}

// name and username in example are fields from table
```
if you want to featch as array 
go to line 102 replace PDO::FETCH_OBJ ->  to -> PDO::FETCH_ASSOC
## To Get Rows Count
$count = $db->count();
echo $count;
## To Show if there any errors
```php
$error = $db->error();
if(!$error) {
	echo "No Errors";
} else {
	echo "There is error";
}
```
####NOTE:
all methods return false if any error happen and true if all thing allright . except query if no error return an array
so you can do something like this:
```php
<?php 
// delete
if($db->table('users')->delete(['id', '>=', 1])) {
	echo "Deleted Successfully";
} else {
	echo "error Delete";
}

// insert
if($db->table('users')->insert(['name' => 'pla pla'])) {
	echo "Inserted Successfully";
} else {
	echo "error Insert";
}

// update 
if($db->table('users')->update(['name' => 'pla pla'])) {
	echo "Updated Successfully";
} else {
	echo "error Update";
}

// get data
if($users = $db->table('users')->query("select * from users")->results()) {
	print_r($users);
} else {
	echo "error Select From table";
```


# NEW : Data Definition Language (DDL) :

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
#ADD Column :
```php
$db->table('target_table')->alterCchema('condetions is array')->alter();
$db->table('table')->alterSchema(['add', 'column_name', 'type'])->alter();
```
####EX:
```php
$db->table('users')->alterSchema(['add', 'last_login', 'date'])->alter();
```

#RENAME Column :
```php
$db->table('target_table')->alterCchema('condetions is array')->alter();
$db->table('table')->alterSchema(['rename', 'column_name', 'new_column_name' ,'type'])->alter();
```
####EX:
```php
$db->table('users')->alterSchema(['rename', 'last_login', 'last_session', 'date'])->alter();
```

#EDITE Column  type:
```php
$db->table('table')->alterSchema(['modify', 'column_name', 'new_type'])->alter();
```
####EX:
```php
$db->table('users')->alterSchema(['modify', 'full_name', 'text'])->alter();
```

#DROP Column :
```php
$db->table('table')->alterSchema(['drop', 'column_name'])->alter();
```
####EX:
```php
$db->table('users')->alterSchema(['drop', 'full_name'])->alter();
```

###THATS IT :) 

###I HOPE THIE HELP YOU.

=============================
#Change Log

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
*FIX first method -> to compatible with PHP V 5.3.*

#### 1.0.0
*First Release


=============================
#License
### No License For This Class You are Free To Use it :)




