<?php
require 'core' . DIRECTORY_SEPARATOR . 'factory' . DIRECTORY_SEPARATOR . 'DataFactory.php';
require 'core' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'DataHandler.php';

//set data
$instance = new App\Core\Classes\DataHandler($_FILES['file'], $_POST);
