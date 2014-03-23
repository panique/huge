<?php
/*
 * each lang item has the following syntax:
 *      $lang["folder name in views"]["line name"]
 * The line name can be anything as long as it is properly set in the file
 * The folder name must match the views folder it is to be used in
 * If something is to be used in every folder, then it goes in ["ALL"] 
 * Keep the caps of ["ALL"] properties to make sure folder properties don't get ignored
 *      This means the template is case sensitive too.
 * 
 * RESERVED : $lang[pagename]["title"] = Will replace the ALL title in the header.
 * 
 * The header.tpl should be in utf-8
 * Escape double-quotes with \"
 */
$lang["ALL"]["TITLE"] = "PHP Login with Smarty";


//$lang["index"]["title"] = "index only title";
$lang["index"]["box1"]= "This box (everything between header and footer) is the content of views/index/default/index.php, 
        so it's the index/index view.";
$lang["index"]["box2"]= "It's rendered by the index-method within the index-controller (in controllers/index.php).";
$lang["index"]["geninfo"]= "General information on this little framework";
$lang["index"]["frmw1"]= "\"C'mon! Framework #1000 ? Why do we need this ?\" Indeed, there are a lot of good
        (and a lot of bad, too) PHP frameworks on the web. But most of them have something in common:
        They don't have a proper login system. And even if they have, then it's using outdated
        password hashing/salting technologies, it's not future-proof, don't provide email verification,
        password reset etc.";
$lang["index"]["frmw2"]= "This framework tries to";
$lang["index"]["frmw3"]= "focus on a proper, secure and up-to-date login system";
$lang["index"]["frmw4"]= "combined with an easy-to-use, easy-to-understand and highly usable framework structure.
        So, if you don't like the framework itself, feel free to merge the login-related actions,
        models and views into the framework of your choice.";
