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

	/** @var \lookyman\ReCaptcha\Configuration */
	private $configuration;

	/** @var \lookyman\ReCaptcha\Client\IClient */
	private $client;

	/** @var \Nette\Http\IRequest */
	private $request;

	public function __construct(Configuration $configuration, IClient $client, IRequest $request)
	{
		$this->configuration = $configuration;
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
				$this->configuration->getVerificationUrl(),
				$this->configuration->getSecretKey(),
				$control->getValue(),
				$this->configuration->getValidateRemoteIp()
					? $this->request->getRemoteAddress()
					: NULL
			);

		} catch (\Exception $e) {
			throw new ClientException('There was an error while contacting the verification API.', NULL, $e);
		}

		$code = $response->getStatusCode();
		if ($code !== 200) {
			throw new BadResponseException('The verification API did not responded correctly.', $code);
		}
		$answer = Json::decode($response->getBody()->getContents());
		return isset($answer->success) && $answer->success === TRUE;
	}

}
