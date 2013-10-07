<?php

// please note: we can use unencoded characters like ö, é etc here as we use the html5 doctype with utf8 encoding
// in the application's header (in views/_templates/header.php)

$phplogin_lang = array(

// Login & Registration classes
'Database error'	=> 'Er is een probleem met de database verbinding.',
'Empty username'	=> 'Gebruikersnaam veld was leeg',
'Username exist'	=> 'Sorry, deze gebruikersnaam is al in gebruik. Kies een andere.',
'Invalid username'	=> 'Gebruikersnaam past niet in het schema: enkel a-Z en nummers zijn toegestaan, 2 tot 64 karakters',
'Empty password'	=> 'Wachtwoord vel was leeg',
'Bad confirm password'	=> 'Het ingevulde wachtwoord en het herhaalde wachtwoord komen niet overeen',
'Password too short'	=> 'Minimum lengte van het wachtwoord is 6 karakters',
'Email exist'	=> 'Dit e-mailadres is al in gebruik. Gebruik de "Wachtwoord vergeten" pagina als je de gegevens verloren bent.',
'Invalid email'	=> 'Uw e-mailadres is in een ongeldig formaat',

// Registration class
'Wrong captcha'	=> 'Beveiligingscode is onjuist!',
'Username bad length'	=> 'Gebruikersnaam mag niet korter zijn dan 2 of langer dan 64 karakters',
'Empty email'	=> 'E-mailadres mag niet leeg zijn',
'Email too long'	=> 'E-mail adres mag niet langer zijn dan 64 karakters',
'Verification mail error'	=> 'Sorry, we hebben geen activatiemail kunnen sturen. Uw account is NIET aangemaakt',
'Verification mail sent'	=> 'Uw account is succesvol aangemaakt, we hebben u een activatiemail gestuurd. Klik op de link in deze mail om uw account te activeren.',
'Verification mail not sent'=> 'Activatiemail NIET succesvol verzonden! Fout: ',
'Registration failed'	=> 'Sorry, uw registratie is mislukt. Ga terug en probeer het opnieuw.',
'Activation successful'	=> 'Activatie succesvol! U kunt nu inloggen!',
'Activation error'	=> 'Sorry, geen geschikte id/activatie code combinatie..',

// Login class
'Invalid cookie'	=> 'Ongeldige cookie',
'User not exist'	=> 'Deze gebruiker bestaat niet',
'Wrong password'	=> 'Verkeerd wachtwoord ingevoerd. Probeer opnieuw.',
'Account not activated'	=> 'Uw account is nog niet geactieveerd. Klik op de bevestigingslink in de mail.',
'Logged out'	=> 'U bent uitgelogd.',
'Same username'	=> 'Sorry, de ingevoerde gebruikersnaam is gelijk aan uw huidige. Kies een andere.',
'Same email'	=> 'Sorry, het ingevoerde e-mailadres is gelijk aan uw huidige. Kies een andere.',
'Username changed'	=> 'Uw gebruikersnaam is succesvol gewijzigd. Nieuwe gebruikersnaam is ',
'Username change failed'	=> 'Sorry, gebruikersnaam wijzigen mislukt',
'Email changed'	=> 'Uw e-mailadres is succesvol gewijzigd. Nieuwe e-mailadres is ',
'Email change failed'	=> 'Sorry, wijzigen van uw e-mailadres is mislukt.',
'Password changed'	=> 'Wachtwoord succesvol gewijzigd!',
'Password changed failed'	=> 'Sorry, het wijzigen van uw wachtwoord mislukt.',
'Wrong old password'	=> 'Uw oude wachtwoord is verkeerd ingevoerd.',
'Password mail sent'	=> 'Wachtwoord reset e-mail succesvol verzonden!',
'Password mail not sent'	=> 'Wachtwoord reset e-mail NIET succesvol verzonden! Fout: ',
'Reset link has expired'	=> 'Uw reset link is verlopen. Gebruik de reset link binnen 60 min.',
'Empty link parameter'	=> 'Lege link parameter data.',

// Login form
'Username'	=> 'Gebruikersnaam',
'Password'	=> 'Wachtwoord',
'Remember me'	=> 'Laat me ingelogd blijven (voor 2 weken)',
'Log in'	=> 'Inloggen',
'Register new account'	=> 'Account aanmaken',
'I forgot my password'	=> 'Wachtwoord vergeten',

// Register form
'Register username'	=> 'Gebruikersnaam (enkel letters en nummers, 2 tot 64 karakters)',
'Register email'	=> 'Gebruiker email (geef een geldig e-mailadre op, u krijgt een mail met daarin een activatielink)',
'Register password'	=> 'Wachtwoord (min. 6 karakters!)',
'Register password repeat'	=> 'Wachtwoord herhalen',
'Register captcha'	=> 'Neem de karakters over',
'Register'	=> 'Registreren',
'Back to login'	=> 'Terug naar login pagina',

// Password reset request
'Password reset request'	=> 'Wachtwoord reset aanvragen. Geef uw gebruikersnaam op en u krijgt een e-mail met instructies: ',
'Reset my password'	=> 'Reset mijn wachtwoord',
'New password'	=> 'Nieuw wachtwoord',
'Repeat new password'	=> 'Nieuwe wachtwoord herhalen',
'Submit new password'	=> 'Nieuwe wachtwoord opslaan',

// Edit account
'Edit title'	=> 'U bent ingelogd en u kunt uw gegevens hier wijzigen',
'Old password'	=> 'Uw OUDE wachtwoord',
'New username'	=> 'Nieuwe gebruikersnaam (gebruikersnaam mag niet leeg zijn en mogen enkel azAZ09 en 2-64 karakters bevatten)',
'New email'	=> 'Nieuwe e-mail',
'currently'	=> 'huidige',
'Change username'	=> 'Wijzig gebruikersnaam',
'Change email'	=> 'Wijzig e-mail',
'Change password'	=> 'Wijzig wachtwoord',

// Logged in
'You are logged in as'	=> 'U ben ingelogd als ',
'Logout'	=> 'Uitloggen',
'Edit user data'	=> 'Wijzig gebruiker-gegevens',
'Profile picture'	=> 'Uw profielfoto (van gravatar):'

);
