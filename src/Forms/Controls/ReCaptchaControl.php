<?php

/**
 * This file is part of lookyman/ReCaptcha (https://github.com/lookyman/recaptcha)
 *
 * Copyright (c) 2015 Lukáš Unger (looky.msc@gmail.com)
 *
 * For the full copyright and license information, please view the file LICENSE that was distributed with this source code.
 */

namespace lookyman\ReCaptcha\Forms\Controls;

use Nette\Forms\Form;
use Nette\Forms\Controls\BaseControl;
use Nette\Utils\Html;

/**
 * ReCaptcha form control.
 *
 * @author Lukáš Unger <looky.msc@gmail.com>
 */
class ReCaptchaControl extends BaseControl
{

	/** @var string */
	private $siteKey;

	/**
	 * @param string $siteKey
	 * @param string|NULL $caption
	 */
	public function __construct($siteKey, $caption = NULL)
	{
		parent::__construct($caption);
		$this->siteKey = $siteKey;
		$this->control = Html::el('div')->addAttributes([
			'class' => 'g-recaptcha',
			'data-sitekey' => $this->siteKey,
		]);
		$this->setOmitted();
	}

	public function loadHttpData()
	{
		$this->setValue($this->getForm()->getHttpData(Form::DATA_TEXT, 'g-recaptcha-response'));
	}

}
