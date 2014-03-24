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
$lang["ALL"]["EDIT"] = "Edit";
$lang["ALL"]["DELETE"] = "Delete";


//$lang["index"]["title"] = "index only title";
$lang["index"]["header"] = "Index";
$lang["index"]["box1"]= "This box (everything between header and footer) is the content of views/index/default/index.php, 
        so it's the index/index view.";
$lang["index"]["box2"]= "It's rendered by the index-method within the index-controller (in controllers/index.php).";
$lang["index"]["geninfo"]= "General information on this framework";
$lang["index"]["frmw1"]= "\"C'mon! Framework #1000 ? Why do we need this ?\" Indeed, there are a lot of good
        (and a lot of bad, too) PHP frameworks on the web. But most of them have something in common:
        They don't have a proper login system. And even if they have, then it's using outdated
        password hashing/salting technologies, it's not future-proof, don't provide email verification,
        password reset etc.";
$lang["index"]["frmw2"]= "This framework tries to";
$lang["index"]["frmw3"]= "focus on a proper, secure and up-to-date login system,";
$lang["index"]["frmw4"]= "combined with an easy-to-use, easy-to-understand and highly usable framework structure.
        So, if you don't like the framework itself, feel free to merge the login-related actions,
        models and views into the framework of your choice.";
$lang["index"]["login1"]= "Go to the ";
$lang["index"]["login2"]= "login page ";

//$lang["login"]["title"] = "login only title";
$lang["login"]["header"] = "Login";
$lang["login"]["orheader"] = "or";
$lang["login"]["back"] = "Back to Login Page";
$lang["login"]["submit"] = "Submit";
$lang["login"]["register"] = "Register";
$lang["login"]["passmin"] = "New password (min. 6 characters!)";
$lang["login"]["passsecure"] = "Please note: using a long sentence as a password is much much safer then something like \"!c00lPa$\$w0rd\"). Have a look on";
$lang["login"]["passsecurelink"] = "this interesting security.stackoverflow.com thread";

$lang["login"]["indexlogin"] = "Username (or email)";
$lang["login"]["indexpassword"] = "Password";
$lang["login"]["indexstaylogged"] = "Keep me logged in";
$lang["login"]["indexforgot"] = "Forgot my Password";
$lang["login"]["indexfblogin"] = "Log in with Facebook";

$lang["login"]["changetype"] = "Change account type";
$lang["login"]["changetypeexp"] = "This page is a basic implementation of the upgrade-process.
        User can click on that button to upgrade their accounts from
        \"basic account\" to \"premium account\". This script simple offers
        a click-able button that will upgrade/downgrade the account instantly.
        In a real world application you would implement something like a
        pay-process.";
$lang["login"]["changeline1"] = "This view belongs to the login-controller / changeaccounttype()-method.
        The model used is login->changeAccountType().";
$lang["login"]["changecurrent"] = "Currently your account type is: ";
$lang["login"]["changeup"] = "Upgrade my account";
$lang["login"]["changedown"] = "Downgrade my account";

$lang["login"]["newpass"] = "Set new password";
$lang["login"]["newpassrepeat"] = "Repeat new password";
$lang["login"]["newpasssubmit"] = "Submit new password";

$lang["login"]["editemail"] = "Change your email adress";
$lang["login"]["editemailnew"] = "New email adress:";

$lang["login"]["edituser"] = "Change your username";
$lang["login"]["editusernew"] = "New username";

