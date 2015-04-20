<?php

include('../CaptchaBuilderInterface.php');
include('../PhraseBuilderInterface.php');
include('../CaptchaBuilder.php');
include('../PhraseBuilder.php');

use Gregwar\Captcha\CaptchaBuilder;

$captcha = new CaptchaBuilder;
$captcha
    ->build()
    ->save('out.jpg')
    ;
