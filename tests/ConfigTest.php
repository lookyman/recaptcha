<?php

/**
 * This file is part of lookyman/ReCaptcha (https://github.com/lookyman/recaptcha)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace lookyman\ReCaptcha\Tests;

use lookyman\ReCaptcha\Config;
use Nette\Utils\AssertionException;

/**
 * Test \lookyman\ReCaptcha\Config.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class ConfigTest extends \PHPUnit_Framework_TestCase
{

	public function testGetSet()
	{
		$config = new Config('a', 'b', 'c', 'd', TRUE);
		$this->assertSame('a', $config->getSiteKey());
		$this->assertSame('b', $config->getSecretKey());
		$this->assertSame('c', $config->getVerificationUrl());
		$this->assertSame('d', $config->getErrorMessage());
		$this->assertSame(TRUE, $config->getValidateRemoteIp());
	}

	/**
	 * @dataProvider invalidArgumentsDataProvider
	 */
	public function testInvalidArguments($siteKey, $secretKey, $verificationUrl, $errorMessage, $validateRemoteIp, $exceptionClass, $exceptionMessage)
	{
		$this->setExpectedException($exceptionClass, $exceptionMessage);
		new Config($siteKey, $secretKey, $verificationUrl, $errorMessage, $validateRemoteIp);
	}

	public function invalidArgumentsDataProvider()
	{
		return [
			[TRUE, '', '', '', TRUE, AssertionException::class, 'The siteKey expects to be string, boolean given.'],
			['', TRUE, '', '', TRUE, AssertionException::class, 'The secretKey expects to be string, boolean given.'],
			['', '', TRUE, '', TRUE, AssertionException::class, 'The verificationUrl expects to be string, boolean given.'],
			['', '', '', TRUE, TRUE, AssertionException::class, 'The errorMessage expects to be string, boolean given.'],
			['', '', '', '', 1, AssertionException::class, 'The validateRemoteIp expects to be bool, integer given.'],
		];
	}

}
