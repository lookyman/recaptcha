<?php

/**
 * This file is part of lookyman/ReCaptcha (https://github.com/lookyman/recaptcha)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace lookyman\ReCaptcha\DI;

use lookyman\ReCaptcha\Config;
use lookyman\ReCaptcha\Client\GuzzleClient;
use lookyman\ReCaptcha\Client\IClient;
use lookyman\ReCaptcha\Validator;
use GuzzleHttp\Client;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;
use Nette\PhpGenerator\ClassType;
use Nette\Utils\Validators;

/**
 * ReCaptcha Nette Framework extension.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class ReCaptchaExtension extends CompilerExtension
{
	/* Themes */
	const DARK = "dark";
	const LIGHT = "light";

	/* Type */
	const AUDIO = "audio";
	const IMAGE = "image";

	/* Size */
	const COMPACT = "compact";
	const NORMAL = "normal";


	/** @var array */
	public $defaults = [
		'siteKey' => '',
		'secretKey' => '',
		'verificationUrl' => 'https://www.google.com/recaptcha/api/siteverify',
		'errorMessage' => 'You appear to be a bot',
		'validateRemoteIp' => FALSE,
		'client' => [],
		'theme' => self::LIGHT,
		'type' => self::IMAGE,
		'size' => self::NORMAL,
	];

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		Validators::assertField($config, 'siteKey', 'string');
		Validators::assertField($config, 'secretKey', 'string');
		Validators::assertField($config, 'verificationUrl', 'string');
		Validators::assertField($config, 'errorMessage', 'string');
		Validators::assertField($config, 'validateRemoteIp', 'bool');
		Validators::assertField($config, 'theme', 'string');
		Validators::assertField($config, 'type', 'string');
		Validators::assertField($config, 'size', 'string');

		$builder->addDefinition($this->prefix('config'))
			->setClass(Config::class, [
				$config['siteKey'],
				$config['secretKey'],
				$config['verificationUrl'],
				$config['errorMessage'],
				$config['validateRemoteIp'],
				$config['theme'],
				$config['type'],
				$config['size'],
			])
			->setAutowired(FALSE);

		Validators::assertField($config, 'client', 'array');

		$builder->addDefinition($this->prefix('client'))
			->setClass(IClient::class)
			->setFactory(GuzzleClient::class, [new Statement(Client::class, [$config['client']])])
			->setAutowired(FALSE);

		$builder->addDefinition($this->prefix('validator'))
			->setClass(Validator::class, [$this->prefix('@config'), $this->prefix('@client')])
			->setAutowired(FALSE);
	}

	public function afterCompile(ClassType $class)
	{
		$initialize = $class->methods['initialize'];
		$initialize->addBody('$self = $this;
Nette\Forms\Container::extensionMethod(\'addReCaptcha\', function (Nette\Forms\Container $container, $name, $label = NULL) use ($self) {
	$conf = $self->getService(?);
	$container[$name] = new lookyman\ReCaptcha\Forms\Controls\ReCaptchaControl($conf->getSiteKey(), $conf->getTheme(), $conf->getType(), $conf->getSize(), $label);
	$container[$name]->addRule([$self->getService(?), \'validateControl\'], $conf->getErrorMessage());
	return $container[$name];
});',
			[$this->prefix('config'), $this->prefix('validator')]
		);
	}

}
