<?php

use JasminWeb\Jasmin\Command\Filter\Filter as JasminFilter;
use JasminWeb\Jasmin\Connection\Session as JasminSession;
use JasminWeb\Jasmin\Connection\SocketConnection as JasminConnector;


$J_connection = JasminConnector::init('127.0.0.1', 8990, 500000);
$J_session = JasminSession::init('jcliadmin', 'jclipwd', $J_connection);
$manager = new JasminFilter($J_session);

// Show all Filters
$manager->all();

// Create a new Filter
$errors = '';
$manager->add([
  'fid' => 'new_filter_id',
  'type' => 'GroupFilter',
  'gid' => 'some_group_id',
], $errors);

// Remove a Filter
$manager->remove('some_filter_id');
