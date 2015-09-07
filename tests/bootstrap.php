<?php

/**
 * This file is part of lookyman/ReCaptcha (https://github.com/lookyman/recaptcha)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

use Nette\Caching\Storages\DevNullStorage;
use Nette\Loaders\RobotLoader;

require_once __DIR__ . '/../vendor/autoload.php';

$loader = new RobotLoader;
$loader->autoRebuild = true;
$loader->setCacheStorage(new DevNullStorage)
	->addDirectory(__DIR__)
	->register();

define('TEMP_DIR', __DIR__ . '/tmp');

call_user_func(function ($dir) {
	if (!is_dir($dir)) {
		mkdir($dir);
	}
	foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $entry) {
		$entry->isDir() ? rmdir($entry) : unlink($entry);
	}
}, TEMP_DIR);
