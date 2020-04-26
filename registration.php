<?php
require_once 'classes/TPL.php';

session_start();

TPL::getInstance()->display('registration.tpl');