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
        vertical-align: middle;
        bottom: 1px;
    }
    input[type=text], input[type=password], input[type=submit], input[type=email] {
        display: block;
        margin-bottom: 15px;
    }
    input[type=checkbox] {
        margin-bottom: 15px;
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

?>   
