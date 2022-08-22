<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env');

$application = new Symfony\Component\Console\Application;

$client = HttpClient::create();

$application->add(new App\Command\ListUsers($client));
$application->add(new App\Command\GetUser($client));
$application->add(new App\Command\CreateUser($client));
$application->add(new App\Command\UpdateUser($client));
$application->add(new App\Command\DeleteUser($client));

$application->add(new App\Command\ListUserGroups($client));
$application->add(new App\Command\GetUserGroup($client));
$application->add(new App\Command\CreateUserGroup($client));
$application->add(new App\Command\UpdateUserGroup($client));
$application->add(new App\Command\DeleteUserGroup($client));

$application->add(new App\Command\ListGroupUsers($client));

$application->run();