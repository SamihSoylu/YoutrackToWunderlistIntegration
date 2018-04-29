<?php

# PHP Settings
ini_set('display_errors', 'On');
error_reporting(E_ALL);
date_default_timezone_set('Europe/Amsterdam');

# Load composer packages
require_once(dirname(__FILE__)."/vendor/autoload.php");

# Loads environment variables from .env
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

# Sets the followng variables as required
$dotenv->required(['YT_URL', 'YT_USERNAME', 'YT_PASSWORD']);
