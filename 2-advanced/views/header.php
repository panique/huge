<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PHP-login</title>
<style type="text/css">
	body {
		font-family: Helvetica, Verdana, Arial, sans-serif;
		font-size: 10pt;
		margin: 10px;
	}
	label {
		position: relative;
		bottom: 1px;
		display: block;
	}
	input[type=text], input[type=password], input[type=submit], input[type=email] {
		margin-bottom: 15px;
	}
	input[type=checkbox] + label {
		display: inline-block;
		margin-bottom: 15px;
	}
	input[type=submit] {
		display: block;
	}
</style>
</head>
<body>
<?php

// show negative messages
if ($login->errors) {
    foreach ($login->errors as $error) {
        echo $error;
    }
}

// show positive messages
if ($login->messages) {
    foreach ($login->messages as $message) {
        echo $message;
    }
}
