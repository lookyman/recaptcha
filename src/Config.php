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

	/**
	 * @param string $siteKey
	 * @param string $secretKey
	 * @param string $verificationUrl
	 * @param string $errorMessage
	 * @param bool $validateRemoteIp
	 */
	public function __construct($siteKey, $secretKey, $verificationUrl, $errorMessage, $validateRemoteIp)
	{
		Validators::assert($siteKey, 'string', 'siteKey');
		Validators::assert($secretKey, 'string', 'secretKey');
		Validators::assert($verificationUrl, 'string', 'verificationUrl');
		Validators::assert($errorMessage, 'string', 'errorMessage');
		Validators::assert($validateRemoteIp, 'bool', 'validateRemoteIp');

		$this->siteKey = $siteKey;
		$this->secretKey = $secretKey;
		$this->verificationUrl = $verificationUrl;
		$this->errorMessage = $errorMessage;
		$this->validateRemoteIp = $validateRemoteIp;
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

}
