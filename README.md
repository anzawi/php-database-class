PDO Database Class
============================

A database class for PHP-MySQL which uses the PDO extension.

If you have any questions go to : http://www.t3lam.net/PDO-class
#### To Arabic go to : http://www.t3lam.net/PDO-class  : للشرح باللغة العربية توجه الى
##To use the class
###declare constant variables

```php
<?php
define(HOST,     'localhost');
define(DBNAME,   'your_database_name');
define(USERNAME, 'database_username');
define(PASSWORD, 'database_password');
define(CHARSET,  'charst'); // utf8 RECOMMENDED
```
### Require the class in your project
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
$db->query("SELECT * FROM table_name WHERE field = value)->first();
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
========================

### Note
You Can Insert , Update, Delete uses query method
```php
<?php
$db->query("INSERT INTO users (name) VALUES (?)", array('mohammad'));

$db->query("UPDATE  users SET (name =?) WHERE id=1", array('AIi'));
```
=============

## How to user returned data
```php
$allUsers = $db->query("SELECT * FROM table_name)-results();

foreach($allUsers as $singleUser) {
	echo $singleUser->name;
	echo "<br>";
	echo $singleUser->username;
}

// name and username in example are fields from table
```
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
=============================
#License
### No License For This Class You are Free To Use it :)




