<?php

// please note: we can use unencoded characters like ö, é etc here as we use the html5 doctype with utf8 encoding
// in the application's header (in views/_templates/header.php)

$phplogin_lang = array(

// Login & Registration classes
'database_error'			=> 'Problem przy połączeniu z bazą danych.',
'empty_username'			=> 'Pole użytkownika było puste',
'username_exist'			=> 'Niestety ta nazwa użytkownika jest już zajęta. Proszę wybrać inną.',
'invalid_username'			=> 'Niestety nazwa użytkownika nie spełnia wymagań: dopuszczalne są tylko litery i cyfry, 2 do 64 znaków',
'empty_password'			=> 'Puste pole Hasło',
'bad_confirm_password'		=> 'Hasła nie pasują do siebie',
'password_too_short'		=> 'Hasło powinno mieć przynajmniej 6 znaków',
'email_exist'				=> 'Ten email jest już zarejestrowany. Proszę użyć linku "Zapomniałem hasła"',
'invalid_email'				=> 'Proszę wpisać poprawny adres email.',

// Registration class
'wrong_captcha'				=> 'Przepisz poprawnie kod Captcha z obrazka.',
'username_bad_length'		=> 'Nazwa użytkownika nie może być krótsza niż 2 i dłuższa niż 64 znaki.',
'empty_email'				=> 'Puste pole Email.',
'email_too_long'			=> 'Email nie może być dłuższy niż 64 znaki.',
'verification_mail_error'	=> 'Przykro mi, ale nie udało się dostarczyć linku aktywującego na Twój adres email. Twoje konto NIE zostało utworzone.',
'verification_mail_sent'	=> 'Twoje konto zostało utworzone, a na podany przez ciebie email został wysłany link aktywacyjny. Proszę klikną w link aktywacyjny zawarty w emailu.',
'verification_mail_not_sent'=> 'Mail weryfikacyjny NIE został wysłany! Błąd: ',
'registration_failed'		=> 'Przykro mi, proces rejestracji nie powiódł się. Proszę spróbować ponownie.',
'activation_successful'		=> 'Aktywacja powidła się! Możesz się teraz zalogować.',
'activation_error'			=> 'Przykro mi, ale nie istnieje taka kombinacja kody weryfikacyjnego oraz nazwy użytkownika...',

// Login class
'invalid_cookie'			=> 'Błąd pliku cookie.',
'user_not_exist'			=> 'Taki użytkownik nie istnieje.',
'wrong_password'			=> 'Złe hasło, spróbuj ponownie.',
'account_not_activated'		=> 'Twoje konto nie zostało jeszcze aktywowane. Kliknij w link aktywacyjny w wysłanym do Ciebie emailu.',
'logged_out'				=> 'Zostałeś wylogowany.',
'same_username'				=> 'Nazwa użytkownika jest taka sama jak jak Twoja aktualna. Proszę wybrać inną.',
'same_email'				=> 'Ten email jest taki sam jak Twój aktualny. Proszę wybrać inny.',
'username_changed'			=> 'Twoja nazwa użytkownika została zmieniona. Nowa nazwa użytkownika to ',
'username_change_failed'	=> 'Nie udało się zmienić Twojej nazwy użytkownika.',
'email_changed'				=> 'Twój email został zmieniony. Nowy email to ',
'email_change_failed'		=> 'Nie udało się zmienić Twojego adresu email',
'password_changed'			=> 'Twoje hasło zostało zmienione!',
'password_changed_failed'	=> 'Nie udało się zmienić Twojego hasła.',
'wrong_old_password'		=> 'Wpisz poprawnie swoje STARE hasło.',
'password_mail_sent'		=> 'Email do zmiany hasła został wysłany!',
'password_mail_not_sent'	=> 'Email do zmiany hasła NIE został wysłany! Błąd: ',
'reset_link_has_expired'	=> 'Link do zmiany hasła stracił ważność. Proszę użyć linku w przeciągu jednej godziny.',
'empty_link_parameter'		=> 'Pusty link.',

// Login form
'username'					=> 'Nazwa użytkownika',
'password'					=> 'Hasło',
'remember_me'				=> 'Pamiętaj mnie przez następne 2 tygodnie',
'log_in'					=> 'Zaloguj się',
'register_new_account'		=> 'Zarejestruj nowe konto',
'i_forgot_my_password'		=> 'Zapomniałem hasła',

// Register form
'register_username'			=> 'Nazwa użytkownika (tylko litery i cyfry, od 2 do 64 znaków)',
'register_email'			=> 'Twój adres email (proszę podać prawdziwy adres, na niego zostanie wysłany link aktywacyjny)',
'register_password'			=> 'Hasło (min. 6 znaków!)',
'register_password_repeat'	=> 'Powtórz hasło',
'register_captcha'			=> 'Przepisz kod z obrazka',
'Register'					=> 'Zarejestruj się',
'back_to_login'				=> 'Powrót do strony logowania',

// password_reset_request
'password_reset_request'	=> 'Zmiana hasła. Wpisz swoją nazwę użytkownika, a na podany przez Ciebie email zostanie wysłany link resetujący hasło:',
'reset_my_password'			=> 'Zmień hasło',
'new_password'				=> 'Nowe hasło',
'repeat_new_password'		=> 'Powtórz nowe hasło',
'submit_new_password'		=> 'Zapisz nowe hasło',

// Edit account
'edit_title'				=> 'Jesteś zalogowany i możesz edytować swoje dane',
'old_password'				=> 'Twoje STARE hasło',
'new_username'				=> 'Nowa nazwa użytkownika (nazwa użytkownika musi się składać z liter i cyfr, od 2 do 64 znaków.)',
'new_email'					=> 'Nowy email',
'currently'					=> 'aktualnie',
'change_username'			=> 'Zmień nazwę użytkownika',
'change_email'				=> 'Zmień email',
'change_password'			=> 'Zmień hasło',

// Logged in
'you_are_logged_in_as'		=> 'Jesteś zalogowany jako ',
'logout'					=> 'Wyloguj',
'edit_user_data'			=> 'Edytuj dane',
'profile_picture'			=> 'Twoje zdjęcie profilowe (z serwisu gravatar):'

);
