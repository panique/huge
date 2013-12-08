<?php

// please note: we can use unencoded characters like ö, é etc here as we use the html5 doctype with utf8 encoding
// in the application's header (in views/_templates/header.php)

$phplogin_lang = array(

// Login & Registration classes
'database_error'			=> 'Database connection problem.',
'empty_username'			=> 'Username field was empty',
'username_exist'			=> 'Sorry, that username is already taken. Please choose another one.',
'invalid_username'			=> 'Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters',
'empty_password'			=> 'Password field was empty',
'bad_confirm_password'		=> 'Password and password repeat are not the same',
'password_too_short'		=> 'Password has a minimum length of 6 characters',
'email_exist'				=> 'This email address is already registered. Please use the "I forgot my password" page if you don\'t remember it.',
'invalid_email'				=> 'Your email address is not in a valid email format',

// Registration class
'wrong_captcha'				=> 'Captcha was wrong!',
'username_bad_length'		=> 'Username cannot be shorter than 2 or longer than 64 characters',
'empty_email'				=> 'Email cannot be empty',
'email_too_long'			=> 'Email cannot be longer than 64 characters',
'verification_mail_error'	=> 'Sorry, we could not send you an verification mail. Your account has NOT been created.',
'verification_mail_sent'	=> 'Your account has been created successfully and we have sent you an email. Please click the VERIFICATION LINK within that mail.',
'verification_mail_not_sent'=> 'Verification Mail NOT successfully sent! Error: ',
'registration_failed'		=> 'Sorry, your registration failed. Please go back and try again.',
'activation_successful'		=> 'Activation was successful! You can now log in!',
'activation_error'			=> 'Sorry, no such id/verification code combination here...',

// Login class
'invalid_cookie'			=> 'Invalid cookie',
'user_not_exist'			=> 'This user does not exist',
'wrong_password'			=> 'Wrong password. Try again.',
'account_not_activated'		=> 'Your account is not activated yet. Please click on the confirm link in the mail.',
'logged_out'				=> 'You have been logged out.',
'same_username'				=> 'Sorry, that username is the same as your current one. Please choose another one.',
'same_email'				=> 'Sorry, that email address is the same as your current one. Please choose another one.',
'username_changed'			=> 'Your username has been changed successfully. New username is ',
'username_change_failed'	=> 'Sorry, your chosen username renaming failed',
'email_changed'				=> 'Your email address has been changed successfully. New email address is ',
'email_change_failed'		=> 'Sorry, your email changing failed.',
'password_changed'			=> 'Password successfully changed!',
'password_changed_failed'	=> 'Sorry, your password changing failed.',
'wrong_old_password'		=> 'Your OLD password was wrong.',
'password_mail_sent'		=> 'Password reset mail successfully sent!',
'password_mail_not_sent'	=> 'Password reset mail NOT successfully sent! Error: ',
'reset_link_has_expired'	=> 'Your reset link has expired. Please use the reset link within one hour.',
'empty_link_parameter'		=> 'Empty link parameter data.',

// Login form
'username'					=> 'Username',
'password'					=> 'Password',
'remember_me'				=> 'Keep me logged in (for 2 weeks)',
'log_in'					=> 'Log in',
'register_new_account'		=> 'Register new account',
'i_forgot_my_password'		=> 'I forgot my password',

// Register form
'register_username'			=> 'Username (only letters and numbers, 2 to 64 characters)',
'register_email'			=> 'User\'s email (please provide a real email address, you\'ll get a verification mail with an activation link)',
'register_password'			=> 'Password (min. 6 characters!)',
'register_password_repeat'	=> 'Password repeat',
'register_captcha'			=> 'Please enter those characters',
'register'					=> 'Register',
'back_to_login'				=> 'Back to Login Page',

// password_reset_request
'password_reset_request'	=> 'Request a password reset. Enter your username and you\'ll get a mail with instructions:',
'reset_my_password'			=> 'Reset my password',
'new_password'				=> 'New password',
'repeat_new_password'		=> 'Repeat new password',
'submit_new_password'		=> 'Submit new password',

// Edit account
'edit_title'				=> 'You are logged in and can edit your credentials here',
'old_password'				=> 'Your OLD Password',
'new_username'				=> 'New username (username cannot be empty and must be azAZ09 and 2-64 characters)',
'new_email'					=> 'New email',
'currently'					=> 'currently',
'change_username'			=> 'Change username',
'change_email'				=> 'Change email',
'change_password'			=> 'Change password',

// Logged in
'you_are_logged_in_as'		=> 'You are logged in as ',
'logout'					=> 'Logout',
'edit_user_data'			=> 'Edit user data',
'profile_picture'			=> 'Your profile picture (from gravatar):'

);