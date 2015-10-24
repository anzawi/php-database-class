PDO Database Class
============================

A database class for PHP-MySQL which uses the PDO extension.
* Allows one connection with the database and deny duplicate connection, 
* this speeds up to use the database and reduces the load on the server.
If you have any questions go to : http://www.phptricks.org/PDO-class

#### To Arabic go to : http://www.phptricks.org/PDO-class  : للشرح باللغة العربية توجه الى

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
$userInformation = array(
	'id'       => 1,
	'name'     => 'Mohammad',
	'username' => 'Anzawi',
	'email'    => 'email@example.com',
);

$db->insert('users', $userInformation );
```

#### Note
* $userInformation = array( field name  , value )

###update
suppose you want to update name for Mohammad

* you can update where id or username or any field

```php
<?php
$newValues  array(
	'name' => 'Ahmed',
);
$db->update('users', $newValues, array('id', '=', 1));
```
####OR
```php
<?php
$newValues  array(
	'name' => 'Ahmed',
);
$db->update('users', $newValues, array('username', '=', 'Mohammad'));
```

####Note
You can update more than one field in the same array
```php
$newValues  array(
	'name' => 'Ahmed',
	'username' => 'plapla',
	'email' => 'pla@plalpa.com',
	...
);
$db->update('users', $newValues, array('username', '=', 'Mohammad'));
```
update method look like this
update('tablename', $newVaules =array(), $whereCondition = array());

* $newVaules = array( field name  , new value )
* $whereCondition = array( field name  , operator  ,  value)

###delete
```php
<?php
$db->delete('users', array('id', '>=', 1));
```
you can set where condition same update 
* delete('users', array( field name  , operator  ,  value));
```php
<?php
$db->delete('users', array('name', 'LIKE', 'mohammad));
```
========================

### Note
You Can Insert , Update, Delete uses query method
```php
<?php
$db->query("INSERT INTO users (name) VALUES (?)", array('mohammad'));

$db->query("UPDATE  users SET (name =?) WHERE id=1", array('AIi'));
```

## Get First X number Rows
methode getFirst accept 3 parameters 
1- table name (required)
2- rows number (required) by default 10 if kept empty
3- where condition (optional)
```php
<?php

$db->getFirst('table_name', 5, $where);
```

## Get Last X number Rows
methode getLast accept 3 parameters 
1- table name (required)
2- rows number (required) by default 10 if kept empty
3- where condition (optional)
```php
<?php

$db->getLast('table_name', 5, $where);
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
go to line 99 replace PDO::FETCH_OBJ ->  to -> PDO::FETCH_ASSOC
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
if($db->delete('users', array('id', '>=', 1))) {
	echo "Deleted Successfully";
} else {
	echo "error Delete";
}

// insert
if($db->insert('users', array('name' => 'pla pla'))) {
	echo "Inserted Successfully";
} else {
	echo "error Insert";
}

// update 
if($db->update('users', array('name' => 'pla pla'))) {
	echo "Updated Successfully";
} else {
	echo "error Update";
}

// get data
if($users = $db->query('users', "select * from users")->results()) {
	print_r($users);
} else {
	echo "error Select From table";
```
=============================
#License
### No License For This Class You are Free To Use it :)




