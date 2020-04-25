<?php
require_once 'core/model.php';
require_once 'core/view.php';
require_once 'core/controller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/mail.php';
require_once 'core/route.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
Route::start();
