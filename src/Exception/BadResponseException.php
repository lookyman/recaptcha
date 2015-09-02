<?php

/**
 * This file is part of lookyman/ReCaptcha (https://github.com/lookyman/recaptcha)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace lookyman\ReCaptcha\Exception;

/**
 * Thrown when the API call returns a non-200 code.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class BadResponseException extends \RuntimeException implements IException
{

}
