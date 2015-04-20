<?php

include('../CaptchaBuilderInterface.php');
include('../PhraseBuilderInterface.php');
include('../CaptchaBuilder.php');
include('../PhraseBuilder.php');

use Gregwar\Captcha\CaptchaBuilder;

header('Content-type: image/jpeg');

CaptchaBuilder::create()
    ->build()
    ->output()
    ;
