<?php

/**
 * This file is part of lookyman/ReCaptcha (https://github.com/lookyman/recaptcha)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace lookyman\ReCaptcha\Client;

use GuzzleHttp\Client;
use Nette\SmartObject;

/**
 * Guzzle client implementation of IClient interface.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class GuzzleClient implements IClient
{

	use SmartObject;

	/** @var \GuzzleHttp\Client */
	private $client;

	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validate($uri, $secret, $response, $remoteIp = NULL)
	{
		$params = [
			'secret' => $secret,
			'response' => $response,
		];
		if ($remoteIp !== NULL) {
			$params['remoteip'] = $remoteIp;
		}
		return $this->client->post($uri, ['query' => $params]);
	}

}
