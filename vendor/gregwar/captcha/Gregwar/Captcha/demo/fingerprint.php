<?php

include('../CaptchaBuilderInterface.php');
include('../PhraseBuilderInterface.php');
include('../CaptchaBuilder.php');
include('../PhraseBuilder.php');

use Gregwar\Captcha\CaptchaBuilder;

echo count(CaptchaBuilder::create()
    ->build()
    ->getFingerprint()
);

echo "\n";
