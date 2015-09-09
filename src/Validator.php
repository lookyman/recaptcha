<?php

/**
 * This file is part of lookyman/ReCaptcha (https://github.com/lookyman/recaptcha)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace lookyman\ReCaptcha;

use lookyman\ReCaptcha\Client\IClient;
use lookyman\ReCaptcha\Exception\BadResponseException;
use lookyman\ReCaptcha\Exception\ClientException;
use lookyman\ReCaptcha\Forms\Controls\ReCaptchaControl;
use Nette\Http\IRequest;
use Nette\Object;
use Nette\Utils\Json;

/**
 * Validates ReCaptchaControl.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class Validator extends Object
{

	/** @var \lookyman\ReCaptcha\Config */
	private $config;

	/** @var \lookyman\ReCaptcha\Client\IClient */
	private $client;

	/** @var \Nette\Http\IRequest */
	private $request;

	public function __construct(Config $config, IClient $client, IRequest $request)
	{
		$this->config = $config;
		$this->client = $client;
		$this->request = $request;
	}

	/**
	 * @return bool
	 * @throws \lookyman\ReCaptcha\Exception\ClientException
	 * @throws \lookyman\ReCaptcha\Exception\BadResponseException
	 */
	public function validateControl(ReCaptchaControl $control)
	{
		if (!$control->isFilled()) {
			return FALSE;
		}

		try {
			$response = $this->client->validate(
				$this->config->getVerificationUrl(),
				$this->config->getSecretKey(),
				$control->getValue(),
				$this->config->getValidateRemoteIp()
					? $this->request->getRemoteAddress()
					: NULL
			);

		} catch (\Exception $e) {
			throw new ClientException('There was an error while contacting the verification API.', NULL, $e);
		}

		$code = $response->getStatusCode();
		if ($code !== 200) {
			throw new BadResponseException('The verification API did not respond correctly.', $code);
		}
		$answer = Json::decode($response->getBody()->getContents());
		return isset($answer->success) && $answer->success === TRUE;
	}

}
