<?php

/**
 * This file is part of lookyman/ReCaptcha (https://github.com/lookyman/recaptcha)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace lookyman\ReCaptcha\Client;

/**
 * Client interface.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
interface IClient
{

	/**
	 * @param string $uri
	 * @param string $secret
	 * @param string $response
	 * @param string|NULL $remoteIp
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	function validate($uri, $secret, $response, $remoteIp = NULL);

}
