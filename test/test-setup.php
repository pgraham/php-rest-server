<?php
/*
 * Copyright (c) 2014, Philip Graham
 * All rights reserved.
 *
 * This file is part of php-rest-server. For the full copyright and license
 * information please view the LICENSE file that was distributed with this
 * source code.
 */

$vendorDir = dirname(__DIR__);
while (!file_exists($vendorDir . '/vendor')) {
	$vendorDir = dirname($vendorDir);
}

if (!file_exists($vendorDir . '/vendor')) {
	echo "Unable to find Composer dependencies\n";
	exit(1);
}

$loader = require_once "$vendorDir/vendor/autoload.php";
