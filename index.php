<?php
/*Načtení konfigurace*/
require_once './config/config.php';
/*Načtení databáze*/
require_once './core/database.php';
/*Načtení jádra*/
require_once './core/uri.php';
require_once './core/base_model.php';
require_once './models/application_model.php';
require_once './core/my_controller.php';
require_once './controllers/application.php';

$uri = new uri();
$uri->routing();



