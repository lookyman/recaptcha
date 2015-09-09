<?php

/**
 * This file is part of lookyman/ReCaptcha (https://github.com/lookyman/recaptcha)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace lookyman\ReCaptcha\Tests;

use lookyman\ReCaptcha\Client\IClient;
use lookyman\ReCaptcha\Config;
use lookyman\ReCaptcha\Forms\Controls\ReCaptchaControl;
use lookyman\ReCaptcha\Validator;
use Nette\Http\IRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Test \lookyman\ReCaptcha\Validator.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase
{

	public function testValidateControl()
	{
		$config = $this->getMockBuilder(Config::class)
			->disableOriginalConstructor()
			->getMock();
		$config->expects($this->once())
			->method('getVerificationUrl')
			->will($this->returnValue('a'));
		$config->expects($this->once())
			->method('getSecretKey')
			->will($this->returnValue('b'));
		$config->expects($this->once())
			->method('getValidateRemoteIp')
			->will($this->returnValue(TRUE));

		$stream = $this->getMockBuilder(StreamInterface::class)
			->disableOriginalConstructor()
			->getMock();
		$stream->expects($this->once())
			->method('getContents')
			->will($this->returnValue('{"success":true}'));

		$response = $this->getMockBuilder(ResponseInterface::class)
			->disableOriginalConstructor()
			->getMock();
		$response->expects($this->once())
			->method('getStatusCode')
			->will($this->returnValue(200));
		$response->expects($this->once())
			->method('getBody')
			->will($this->returnValue($stream));

		$client = $this->getMockBuilder(IClient::class)
			->disableOriginalConstructor()
			->getMock();
		$client->expects($this->once())
			->method('validate')
			->with('a', 'b', 'c', 'd')
			->will($this->returnValue($response));

		$request = $this->getMockBuilder(IRequest::class)
			->disableOriginalConstructor()
			->getMock();
		$request->expects($this->once())
			->method('getRemoteAddress')
			->will($this->returnValue('d'));

		$validator = new Validator($config, $client, $request);

		$unfilled = $this->getMockBuilder(ReCaptchaControl::class)
			->disableOriginalConstructor()
			->getMock();
		$unfilled->expects($this->once())
			->method('isFilled')
			->will($this->returnValue(FALSE));

		$this->assertFalse($validator->validateControl($unfilled));

		$control = $this->getMockBuilder(ReCaptchaControl::class)
			->disableOriginalConstructor()
			->getMock();
		$control->expects($this->once())
			->method('isFilled')
			->will($this->returnValue(TRUE));
		$control->expects($this->once())
			->method('getValue')
			->will($this->returnValue('c'));

		$this->assertTrue($validator->validateControl($control));
	}

}
