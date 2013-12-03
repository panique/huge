<?php

// please note: we can use unencoded characters like ö, é etc here as we use the html5 doctype with utf8 encoding
// in the application's header (in views/_templates/header.php)

$phplogin_lang = array(

// Login & Registration classes
'Database error'			=> 'Problem przy połączeniu z bazą danych.',
'Empty username'			=> 'Pole użytkownika było puste',
'Username exist'			=> 'Niestety ta nazwa użytkownika jest już zajęta. Proszę wybrać inną.',
'Invalid username'			=> 'Niestety nazwa użytkownika nie spełnia wymagań: dopuszczalne są tylko litery i cyfry, 2 do 64 znaków',
'Empty password'			=> 'Puste pole Hasło',
'Bad confirm password'		=> 'Hasła nie pasują do siebie',
'Password too short'		=> 'Hasło powinno mieć przynajmniej 6 znaków',
'Email exist'				=> 'Ten email jest już zarejestrowany. Proszę użyć linku "Zapomniałem hasła"',
'Invalid email'				=> 'Proszę wpisać poprawny adres email.',

// Registration class
'Wrong captcha'				=> 'Przepisz poprawnie kod Captcha z obrazka.',
'Username bad length'		=> 'Nazwa użytkownika nie może być krótsza niż 2 i dłuższa niż 64 znaki.',
'Empty email'				=> 'Puste pole Email.',
'Email too long'			=> 'Email nie może być dłuższy niż 64 znaki.',
'Verification mail error'	=> 'Przykro mi, ale nie udało się dostarczyć linku aktywującego na Twój adres email. Twoje konto NIE zostało utworzone.',
'Verification mail sent'	=> 'Twoje konto zostało utworzone, a na podany przez ciebie email został wysłany link aktywacyjny. Proszę klikną w link aktywacyjny zawarty w emailu.',
'Verification mail not sent'=> 'Mail weryfikacyjny NIE został wysłany! Błąd: ',
'Registration failed'		=> 'Przykro mi, proces rejestracji nie powiódł się. Proszę spróbować ponownie.',
'Activation successful'		=> 'Aktywacja powidła się! Możesz się teraz zalogować.',
'Activation error'			=> 'Przykro mi, ale nie istnieje taka kombinacja kody weryfikacyjnego oraz nazwy użytkownika...',

// Login class
'Invalid cookie'			=> 'Błąd pliku cookie.',
'User not exist'			=> 'Taki użytkownik nie istnieje.',
'Wrong password'			=> 'Złe hasło, spróbuj ponownie.',
'Account not activated'		=> 'Twoje konto nie zostało jeszcze aktywowane. Kliknij w link aktywacyjny w wysłanym do Ciebie emailu.',
'Logged out'				=> 'Zostałeś wylogowany.',
'Same username'				=> 'Nazwa użytkownika jest taka sama jak jak Twoja aktualna. Proszę wybrać inną.',
'Same email'				=> 'Ten email jest taki sam jak Twój aktualny. Proszę wybrać inny.',
'Username changed'			=> 'Twoja nazwa użytkownika została zmieniona. Nowa nazwa użytkownika to ',
'Username change failed'	=> 'Nie udało się zmienić Twojej nazwy użytkownika.',
'Email changed'				=> 'Twój email został zmieniony. Nowy email to ',
'Email change failed'		=> 'Nie udało się zmienić Twojego adresu email',
'Password changed'			=> 'Twoje hasło zostało zmienione!',
'Password changed failed'	=> 'Nie udało się zmienić Twojego hasła.',
'Wrong old password'		=> 'Wpisz poprawnie swoje STARE hasło.',
'Password mail sent'		=> 'Email do zmiany hasła został wysłany!',
'Password mail not sent'	=> 'Email do zmiany hasła NIE został wysłany! Błąd: ',
'Reset link has expired'	=> 'Link do zmiany hasła stracił ważność. Proszę użyć linku w przeciągu jednej godziny.',
'Empty link parameter'		=> 'Pusty link.',

// Login form
'Username'					=> 'Nazwa użytkownika',
'Password'					=> 'Hasło',
'Remember me'				=> 'Pamiętaj mnie przez następne 2 tygodnie',
'Log in'					=> 'Zaloguj się',
'Register new account'		=> 'Zarejestruj nowe konto',
'I forgot my password'		=> 'Zapomniałem hasła',

// Register form
'Register username'			=> 'Nazwa użytkownika (tylko litery i cyfry, od 2 do 64 znaków)',
'Register email'			=> 'Twój adres email (proszę podać prawdziwy adres, na niego zostanie wysłany link aktywacyjny)',
'Register password'			=> 'Hasło (min. 6 znaków!)',
'Register password repeat'	=> 'Powtórz hasło',
'Register captcha'			=> 'Przepisz kod z obrazka',
'Register'					=> 'Zarejestruj się',
'Back to login'				=> 'Powrót do strony logowania',

// Password reset request
'Password reset request'	=> 'Zmiana hasła. Wpisz swoją nazwę użytkownika, a na podany przez Ciebie email zostanie wysłany link resetujący hasło:',
'Reset my password'			=> 'Zmień hasło',
'New password'				=> 'Nowe hasło',
'Repeat new password'		=> 'Powtórz nowe hasło',
'Submit new password'		=> 'Zapisz nowe hasło',

// Edit account
'Edit title'				=> 'Jesteś zalogowany i możesz edytować swoje dane',
'Old password'				=> 'Twoje STARE hasło',
'New username'				=> 'Nowa nazwa użytkownika (nazwa użytkownika musi się składać z liter i cyfr, od 2 do 64 znaków.)',
'New email'					=> 'Nowy email',
'currently'					=> 'aktualnie',
'Change username'			=> 'Zmień nazwę użytkownika',
'Change email'				=> 'Zmień email',
'Change password'			=> 'Zmień hasło',

// Logged in
'You are logged in as'		=> 'Jesteś zalogowany jako ',
'Logout'					=> 'Wyloguj',
'Edit user data'			=> 'Edytuj dane',
'Profile picture'			=> 'Twoje zdjęcie profilowe (z serwisu gravatar):'

);