$lang["login"]["regisuser"] = "Username";
$lang["login"]["regisuserprop"] = "(only letters and numbers, 2 to 64 characters)";
$lang["login"]["regisemail"] = "Email";
$lang["login"]["regisemail1"] = "(please provide a ";
$lang["login"]["regisemail2"] = "real email address";
$lang["login"]["regisemail3"] = "you'll get a verification mail with an activation link)";
$lang["login"]["regisrepeatpass"] = "Repeat password";
$lang["login"]["regiscaptcha1"] = "Please enter these characters";
$lang["login"]["regiscaptcha2"] = "Please note: This captcha will be generated when the img tag requests the captcha-generation
        (and a real image) from YOURURL/login/showcaptcha. As this is a client-side triggered request, the
        \$_SESSION[\"captcha\"] dump in the footer will not show the captcha characters. The captcha generation
        happens AFTER the rendering of the footer.";
$lang["login"]["regisfb"] = "Register with Facebook";

$lang["login"]["resetpass"] = "Request a password reset";
$lang["login"]["resetpassalt"] = "Reset my password";
$lang["login"]["resetpassinst"] = "Enter your username and you'll get a mail with instructions:";

$lang["login"]["profile"] = "Your profile";
$lang["login"]["profileuser"] = "Your username:";
$lang["login"]["profilemail"] = "Your email:";
$lang["login"]["profilegrav"] = "Your gravatar pic (on gravatar.com):";
$lang["login"]["profileavatar"] = "Your avatar pic (saved on local server):";
$lang["login"]["profiletype"] = "Your account type is:";

$lang["login"]["avatar"] = "Upload an avatar";
$lang["login"]["avatarexp"] = "Select an avatar image from your hard-disk (will be scaled to 44x44 px):";
$lang["login"]["avatarupload"] = "Upload image";

$lang["login"]["verify"] = "Verification";

//$lang["note"]["title"] = "note only title";
$lang["note"]["notelist"] = "List of your notes";
$lang["note"]["notenone"] = "No notes yet. Create some !";
$lang["note"]["new"] = "Create new note";
$lang["note"]["newexp"] = "\"Notes\" are just an example of how to create, show (read), edit (update) and delete things. CRUD, you know...";
$lang["note"]["newtext"] = "Text of new note: ";

$lang["note"]["edit"] = "Edit a note";
$lang["note"]["editchange"] = "Change text of note: ";
$lang["note"]["editnone"] = "This note does not exist.";
$lang["note"]["editchange"] = "Change";

//$lang["overview"]["title"] = "Overview only title";
$lang["overview"]["header"] = "Overview";
$lang["overview"]["note"] = "NOTE: be sure NOT to show email addresses of users in a real app. This is a demo";
$lang["overview"]["active"] = "Active:";

$lang["overview"]["indexexp"] = "This controller/action/view shows a list of all users in the system.
        You could use the underlaying code to build things that use profile information
        of one or multiple/all users.";
$lang["overview"]["indexnoav"] = "No avatar";
$lang["overview"]["indexactive"] = "Active:";
$lang["overview"]["indexshow"] = "Show user's profile";
$lang["overview"]["indexnousers"] = "No users found";

$lang["overview"]["showpub"] = "A public user profile";
$lang["overview"]["showpubexp"] = "This controller/action/view shows all public information about a certain user.";
$lang["overview"]["shownone"] = "No user found";

$lang["error"]["title"] = "Error!";
$lang["error"]["pagenotfound"] = "This page does not exist.";

$lang["help"]["title"] = "Help page";
$lang["help"]["header"] = "Help";
$lang["help"]["index1"] = "This box (everything between header and footer) is the content of views/help/index.php,
        so it's the help/index view. It's rendered by the index-method within the help-controller
        (in controllers/help.php). You can easily create a sub-page by putting a method into the
        controller and a view into the view folder. So, if you want to create something like
        a FAQ section within \"Help\", then put ";
$lang["help"]["index2"] = "function faq() { \$this->view->render('help/faq'); }";
$lang["help"]["index3"] = " into controllers/help.php and create an according view in views/help/, named \"faq.php\".
        Now you can use that by simply navigation to \"help/faq\" in your app: If your app is on
        http://localhost/myapp/ then this section is now on http://localhost/myapp/help/faq !
        Try it out...";

//$lang["dashboard"]["title"] = "Dashboard only title";
$lang["dashboard"]["header"] = "Dashboard";
$lang["dashboard"]["indexlogged"] = "This is an area that's only visible for logged in users";
$lang["dashboard"]["index1logout"] = "Try to log out ";
$lang["dashboard"]["index1"] = "and go to /dashboard/ again. You'll be redirected to /index/ as you are not logged in.";
$lang["dashboard"]["index2"] = "You can protect a whole section in your app within the according controller (here: controllers/dashboard.php)
    by placing";
$lang["dashboard"]["index3"] = "Auth::handleLogin();";
$lang["dashboard"]["index4"] = " into the constructor.";
$lang["dashboard"]["index5"] = "Since you are logged in, you can ";
$lang["dashboard"]["index6"] = "view the overview";



/**
 * Configuration for: Error messages and notices
 *
 * In this project, the error messages, notices etc are all-together called "feedback".
 */
$feedback["FEEDBACK_UNKNOWN_ERROR"] = "Unknown error occurred!";
$feedback["FEEDBACK_PASSWORD_WRONG_3_TIMES"] = "You have typed in a wrong password 3 or more times already. Please wait 30 seconds to try again.";
$feedback["FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET"] = "Your account is not activated yet. Please click on the confirm link in the mail.";
$feedback["FEEDBACK_PASSWORD_WRONG"] = "Password was wrong.";
$feedback["FEEDBACK_USER_DOES_NOT_EXIST"] = "This user does not exist.";
// The "login failed"-message is a security improved feedback that doesn't show a potential attacker if the user exists or not
$feedback["FEEDBACK_LOGIN_FAILED"] = "Login failed.";
$feedback["FEEDBACK_USERNAME_FIELD_EMPTY"] = "Username field was empty.";
$feedback["FEEDBACK_PASSWORD_FIELD_EMPTY"] = "Password field was empty.";
$feedback["FEEDBACK_EMAIL_FIELD_EMPTY"] = "Email and passwords fields were empty.";
$feedback["FEEDBACK_EMAIL_AND_PASSWORD_FIELDS_EMPTY"] = "Email field was empty.";
$feedback["FEEDBACK_USERNAME_SAME_AS_OLD_ONE"] = "Sorry, that username is the same as your current one. Please choose another one.";
$feedback["FEEDBACK_USERNAME_ALREADY_TAKEN"] = "Sorry, that username is already taken. Please choose another one.";
$feedback["FEEDBACK_USER_EMAIL_ALREADY_TAKEN"] = "Sorry, that email is already in use. Please choose another one.";
$feedback["FEEDBACK_USERNAME_CHANGE_SUCCESSFUL"] = "Your username has been changed successfully.";
$feedback["FEEDBACK_USERNAME_AND_PASSWORD_FIELD_EMPTY"] = "Username and password fields were empty.";
$feedback["FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN"] = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters.";
$feedback["FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN"] = "Sorry, your chosen email does not fit into the email naming pattern.";
$feedback["FEEDBACK_EMAIL_SAME_AS_OLD_ONE"] = "Sorry, that email address is the same as your current one. Please choose another one.";
$feedback["FEEDBACK_EMAIL_CHANGE_SUCCESSFUL"] = "Your email address has been changed successfully.";
$feedback["FEEDBACK_CAPTCHA_WRONG"] = "The entered captcha security characters were wrong.";
$feedback["FEEDBACK_PASSWORD_REPEAT_WRONG"] = "Password and password repeat are not the same.";
$feedback["FEEDBACK_PASSWORD_TOO_SHORT"] = "Password has a minimum length of 6 characters.";
$feedback["FEEDBACK_USERNAME_TOO_SHORT_OR_TOO_LONG"] = "Username cannot be shorter than 2 or longer than 64 characters.";
$feedback["FEEDBACK_EMAIL_TOO_LONG"] = "Email cannot be longer than 64 characters.";
$feedback["FEEDBACK_ACCOUNT_SUCCESSFULLY_CREATED"] = "Your account has been created successfully and we have sent you an email. Please click the VERIFICATION LINK within that mail.";
$feedback["FEEDBACK_VERIFICATION_MAIL_SENDING_FAILED"] = "Sorry, we could not send you an verification mail. Your account has NOT been created.";
$feedback["FEEDBACK_ACCOUNT_CREATION_FAILED"] = "Sorry, your registration failed. Please go back and try again.";
$feedback["FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR"] = "Verification mail could not be sent due to: ";
$feedback["FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL"] = "A verification mail has been sent successfully.";
$feedback["FEEDBACK_ACCOUNT_ACTIVATION_SUCCESSFUL"] = "Activation was successful! You can now log in.";
$feedback["FEEDBACK_ACCOUNT_ACTIVATION_FAILED"] = "Sorry, no such id/verification code combination here...";
$feedback["FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL"] = "Avatar upload was successful.";
$feedback["FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE"] = "Only JPEG and PNG files are supported.";
$feedback["FEEDBACK_AVATAR_UPLOAD_TOO_SMALL"] = "Avatar source file's width/height is too small. Needs to be 100x100 pixel minimum.";
$feedback["FEEDBACK_AVATAR_UPLOAD_TOO_BIG"] = "Avatar source file is too big. 5 Megabyte is the maximum.";
$feedback["FEEDBACK_AVATAR_FOLDER_DOES_NOT_EXIST_OR_NOT_WRITABLE"] = "Avatar folder does not exist or is not writable. Please change this via chmod 775 or 777.";
$feedback["FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED"] = "Something went wrong with the image upload.";
$feedback["FEEDBACK_PASSWORD_RESET_TOKEN_FAIL"] = "Could not write token to database.";
$feedback["FEEDBACK_PASSWORD_RESET_TOKEN_MISSING"] = "No password reset token.";
$feedback["FEEDBACK_PASSWORD_RESET_MAIL_SENDING_ERROR"] = "Password reset mail could not be sent due to: ";
$feedback["FEEDBACK_PASSWORD_RESET_MAIL_SENDING_SUCCESSFUL"] = "A password reset mail has been sent successfully.";
$feedback["FEEDBACK_PASSWORD_RESET_LINK_EXPIRED"] = "Your reset link has expired. Please use the reset link within one hour.";
$feedback["FEEDBACK_PASSWORD_RESET_COMBINATION_DOES_NOT_EXIST"] = "Username/Verification code combination does not exist.";
$feedback["FEEDBACK_PASSWORD_RESET_LINK_VALID"] = "Password reset validation link is valid. Please change the password now.";
$feedback["FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL"] = "Password successfully changed.";
$feedback["FEEDBACK_PASSWORD_CHANGE_FAILED"] = "Sorry, your password changing failed.";
$feedback["FEEDBACK_ACCOUNT_UPGRADE_SUCCESSFUL"] = "Account upgrade was successful.";
$feedback["FEEDBACK_ACCOUNT_UPGRADE_FAILED"] = "Account upgrade failed.";
$feedback["FEEDBACK_ACCOUNT_DOWNGRADE_SUCCESSFUL"] = "Account downgrade was successful.";
$feedback["FEEDBACK_ACCOUNT_DOWNGRADE_FAILED"] = "Account downgrade failed.";
$feedback["FEEDBACK_NOTE_CREATION_FAILED"] = "Note creation failed.";
$feedback["FEEDBACK_NOTE_EDITING_FAILED"] = "Note editing failed.";
$feedback["FEEDBACK_NOTE_DELETION_FAILED"] = "Note deletion failed.";
$feedback["FEEDBACK_COOKIE_INVALID"] = "Your remember-me-cookie is invalid.";
$feedback["FEEDBACK_COOKIE_LOGIN_SUCCESSFUL"] = "You were successfully logged in via the remember-me-cookie.";
$feedback["FEEDBACK_FACEBOOK_LOGIN_NOT_REGISTERED"] = "Sorry, you don't have an account here. Please register first.";
$feedback["FEEDBACK_FACEBOOK_EMAIL_NEEDED"] = "Sorry, but you need to allow us to see your email address to register.";
$feedback["FEEDBACK_FACEBOOK_UID_ALREADY_EXISTS"] = "Sorry, but you have already registered here (your Facebook ID exists in our database).";
$feedback["FEEDBACK_FACEBOOK_EMAIL_ALREADY_EXISTS"] = "Sorry, but you have already registered here (your Facebook email exists in our database).";
$feedback["FEEDBACK_FACEBOOK_USERNAME_ALREADY_EXISTS"] = "Sorry, but you have already registered here (your Facebook username exists in our database).";
$feedback["FEEDBACK_FACEBOOK_REGISTER_SUCCESSFUL"] = "You have been successfully registered with Facebook.";
$feedback["FEEDBACK_FACEBOOK_OFFLINE"] = "We could not reach the Facebook servers. Maybe Facebook is offline (that really happens sometimes).";
$feedback["FEEDBACK_LANGUAGE_NOT_SET"] = "No language code passed";
$feedback["FEEDBACK_LANGUAGE_NOT_VALID"] = "Chosen language code not valid";
$feedback["FEEDBACK_LANGUAGE_NOT_EXIST"] = "No language was selected";
