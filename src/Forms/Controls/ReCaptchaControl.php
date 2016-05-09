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

	/** @var string */
	private $theme;

	/** @var string */
	private $type;

	/** @var string */
	private $size;

	/**
	 * @param string $siteKey
	 * @param string $theme
	 * @param string|NULL $caption
	 */
	public function __construct($siteKey, $theme, $type, $size, $caption = NULL)
	{
		parent::__construct($caption);
		$this->siteKey = $siteKey;
		$this->theme = $theme;
		$this->type = $type;
		$this->size = $size;
		
		$this->control = Html::el('div')->addAttributes([
			'class' => 'g-recaptcha',
			'data-sitekey' => $this->siteKey,
			'data-theme' => $this->theme,
			'data-type' => $this->type,
			'data-size' => $this->size,
		]);
		$this->setOmitted();
	}

	public function loadHttpData()
	{
		$this->setValue($this->getForm()->getHttpData(Form::DATA_TEXT, 'g-recaptcha-response'));
	}

}
