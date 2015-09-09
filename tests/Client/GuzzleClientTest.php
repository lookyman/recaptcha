<?php

/**
 * This file is part of lookyman/ReCaptcha (https://github.com/lookyman/recaptcha)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace lookyman\ReCaptcha\Tests\Client;

use lookyman\ReCaptcha\Client\GuzzleClient;
use GuzzleHttp\Client;

/**
 * Test \lookyman\ReCaptcha\Client\GuzzleClient.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class GuzzleClientTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @dataProvider validateDataProvider
	 */
	public function testValidate(array $args, array $data)
	{
		$client = $this->getMock(Client::class);
		$client->expects($this->once())
			->method('__call')
			->with('post', $data)
			->will($this->returnValue(TRUE));

		$this->assertTrue(call_user_func_array([new GuzzleClient($client), 'validate'], $args));
	}

	public function validateDataProvider()
	{
		return [
			[
				['a', 'b', 'c'],
				['a', ['query' => ['secret' => 'b', 'response' => 'c']]],
			],
			[
				['d', 'e', 'f', 'g'],
				['d', ['query' => ['secret' => 'e', 'response' => 'f', 'remoteip' => 'g']]],
			],
		];
	}

}
