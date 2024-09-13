<?php
require 'vendor/autoload.php';

$openapi = \OpenApi\Generator::scan(['D:\XAMP\htdocs\FAQS']);
file_put_contents('./swagger.json', $openapi->toJson());
