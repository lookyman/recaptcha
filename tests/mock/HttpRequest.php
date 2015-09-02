<?php

/**
 * This file is part of lookyman/ReCaptcha (https://github.com/lookyman/recaptcha)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace lookyman\ReCaptcha\Tests\Mock;

use Nette\Http\IRequest;

/**
 * Mock IRequest implementation for use as a service in ReCaptchaExtension test.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class HttpRequest implements IRequest
{

	public function getCookie($key, $default = NULL) {}
	public function getCookies() {}
	public function getFile($key) {}
	public function getFiles() {}
	public function getHeader($header, $default = NULL) {}
	public function getHeaders() {}
	public function getMethod() {}
	public function getPost($key = NULL, $default = NULL) {}
	public function getQuery($key = NULL, $default = NULL) {}
	public function getRawBody() {}
	public function getRemoteAddress() {}
	public function getRemoteHost() {}
	public function getUrl() {}
	public function isAjax() {}
	public function isMethod($method) {}
	public function isSecured() {}

}
