Nette ReCaptcha
======

This extension provides integration of [Google reCAPTCHA](https://www.google.com/recaptcha/intro/index.html) into Nette Framework.

[![Build Status](https://travis-ci.org/lookyman/recaptcha.svg?branch=master)](https://travis-ci.org/lookyman/recaptcha)
[![Downloads](https://img.shields.io/packagist/dt/lookyman/recaptcha.svg)](https://packagist.org/packages/lookyman/recaptcha)
[![Latest stable](https://img.shields.io/packagist/v/lookyman/recaptcha.svg)](https://packagist.org/packages/lookyman/recaptcha)
[![Code Climate](https://codeclimate.com/github/lookyman/recaptcha/badges/gpa.svg)](https://codeclimate.com/github/lookyman/recaptcha)


Requirements
------

lookyman/ReCaptcha requires PHP 5.5 or higher.

- [Nette Framework](https://github.com/nette/nette)


Installation
------

The best way to install lookyman/ReCaptcha is using [Composer](http://getcomposer.org/):

```sh
$ composer require lookyman/recaptcha
```

You can enable the extension using your neon config:

```yml
extensions:
	recaptcha: lookyman\ReCaptcha\DI\ReCaptchaExtension
```


Configuration
------

This extension creates new configuration section `recaptcha`, the default configuration looks like this:

```yml
recaptcha:
	siteKey: ''
	secretKey: ''
	verificationUrl: 'https://www.google.com/recaptcha/api/siteverify'
	errorMessage: 'You appear to be a bot'
	validateRemoteIp: off
	client: []
```

You can get your `siteKey` and `secretKey` at the [Google reCAPTCHA](https://www.google.com/recaptcha/intro/index.html) admin page.

The `client` configuration option can pass additional settings to the default [Guzzle](http://guzzlephp.org) client. For example, should you run into the `cURL error 60: SSL certificate problem: unable to get local issuer certificate` error, you can use the `verify: off` option here to disable peer certificate verification.

Additionally, you have to paste the following snippet into your template just before the closing `</body>` tag:

```html
<script src='https://www.google.com/recaptcha/api.js'></script>
```


Usage
------

This extension adds a single method into the `Nette\Forms\Container` namespace with the following signature:

```php
/**
 * @param string $name Control name
 * @param string|NULL $label Control label
 * @return \lookyman\ReCaptcha\Forms\Controls\ReCaptchaControl
 */
public function addReCaptcha($name, $label = NULL);
```

Adding a reCAPTCHA to your form is then as easy as adding any other control type:

```php
protected function createComponentMyReCaptchaForm()
{
	$form = new Nette\Application\UI\Form();
	$form->addReCaptcha('recaptcha', 'You have to solve this before you send the form');
	...
}
```


-----

Homepage [https://lookyman.net](https://lookyman.net) and repository [https://github.com/lookyman/recaptcha](https://github.com/lookyman/recaptcha).
