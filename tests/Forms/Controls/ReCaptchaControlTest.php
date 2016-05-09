<?php

/**
 * This file is part of lookyman/ReCaptcha (https://github.com/lookyman/recaptcha)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace lookyman\ReCaptcha\Tests\Forms\Controls;

use lookyman\ReCaptcha\Forms\Controls\ReCaptchaControl;
use Nette\Forms\Form;

/**
 * Test \lookyman\ReCaptcha\Forms\Controls\ReCaptchaControl.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class ReCaptchaControlTest extends \PHPUnit_Framework_TestCase
{

	public function testGetControl()
	{
		$form = new Form;
		$form['recaptcha'] = new ReCaptchaControl('a', 'b', 'c', 'd');
		$this->assertSame('<div class="g-recaptcha" data-sitekey="a" data-theme="b" data-type="c" data-size="d" name="recaptcha" id="frm-recaptcha"></div>', (string) $form['recaptcha']->getControl());
	}

	public function testLoadHttpData()
	{
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_POST = ['g-recaptcha-response' => 'a'];
		$_FILES = [];

		$form = new Form;
		$form['recaptcha'] = new ReCaptchaControl('', '', '', '');
		$this->assertSame('a', $form['recaptcha']->getValue());
	}

	public function testOmitted()
	{
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_POST = ['g-recaptcha-response' => 'a'];
		$_FILES = [];

		$form = new Form;
		$form['recaptcha'] = new ReCaptchaControl('', '', '', '');
		$this->assertSame([], $form->getValues(TRUE));
	}

}
