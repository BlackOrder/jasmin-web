<?php

use JasminWeb\Jasmin\Command\SmppConnector\Connector as JasminSmppConnector;
use JasminWeb\Jasmin\Connection\Session as JasminSession;
use JasminWeb\Jasmin\Connection\SocketConnection as JasminConnector;


$J_connection = JasminConnector::init('127.0.0.1', 8990, 500000);
$J_session = JasminSession::init('jcliadmin', 'jclipwd', $J_connection);
$manager = new JasminSmppConnector($J_session);

// Show all SmppConnectors
$manager->all();

// Create a new SmppConnector
$errors = '';
$manager->add([
  'cid' => 'new_smppConnector_id',
], $errors);

// Show a SmppConnector
$manager->show('some_smppConnector_id');

// Disable a SmppConnector
$manager->disable('some_smppConnector_id');

// Enable a SmppConnector
$manager->enable('some_smppConnector_id');

// Remove a SmppConnector
$manager->remove('some_smppConnector_id');
