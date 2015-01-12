<?php

class CaptchaModel
{
	/**
	 * Generates the captcha, "returns" a real image,
	 * this is why there is header('Content-type: image/jpeg')
	 * Note: This is a very special method, as this is echoes out binary data.
	 * Eventually this is something to refactor
	 */
	public static function generateAndShowCaptcha()
	{
		// create a captcha with the CaptchaBuilder lib (loaded via Composer)
		$captcha = new Gregwar\Captcha\CaptchaBuilder;
		$captcha->build();

		// write the captcha character into session
		Session::set('captcha', $captcha->getPhrase());

		// render an image showing the characters (=the captcha)
		header('Content-type: image/jpeg');
		$captcha->output();
	}

	/**
	 * Checks if the entered captcha is the same like the one from the rendered image which has been saved in session
	 * @param $captcha string The captcha characters
	 * @return bool success of captcha check
	 */
	public static function checkCaptcha($captcha)
	{
		if ($captcha == Session::get('captcha')) {
			return true;
		}

		return false;
	}
}
