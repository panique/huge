<!-- this style stuff is just for demonstration. nice people put this in .css files -->
<style>
    
    #login_wrapper {
        margin:100px;
        width:200px;
    }
    
    #login {
        font-family: Arial;
        text-align: center;
    }
    
    #login a {
        color: #555;
        text-decoration: none;
        font-size: 11px;
    }
    
    #login a:hover {
        text-decoration:underline;
    }

    .headline {
        font-size: 24px;
        color:#555;
        margin-bottom: 10px;
    }
    
    fieldset {
        
        border:1px solid #999;
        border-radius: 5px;
        padding:10px;
        box-shadow: 0px 0px 3px 2px rgba(200,200,200, 0.3);
    }
    
    input {
        border:1px solid #bbb;
        border-radius: 5px;
        padding:6px 8px;        
        width:100%;
        color:#555;
        font-size:11px;
        box-shadow: 1px 1px 3px 2px rgba(200,200,200, 0.2) inset;
    }
    
    input[type="text"],
    input[type="password"] {
        margin-bottom: 5px;
        color:#999;
    }
    
    input[type="submit"] {
        border-color: #999;  
        cursor: pointer;
        background: -webkit-linear-gradient(top, white, #E0E0E0);
        background:    -moz-linear-gradient(top, white, #E0E0E0);
        background:     -ms-linear-gradient(top, white, #E0E0E0);
        background:      -o-linear-gradient(top, white, #E0E0E0);
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,0.25), inset 0 0 3px #fff;
        -moz-box-shadow:    0 1px 2px rgba(0,0,0,0.25), inset 0 0 3px #fff;
        box-shadow:         0 1px 2px rgba(0,0,0,0.25), inset 0 0 3px #fff;
        margin-top: 5px;
    }
    
    input[type="submit"]:hover {
        background: -webkit-linear-gradient(bottom, white, #E0E0E0);
        background:    -moz-linear-gradient(bottom, white, #E0E0E0);
        background:     -ms-linear-gradient(bottom, white, #E0E0E0);
        background:      -o-linear-gradient(bottom, white, #E0E0E0);
    }
    
    .message_success {
        border:1px solid #58BA36;
        border-radius: 5px;
        background-color: #E9F9E5;
        padding:6px 8px; 
        color:#58BA36;
        font-size:11px;
        margin-bottom: 10px;
    }
    
    .message_error {
        border:1px solid #C83E16;
        border-radius: 5px;
        background-color: #F9E5E6;
        padding:6px 8px; 
        color:#C83E16;
        font-size:11px;
        -webkit-box-shadow: 0 2px 3px rgba(62,120,170,0.25);
        -moz-box-shadow:    0 2px 3px rgba(62,120,170,0.25);
        box-shadow:         0 2px 3px rgba(62,120,170,0.25);
        margin-bottom: 10px;
    }    
    
</style>

<div id="login_wrapper">
    <div id="login">
        
        <div class="headline">Create new user</div>
        <a href="index.php">Back to login</a><br/><br/>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?action=register" name="registerform" id="registerform">
            <fieldset>
                
<?php

if ($login->errors) {
    foreach ($login->errors as $error) {
        echo '<div class="message_error">'.$error.'</div>'; 
    }
}

if ($login->messages) {
    foreach ($login->messages as $message) {
        echo '<div class="message_success">'.$message.'</div>'; 
    }
}

?>                
                
                <input type="text" name="user_name" value="Username" onfocus="if(this.value=='Username') this.value='';" onblur="if(this.value=='') this.value='Username';" /><br />
                <input type="text" name="user_password" value="Password" onfocus="if(this.value=='Password') this.value='';" onblur="if(this.value=='') this.value='Password';" /><br />
                <input type="text" name="user_email" value="E-Mail" onfocus="if(this.value=='E-Mail') this.value='';" onblur="if(this.value=='') this.value='E-Mail';" /><br />
                <input type="submit" name="register" value="Register" />
            </fieldset>
        </form>
   
    </div>
</div>

<!-- this is the Simple sexy PHP Login Script. You can find it on http://www.php-login.net ! It's free and open source. -->