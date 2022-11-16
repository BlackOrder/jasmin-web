<?php

use JasminWeb\Jasmin\Command\User\User as JasminUser;
use JasminWeb\Jasmin\Connection\Session as JasminSession;
use JasminWeb\Jasmin\Connection\SocketConnection as JasminConnector;


$J_connection = JasminConnector::init('127.0.0.1', 8990, 500000);
$J_session = JasminSession::init('jcliadmin', 'jclipwd', $J_connection);
$manager = new JasminUser($J_session);

// Show all Users
$manager->all();

// Create a new User
$errors = '';
$manager->add([
  'uid' => 'new_user_id',
  'gid' => 'some_group_id',
  'username' => 'new_user_username',
  'password' => 'new_user_password',
], $errors);

// Show a User
$manager->show('some_user_id');

// Disable a User
$manager->disable('some_user_id');

// Enable a User
$manager->enable('some_user_id');

// Remove a User
$manager->remove('some_user_id');
