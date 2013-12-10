<?php

// please note: we can use unencoded characters like ö, é etc here as we use the html5 doctype with utf8 encoding
// in the application's header (in views/_templates/header.php)

$phplogin_lang = array(

// Login & Registration classes
'database_error'	=> 'Database verbinding probeem.',
'empty_username'	=> 'Gebruikersnaam veld was leeg',
'username_exist'	=> 'Sorry, deze gebruikersnaam is al in gebruik. Kies een andere.',
'invalid_username'	=> 'Gebruikersnaam past niet in het schema: enkel a-Z en nummers zijn toegestaan, 2 tot 64 karakters',
'empty_password'	=> 'Wachtwoord veld was leeg',
'bad_confirm_password'	=> 'Het ingevulde wachtwoord en het herhaalde wachtwoord komen niet overeen',
'password_too_short'	=> 'Minimum lengte van het wachtwoord is 6 karakters',
'email_exist'	=> 'Dit e-mailadres is al in gebruik. Gebruik de "Wachtwoord vergeten" pagina als je de gegevens verloren bent.',
'invalid_email'	=> 'Uw e-mailadres is niet in een geldig formaat',

// Registration class
'wrong_captcha'	=> 'Beveiligingscode is onjuist!',
'username_bad_length'	=> 'Gebruikersnaam mag niet korter dan 2 of langer dan 64 karakters',
'empty_email'	=> 'E-mailadres mag niet leeg zijn',
'email_too_long'	=> 'E-mailadres mag niet langer zijn dan 64 karakters',
'verification_mail_error'	=> 'Sorry, we hebben geen activatiemail kunnen sturen. Uw account is NIET aangemaakt',
'verification_mail_sent'	=> 'Uw account is succesvol aangemaakt en we hebben uw een mail gestuurd. Klik op de link in deze mail om uw account te activeren.',
'verification_mail_not_sent'=> 'Activatiemail NIET succesvol verzonden! Fout: ',
'registration_failed'	=> 'Sorry, uw registratie is mislukt. Ga terug en probeer het opnieuw.',
'activation_successful'	=> 'Activatie succesvol! U kunt nu inloggen!',
'activation_error'	=> 'Sorry, geen geschikte id/activatie code combinatie..',

// Login class
'invalid_cookie'	=> 'Ongeldige cookie',
'user_not_exist'	=> 'Deze gebruiker bestaat niet',
'wrong_password'	=> 'Verkeerd wachtwoord. Probeer opnieuw.',
'account_not_activated'	=> 'Uw account is nog niet geactieveerd. Klik op de bevestigings-link in de mail.',
'logged_out'	=> 'U ben uitgelogd.',
'same_username'	=> 'Sorry, die gebruikersnaam is het zelfde als uw huidige. Kies een andere.',
'same_email'	=> 'Sorry, dat e-mailadres is het zelfde als uw huidige. Kies een andere.',
'username_changed'	=> 'Uw gebruikersnaam is succesvol gewijzigd. Nieuwe gebruikersnaam is ',
'username_change_failed'	=> 'Sorry, uw gekozen gebruikersnaam wijzigen mislukt',
'email_changed'	=> 'Uw e-mailadres is succesvol gewijzigd. Nieuwe e-mailadres is ',
'email_change_failed'	=> 'Sorry, wijzigen van uw e-mailadres is mislukt.',
'password_changed'	=> 'Wachtwoord succesvol gewijzigd!',
'password_changed_failed'	=> 'Sorry, het wijzigen van uw wachtwoord mislukt.',
'wrong_old_password'	=> 'Uw oude wachtwoord verkeerd ingevoerd.',
'password_mail_sent'	=> 'Wachtwoord reset e-mail succesvol verzonden!',
'password_mail_not_sent'	=> 'Wachtwoord reset e-mail NIET succesvol verzonden! Fout: ',
'reset_link_has_expired'	=> 'Uw reset link is verlopen. Gebruik de reset link binnen 60 min.',
'empty_link_parameter'	=> 'Lege link parameter data.',

// Login form
'username'				=> 'Gebruikersnaam',
'password'				=> 'Wachtwoord',
'remember_me'			=> 'Laat me ingelogd blijven (voor 2 weken)',
'log_in'				=> 'Inloggen',
'register_new_account'	=> 'Account aanmaken',
'i_forgot_my_password'	=> 'Wachtwoord vergeten',

// Register form
'register_username'	=> 'Gebruikersnaam (enkel letters en nummers, 2 tot 64 karakters)',
'register_email'	=> 'Gebruiker email (geef een geldig e-mailadre op, u krijgt een mail met daarin een activatielink)',
'register_password'	=> 'Wachtwoord (min. 6 karakters!)',
'register_password_repeat'	=> 'Wachtwoord herhalen',
'register_captcha'	=> 'Neem de karakters over',
'Register'	=> 'Registreren',
'back_to_login'	=> 'Terug naar login pagina',

// password_reset_request
'password_reset_request'	=> 'Wachtwoord reset aanvragen. Geef uw gebruikersnaam op en u krijgt een e-mail met instructies:',
'reset_my_password'	=> 'Reset mijn wachtwoord',
'new_password'	=> 'Nieuw wachtwoord',
'repeat_new_password'	=> 'Nieuwe wachtwoord herhalen',
'submit_new_password'	=> 'Nieuwe wachtwoord opslaan',

// Edit account
'edit_title'	=> 'U bent ingelogd en kunt uw gegevens hier wijzigen',
'old_password'	=> 'Uw OUDE wachtwoord',
'new_username'	=> 'Nieuwe gebruikersnaam (gebruikersnaam mag niet leeg zijn en moeten azAZ09 en 2-64 karakters zijn)',
'new_email'	=> 'Nieuwe e-mail',
'currently'	=> 'huidige',
'change_username'	=> 'Wijzig gebruikersnaam',
'change_email'	=> 'Wijzig e-mail',
'change_password'	=> 'Wijzig wachtwoord',

// Logged in
'you_are_logged_in_as'	=> 'U ben ingelogd als ',
'logout'	=> 'Uitloggen',
'edit_user_data'	=> 'Wijzig gebruiker gegevens',
'profile_picture'	=> 'Uw profielfoto (van gravatar):'

);
