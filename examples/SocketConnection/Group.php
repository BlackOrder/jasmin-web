<?php

use JasminWeb\Jasmin\Command\Group\Group as JasminGroup;
use JasminWeb\Jasmin\Connection\Session as JasminSession;
use JasminWeb\Jasmin\Connection\SocketConnection as JasminConnector;


$J_connection = JasminConnector::init('127.0.0.1', 8990, 500000);
$J_session = JasminSession::init('jcliadmin', 'jclipwd', $J_connection);
$manager = new JasminGroup($J_session);

// Show all Groups
$manager->all();

// Create a new Group
$errors = '';
$manager->add([
  'gid' => 'new_group_id',
], $errors);

// Disable a Group
$manager->disable('some_group_id');

// Enable a Group
$manager->enable('some_group_id');

// Remove a Group
$manager->remove('some_group_id');
