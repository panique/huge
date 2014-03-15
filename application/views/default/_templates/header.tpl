<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>My Application</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS -->
    {section name=s loop=$css}
    <link rel="stylesheet" href="./public/css/{$css[s]}" />
    {/section}
    
    {section name=s loop=$js}
    <script type="text/javascript" src="./public/js/{$js[s]}"></script>
    {/section}    
</head>
<body>