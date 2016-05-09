<?php

/**
 * This file is part of lookyman/ReCaptcha (https://github.com/lookyman/recaptcha)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace lookyman\ReCaptcha;

use Nette\Object;
use Nette\Utils\Validators;

/**
 * Data object for holding ReCaptcha settings.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class Config extends Object
{

	/** @var string */
	private $siteKey;

	/** @var string */
	private $secretKey;

	/** @var string */
	private $verificationUrl;

	/** @var string */
	private $errorMessage;

	/** @var bool */
	private $validateRemoteIp;

	/** @var string */
	private $theme;

	/** @var string */
	private $type;

	/** @var string */
	private $size;

	/**
	 * @param string $siteKey
	 * @param string $secretKey
	 * @param string $verificationUrl
	 * @param string $errorMessage
	 * @param bool $validateRemoteIp
	 * @param string $theme
	 * @param string $type
	 * @param string $size
	 */
	public function __construct($siteKey, $secretKey, $verificationUrl, $errorMessage, $validateRemoteIp, $theme, $type, $size)
	{
		Validators::assert($siteKey, 'string', 'siteKey');
		Validators::assert($secretKey, 'string', 'secretKey');
		Validators::assert($verificationUrl, 'string', 'verificationUrl');
		Validators::assert($errorMessage, 'string', 'errorMessage');
		Validators::assert($validateRemoteIp, 'bool', 'validateRemoteIp');
		Validators::assert($theme, 'string', 'theme');
		Validators::assert($type, 'string', 'type');
		Validators::assert($size, 'string', 'size');

		$this->siteKey = $siteKey;
		$this->secretKey = $secretKey;
		$this->verificationUrl = $verificationUrl;
		$this->errorMessage = $errorMessage;
		$this->validateRemoteIp = $validateRemoteIp;
		$this->theme = $theme;
		$this->type = $type;
		$this->size = $size;
	}

	/**
	 * @return string
	 */
	public function getSiteKey()
	{
		return $this->siteKey;
	}

	/**
	 * @return string
	 */
	public function getSecretKey()
	{
		return $this->secretKey;
	}

	/**
	 * @return string
	 */
	public function getVerificationUrl()
	{
		return $this->verificationUrl;
	}

	/**
	 * @return string
	 */
	public function getErrorMessage()
	{
		return $this->errorMessage;
	}

	/**
	 * @return bool
	 */
	public function getValidateRemoteIp()
	{
		return $this->validateRemoteIp;
	}

	/**
	 * @return string
	 */
	public function getTheme()
	{
		return $this->theme;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getSize()
	{
		return $this->size;
	}

}
