<?php

// please note: we can use unencoded characters like ö, é etc here as we use the html5 doctype with utf8 encoding
// in the application's header (in views/_templates/header.php)

$phplogin_lang = array(

// Login & Registration classes
'database_error'			=> 'Problème de connexion à la base de données.',
'empty_username'			=> 'Le nom d\'utilisateur est vide',
'username_exist'			=> 'Désolé, ce nom d\'utilisateur est déjà utilisé. Merci d\'en choisir un autre.',
'invalid_username'			=> 'Nom d\'utilisateur invalide : seul les lettres a-Z et les chiffres sont autorisés, longueur de 2 à 64 caractères',
'empty_password'			=> 'Le mot de passe est vide',
'bad_confirm_password'		=> 'Le 2 mots de passe ne sont pas identiques',
'password_too_short'		=> 'Le mot de passe doit contenir au minimum 6 caractères',
'email_exist'				=> 'Cette adresse email est déjà enregistrée. Merci d\'utiliser le lien "J\'ai oublié mon mot de passe" si vous avez oublié votre mot de passe.',
'invalid_email'				=> 'Cette adresse email n\'a pas un format valide',

// Registration class
'wrong_captcha'				=> 'Captcha was wrong!',
'username_bad_length'		=> 'Le nom d\utilisateur doit contenir entre 2 et 64 caractères',
'empty_email'				=> 'L\'adresse email ne peut pas être vide',
'email_too_long'			=> 'L\'adresse email doit contenir au maximum 64 caractères',
'verification_mail_error'	=> 'Désolé, impossible de vous envoyez le mail de vérification. Votre compte n\'a pas été créé.',
'verification_mail_sent'	=> 'Votre compte utilisateur a été créé et un email de vérification vous a été envoyé. Merci de cliquer sur le LIEN DE VERIFICATION de cet email pour activer votre compte.',
'verification_mail_not_sent'=> 'Impossible de vous envoyez le mail de vérification ! Erreur : ',
'registration_failed'		=> 'Désolé, une erreur s\'est produite durant votre enregistrement. Merci de recommencer.',
'activation_successful'		=> 'Votre compte a été activé ! Vous pouvez maintenant vous connectez !',
'activation_error'			=> 'Désolé, la combinaison id/code de vérification est invalide...',

// Login class
'invalid_cookie'			=> 'Cookie invalide',
'user_not_exist'			=> 'Cet utilisateur n\'existe pas',
'wrong_password'			=> 'Mot de passe incorrect. Essayez à nouveau.',
'account_not_activated'		=> 'Votre compte n\'est pas encore activé. Merci de cliquer sur le lien de confirmation dans le mail d\'enregistrement.',
'logged_out'				=> 'Vous avez été deconnecté.',
'same_username'				=> 'Sorry, that username is the same as your current one. Please choose another one.',
'same_email'				=> 'Sorry, that email address is the same as your current one. Please choose another one.',
'username_changed'			=> 'Votre nom d\'utilisateur a été changé. Votre nom d\'utilisateur est maintenant ',
'username_change_failed'	=> 'Désolé, une erreur s\'est produite durant l\'enregistrement de votre nouveau nom d\'utiisateur',
'email_changed'				=> 'Votre adresse email a été changée. Votre addresse email est maintenant ',
'email_change_failed'		=> 'Désolé, une erreur s\'est produite durant l\'enregistrement de votre nouvelle adresse email',
'password_changed'			=> 'Votre mot de passe a été changé !',
'password_changed_failed'	=> 'Désolé, une erreur s\'est produite durant l\'enregistrement de votre nouveau mot de passe.',
'wrong_old_password'		=> 'Votre ancien mot de passe n\'est pas correct.',
'password_mail_sent'		=> 'Le mail de réinitialisation de votre mot de passe a été envoyé !',
'password_mail_not_sent'	=> 'Une erreur s\'est produite durant l\'envoi du mail de réinitialisation de votre mot de passe ! Erreur : ',
'reset_link_has_expired'	=> 'Ce lien de réinitialisation du mot de passe est expiré. Il n\'est actif que pendant une heure.',
'empty_link_parameter'		=> 'Paramètre du lien incorrect.',

// Login form
'username'					=> 'Nom d\'utilisateur',
'password'					=> 'Mot de passe',
'remember_me'				=> 'Resté connecté (pour 2 semaines)',
'log_in'					=> 'Connexion',
'register_new_account'		=> 'Créer un nouveau compte',
'i_forgot_my_password'		=> 'J\'ai oublié mon mot de passe',

// Register form
'register_username'			=> 'Nom d\'utilisateur (seulement des lettres et des chiffres, de 2 à 64 caractères)',
'register_email'			=> 'E-mail (merci de fournir une adresse valide car vous recevrez un mail de vérification avec un lien d\'activation)',
'register_password'			=> 'Mot de passe (min. 6 caractères)',
'register_password_repeat'	=> 'Mot de passe repéter',
'register_captcha'			=> 'Saisir les caractères de l\'image ci-dessus',
'Register'					=> 'S\'enregistrer',
'back_to_login'				=> 'Revenir à la page de connexion',

// password_reset_request
'password_reset_request'	=> 'Démander la réinitialisation de mon mot de passe. Saisir votre nom d\'utilisateur et vous recevrez un mail avec les instructions :',
'reset_my_password'			=> 'Réinitialiser mon mot de passe',
'new_password'				=> 'Nouveau mot de passe',
'repeat_new_password'		=> 'Repéter le nouveau mot de passe',
'submit_new_password'		=> 'Soumettre le nouveau mot de passe',

// Edit account
'edit_title'			=> 'Vous êtes connecté et pouvez modifier vos informations de connexion ici',
'old_password'				=> 'Ancien mot de passe',
'new_username'				=> 'Nouveau nom d\'utilisateur (seul les lettres a-Z et les chiffres sont autorisés, longueur de 2 à 64 caractères)',
'new_email'					=> 'Nouvelle adresse email',
'currently'					=> 'actuellement',
'change_username'			=> 'Changer le nom d\'utilisateur',
'change_email'				=> 'Changer l\'adresse email',
'change_password'			=> 'Changer le mot de passe',

// Logged in
'you_are_logged_in_as'		=> 'Vous êtes connecté en tant que : ',
'logout'					=> 'Déconnexion',
'edit_user_data'			=> 'Modifier mon compte',
'profile_picture'			=> 'Photo de profil (depuis gravatar) :'

);
