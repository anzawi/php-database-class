<?php

include 'vendor/autoload.php';

use PHPtricks\Orm\Database;
$db = Database::connect();

$users = $db->table('users')->where('username', 'Mohammad Walid AL-Anzawi')->parseWhere(['active', '=', 1])->select();

var_dump($users->results());