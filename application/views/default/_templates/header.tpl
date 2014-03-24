    <!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{if isset($lang.title)}{$lang.title}{else}{$lang.TITLE}{/if}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS -->
    {section name=s loop=$css}
    <link rel="stylesheet" href="{$site_path}public/css/{$css[s]}" />
    {/section}
    
    {section name=s loop=$js}
    <script type="text/javascript" src="{$site_path}public/js/{$js[s]}"></script>
    {/section}    
</head>
<body>