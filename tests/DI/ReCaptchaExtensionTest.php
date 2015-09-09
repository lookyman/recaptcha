<?php

/**
 * This file is part of lookyman/ReCaptcha (https://github.com/lookyman/recaptcha)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace lookyman\ReCaptcha\Tests\DI;

use lookyman\ReCaptcha\Config;
use lookyman\ReCaptcha\Client\GuzzleClient;
use lookyman\ReCaptcha\Client\IClient;
use lookyman\ReCaptcha\DI\ReCaptchaExtension;
use lookyman\ReCaptcha\Validator;
use GuzzleHttp\Client;
use Nette\DI\Compiler;
use Nette\DI\Config\Helpers;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nette\InvalidStateException;
use Nette\PhpGenerator\ClassType;
use Nette\Utils\AssertionException;

/**
 * Test \lookyman\ReCaptcha\DI\ReCaptchaExtension.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class ReCaptchaExtensionTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @dataProvider disabledAutowiringDataProvider
	 * @expectedException \Nette\DI\MissingServiceException
	 */
	public function testDisabledAutowiring($class)
	{
		$this->createContainer($this->getDefaultConfig())
			->getByType($class);
	}

	public function disabledAutowiringDataProvider()
	{
		return [
			[Config::class],
			[IClient::class],
			[Validator::class],
		];
	}

	public function testServices()
	{
		$container = $this->createContainer(Helpers::merge($this->getDefaultConfig(), [
			'recaptcha' => [
				'siteKey' => 'a',
				'secretKey' => 'b',
				'verificationUrl' => 'c',
				'errorMessage' => 'd',
				'client' => [],
			],
		]));

		$this->assertInstanceOf(Config::class, $config = $container->getService('recaptcha.config'));

		$this->assertSame('a', $config->getSiteKey());
		$this->assertSame('b', $config->getSecretKey());
		$this->assertSame('c', $config->getVerificationUrl());
		$this->assertSame('d', $config->getErrorMessage());

		$this->assertInstanceOf(GuzzleClient::class, $client = $container->getService('recaptcha.client'));

		$ref = new \ReflectionClass($client);
		$prop = $ref->getProperty('client');
		$prop->setAccessible(TRUE);
		$this->assertInstanceOf(Client::class, $prop->getValue($client));

		$this->assertInstanceOf(Validator::class, $validator = $container->getService('recaptcha.validator'));

		$ref = new \ReflectionClass($validator);
		$prop = $ref->getProperty('config');
		$prop->setAccessible(TRUE);
		$this->assertSame($config, $prop->getValue($validator));

		$ref = new \ReflectionClass($validator);
		$prop = $ref->getProperty('client');
		$prop->setAccessible(TRUE);
		$this->assertSame($client, $prop->getValue($validator));
	}

	/**
	 * @dataProvider misconfigurationDataProvider
	 */
	public function testMisconfiguration($config, $exceptionClass, $exceptionMessage)
	{
		$this->setExpectedException($exceptionClass, $exceptionMessage);
		$container = $this->createContainer(Helpers::merge($this->getDefaultConfig(), $config));
	}

	public function misconfigurationDataProvider()
	{
		return [
			[
				['recaptcha' => ['extra' => TRUE]],
				InvalidStateException::class,
				'Unknown configuration option recaptcha.extra.',
			],
			[
				['recaptcha' => ['siteKey' => TRUE]],
				AssertionException::class,
				'The item \'siteKey\' in array expects to be string, boolean given.',
			],
			[
				['recaptcha' => ['secretKey' => TRUE]],
				AssertionException::class,
				'The item \'secretKey\' in array expects to be string, boolean given.',
			],
			[
				['recaptcha' => ['verificationUrl' => TRUE]],
				AssertionException::class,
				'The item \'verificationUrl\' in array expects to be string, boolean given.',
			],
			[
				['recaptcha' => ['errorMessage' => TRUE]],
				AssertionException::class,
				'The item \'errorMessage\' in array expects to be string, boolean given.',
			],
			[
				['recaptcha' => ['validateRemoteIp' => 1]],
				AssertionException::class,
				'The item \'validateRemoteIp\' in array expects to be bool, integer given.',
			],
		];
	}

	public function testAfterCompile()
	{
		$class = ClassType::from(Container::class);
		$initialize = $class->addMethod('initialize');

		(new ReCaptchaExtension)
			->setCompiler(new Compiler, 'recaptcha')
			->afterCompile($class);

		$this->assertSame('$self = $this;
Nette\Forms\Container::extensionMethod(\'addReCaptcha\', function (Nette\Forms\Container $container, $name, $label = NULL) use ($self) {
	$container[$name] = new lookyman\ReCaptcha\Forms\Controls\ReCaptchaControl($self->getService(\'recaptcha.config\')->getSiteKey(), $label);
	$container[$name]->addRule([$self->getService(\'recaptcha.validator\'), \'validateControl\'], $self->getService(\'recaptcha.config\')->getErrorMessage());
	return $container[$name];
});
', $initialize->getBody());
	}

	/**
	 * @param array $config
	 * @return \Nette\DI\Container
	 */
	private function createContainer(array $config)
	{
		$loader = new ContainerLoader(TEMP_DIR, TRUE);
		$class = $loader->load($config, function (Compiler $compiler) use ($config) {
			$compiler->addExtension('recaptcha', new ReCaptchaExtension);
			$compiler->addConfig($config);
		});
		$container = new $class;
		$container->initialize();
		return $container;
	}

	/**
	 * @return array
	 */
	private function getDefaultConfig()
	{
		return [
			'services' => [
				'lookyman\ReCaptcha\Tests\Mock\HttpRequest',
			],
		];
	}

}
