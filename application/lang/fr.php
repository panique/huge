<?php
/*
 * chaque item lang a la syntaxe suivante:
 *      $lang["nom du répertoire dans views"]["nom de la ligne"]
 * Le nom de la ligne peut être au choix tant que vous puissiez l'écrire dans la page
 * Le nom du répertoire doit être le même que dans "views"
 * Si quelque chose doit être utilisé dans tous les répertoires, il y a le tag ["ALL"] 
 * Ces tags doivent être en majuscules pour éviter que des propriétés ayant le même nom soient ignorées
 *      Ceci veut dire que les noms de lignes doivent bien respecter la casse.
 * 
 * EXCEPTION : $lang[nom de la page]["title"] = Ceci permet à une page d'avoir un titre alternatif.
 * 
 * Le template header.tpl devrait spécifier que les pages sont utf-8
 * Echappez les guillemets anglais comme ceci \"
 */
$lang["ALL"]["TITLE"] = "PHP Login avec Smarty";
$lang["ALL"]["EDIT"] = "Modifier";
$lang["ALL"]["DELETE"] = "Supprimer";


//$lang["index"]["title"] = "titre index seulement";
$lang["index"]["header"] = "Index";
$lang["index"]["box1"]= "Le contenu de cette boîte (tout entre l'entête et le bas de page) provient de  
        views/index/default/index.php, donc c'est la vue index/index.";
$lang["index"]["box2"]= "C'est affiché par la méthode index à l'intérieur avec le controlleur index (dans controllers/index.php) ";
$lang["index"]["geninfo"]= "Information générale à propos de ce framework";
$lang["index"]["frmw1"]= "\"Framework #1000 ? pourquoi ai-je besoin de toi ?\" Il y a beaucoup de bon (et mauvais) frameworks
        pour PHP. La majorité d'entre eux ont un point négatif en commun: Ils n'ont pas un bon système de connexion. Et ceux qui 
        en ont un utilisent des technologies qui ne sont pas modernes: sans vérification courriel, 
        sans sécurité de mot de passe, etc.";

$lang["index"]["frmw2"]= "Ce framework essaie de ";
$lang["index"]["frmw3"]= "combler ce manque avec un système à jour et sécure";
$lang["index"]["frmw4"]= "avec une structure facile à utiliser et facile à comprendre. Si vous ne l'aimez pas, 
        vous êtes libre de fusionner toutes les actions reliées à la connexion dans le framework de votre choix";
$lang["index"]["login1"]= "Allez à la ";
$lang["index"]["login2"]= "page de connexion ";

//$lang["login"]["title"] = "login only title";
$lang["login"]["header"] = "Connexion";
$lang["login"]["orheader"] = "ou";
$lang["login"]["back"] = "Retour à la page connexion";
$lang["login"]["submit"] = "Soumettre";
$lang["login"]["register"] = "S'inscrire";
$lang["login"]["passmin"] = "Nouveau mot de passe (6 caractères min.)";
$lang["login"]["passsecure"] = "À noter: Utiliser une long phrase comme mot de passe est beaucoup plus sécuritaire que \"!c00lPa$\$w0rd\". Voir (en anglais)";
$lang["login"]["passsecurelink"] = "cette discussion sur security.stackoverflow.com";

$lang["login"]["indexlogin"] = "Pseudo (ou courriel)";
$lang["login"]["indexpassword"] = "Mot de passe";
$lang["login"]["indexstaylogged"] = "Rester connecté";
$lang["login"]["indexforgot"] = "Mot de passe oublié";
$lang["login"]["indexfblogin"] = "Connexion avec Facebook";

$lang["login"]["changetype"] = "Changer le type de compte";
$lang["login"]["changetypeexp"] = "Cette page est une démonstration de base du changement du type de compte. 
        L'utilisateur qui click sur ce bouton peut passer de \"membre gratuit\" à \"membre payant\"
        Cette page permet de changer de type de compte comme vous voulez. Dans une vraie application-Web, 
        vous ajouteriez quelque chose comme un système de paiement pour permettre au membre d'altérer son type
        de compte.";
$lang["login"]["changeline1"] = "Cette vue vient de login-controller/changeaccounttype() et appelle la méthode
        login->changeAccountType() dans le modèle login-model.";
$lang["login"]["changecurrent"] = "Votre est type de compte est: ";
$lang["login"]["changeup"] = "Mise à niveau du compte";
$lang["login"]["changedown"] = "Rétrograder le compte";

$lang["login"]["newpass"] = "Entrer un nouveau mot de passe";
$lang["login"]["newpassrepeat"] = "Répeter le mot de passe";
$lang["login"]["newpasssubmit"] = "Sauvegarder le mot de passe";

$lang["login"]["editemail"] = "Changer votre courriel";
$lang["login"]["editemailnew"] = "Nouveau courriel:";

$lang["login"]["edituser"] = "Changer de pseudo";
$lang["login"]["editusernew"] = "Nouveau pseudo";

$lang["login"]["regisuser"] = "Pseudo";
$lang["login"]["regisuserprop"] = "(lettres et chiffres seulement, de 2 à 64 caractères)";
$lang["login"]["regisemail"] = "Courriel";
$lang["login"]["regisemail1"] = "(S.V.P. fournir ";
$lang["login"]["regisemail2"] = "un vrai courriel";
$lang["login"]["regisemail3"] = "vous y receverez un lien d'activation)";
$lang["login"]["regisrepeatpass"] = "Retaper le mot de passe";
$lang["login"]["regiscaptcha1"] = "Taper les caractères dans l'image";
$lang["login"]["regiscaptcha2"] = "S.V.P. prendre note que: Ce captcha est généré quand le tag img en fait la demande à votre 
        serveur avec le lien VOTREURL/login/showcaptcha. Comme cette requête est demandée une coup que l'image est placée, vous 
        ne verrez pas les caractères du captcha dans la variable \$_SESSION[\"captcha\"]. ";
$lang["login"]["regisfb"] = "S'enregistrer avec Facebook";

$lang["login"]["resetpass"] = "Réinitialisation du mot de passe";
$lang["login"]["resetpassalt"] = "Réinitialer le mot de passe";
$lang["login"]["resetpassinst"] = "Entrer le pseudo. Le courriel attaché à ce pseudo recevera dans celui-ci les intructions:";

$lang["login"]["profile"] = "Votre profil";
$lang["login"]["profileuser"] = "Votre pseudo:";
$lang["login"]["profilemail"] = "Votre courriel:";
$lang["login"]["profilegrav"] = "Votre gravatar (sur gravatar.com):";
$lang["login"]["profileavatar"] = "Votre icône (sur le serveur local):";
$lang["login"]["profiletype"] = "Votre type de compte:";

$lang["login"]["avatar"] = "Télécharger une icône";
$lang["login"]["avatarexp"] = "Choisir une image sur le disque dur(sera redimensionner à 44x44 px):";
$lang["login"]["avatarupload"] = "Télécharger";

$lang["login"]["verify"] = "Vérification";

//$lang["note"]["title"] = "note only title";
$lang["note"]["notelist"] = "Liste des notes";
$lang["note"]["notenone"] = "Aucune note. Créez-en!";
$lang["note"]["new"] = "Créez une nouvelle note";
$lang["note"]["newexp"] = "Les \"notes\" sont une exemple de comment créer (create), afficher (read), modifier (update) et
            supprimer (delete) des objets de la base de données. CRUD, en anglais...";
$lang["note"]["newtext"] = "Texte de la nouvelle note: ";

$lang["note"]["edit"] = "Modifier une note";
$lang["note"]["editchange"] = "Modifier le texte: ";
$lang["note"]["editnone"] = "Cette note n'existe pas.";
$lang["note"]["editchange"] = "Modifier";

//$lang["overview"]["title"] = "Overview only title";
$lang["overview"]["header"] = "Survol";
$lang["overview"]["note"] = "Ceci est une démonstration. Assurez-vous de ne pas divulguer les courriel dans un vrai site";
$lang["overview"]["active"] = "Actif:";

$lang["overview"]["indexexp"] = "Cette vue controller/action/view montre tous les utilisateurs du système.
        Vous pourriez utiliser ce code pour monter un système qui utilise les informations de profile d'un 
        ou plusieurs utilisateurs.";
$lang["overview"]["indexnoav"] = "Aucune icône";
$lang["overview"]["indexactive"] = "Actif:";
$lang["overview"]["indexshow"] = "Afficher le profil";
$lang["overview"]["indexnousers"] = "Aucun utilisateur n'a été trouvé";

$lang["overview"]["showpub"] = "Profile public";
$lang["overview"]["showpubexp"] = "Cette vue controller/action/view affiche tous les infos public d'un seul utilisateur.";
$lang["overview"]["shownone"] = "Utilisateur introuvable";

$lang["error"]["title"] = "Erreur!";
$lang["error"]["pagenotfound"] = "Cette page n'existe pas.";

$lang["help"]["title"] = "Page d'aide";
$lang["help"]["header"] = "Aide";
$lang["help"]["index1"] = "Cette vue views/help/index.php et dans help/index. Elle est créée par la méthode index à l'intérieur
        du controlleur  (dans controllers/help.php). Si vous voulez, vous pouvez y ajouter une sous-page en créant une méthode
        dans le controlleur et une vue dans le répertoire views. Par exemple, si vous voulez une FAQ dans l'aide, ajoutez: ";
$lang["help"]["index2"] = "la méthode faq() { \$this->view->render('help/faq'); }";
$lang["help"]["index3"] = " dans controllers/help.php et créez une vue dans  views/default/help/, nommée \"faq.php\".
        Ensuite, naviguez à la page \"help/faq\". Si vous travaillez localement sur localhost, 
        http://localhost/monapplication/ alors cette page se trouve au http://localhost/monapplication/help/faq !
        Essayez-le...";

//$lang["dashboard"]["title"] = "Dashboard only title";
$lang["dashboard"]["header"] = "Tableau de bord";
$lang["dashboard"]["indexlogged"] = "Visible aux membres connectés seulement";
$lang["dashboard"]["index1logout"] = "Déconnectez-vous ";
$lang["dashboard"]["index1"] = "et naviguez à la page /dashboard/ une autre fois. Vous serez redirigé vers /index/ car vous n'êtes pas connecté.";
$lang["dashboard"]["index2"] = "Vous pouvez protéger des gens non-connecté des sections complètes (ici: controllers/dashboard.php)
        en y ajoutant";
$lang["dashboard"]["index3"] = "Auth::handleLogin();";
$lang["dashboard"]["index4"] = " dans le contructeur.";
$lang["dashboard"]["index5"] = "Comme vous êtes connecter, vous pouvez vous diriger ";
$lang["dashboard"]["index6"] = "vers la page survol";


/**
 * Configuration pour: Messages d'erreurs et d'avertissement
 *
 * Dans ce projet, les messages d'erreurs et d'avertissement sont tous appellés FEEDBACK_
 */
$feedback["FEEDBACK_UNKNOWN_ERROR"] = "Une erreur inconnue est survenue!";
$feedback["FEEDBACK_PASSWORD_WRONG_3_TIMES"] = "Vous avez tapé le mauvais mot de passe plus de trois fois en ligne, veuillez attendre 30 secondes et essayés de nouveau";
$feedback["FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET"] = "Votre compte n'est toujours pas activé. Un lien vous a été envoyé par courriel";
$feedback["FEEDBACK_PASSWORD_WRONG"] = "Mauvais mot de passe.";
$feedback["FEEDBACK_USER_DOES_NOT_EXIST"] = "Utilisateur inexistant.";
// The "login failed"-message is a security improved feedback that doesn't show a potential attacker if the user exists or not
$feedback["FEEDBACK_LOGIN_FAILED"] = "Connexion échoué.";
$feedback["FEEDBACK_USERNAME_FIELD_EMPTY"] = "Le champ pseudo était vide.";
$feedback["FEEDBACK_PASSWORD_FIELD_EMPTY"] = "Le champ mot de passe était vide.";
$feedback["FEEDBACK_EMAIL_FIELD_EMPTY"] = "Le champ courriel était vide.";
$feedback["FEEDBACK_EMAIL_AND_PASSWORD_FIELDS_EMPTY"] = "Les champs courriels et mots de passe étaient vides.";
$feedback["FEEDBACK_USERNAME_SAME_AS_OLD_ONE"] = "Vous utilisez déjà ce pseudo. S.V.P. en choisir un autre.";
$feedback["FEEDBACK_USERNAME_ALREADY_TAKEN"] = "Ce peusdo existe déjà. S.V.P. en choisir un autre.";
$feedback["FEEDBACK_USER_EMAIL_ALREADY_TAKEN"] = "Ce courriel existe déjà. S.V.P. en choisir un autre.";
$feedback["FEEDBACK_USERNAME_CHANGE_SUCCESSFUL"] = "Votre pseudo a été changé avec succès.";
$feedback["FEEDBACK_USERNAME_AND_PASSWORD_FIELD_EMPTY"] = "Les champs pseudo et mot de passe sont vides.";
$feedback["FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN"] = "Votre pseudo doit avoir que des lettres de a-Z ou des chiffres. Il doit avoir entre 2 et 64 caractères inclusivement.";
$feedback["FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN"] = "Votre courriel n'est pas valide.";
$feedback["FEEDBACK_EMAIL_SAME_AS_OLD_ONE"] = "Vous utilisez déjà ce courriel. S.V.P. en choisir un autre.";
$feedback["FEEDBACK_EMAIL_CHANGE_SUCCESSFUL"] = "Votre adresse courriel a été changée avec succès.";
$feedback["FEEDBACK_CAPTCHA_WRONG"] = "Le code captcha que vous avez saisie est incorrecte.";
$feedback["FEEDBACK_PASSWORD_REPEAT_WRONG"] = "Votre mot de passe et la confirmation du mot de passe ne sont pas les mêmes.";
$feedback["FEEDBACK_PASSWORD_TOO_SHORT"] = "Le mot de passe doit avoir au minimum 6 caractères.";
$feedback["FEEDBACK_USERNAME_TOO_SHORT_OR_TOO_LONG"] = "Le pseudo doit avoir entre 2 et 64 caractères inclusivement.";
$feedback["FEEDBACK_EMAIL_TOO_LONG"] = "Le courriel ne peut pas avoir plus de 64 caractères.";
$feedback["FEEDBACK_ACCOUNT_SUCCESSFULLY_CREATED"] = "Votre compte a été créé avec succès et nous vous avons envoyé un courriel. Veuillez clicker le lien de VÉRIFICATION à l'intérieur de ce courriel.";
$feedback["FEEDBACK_VERIFICATION_MAIL_SENDING_FAILED"] = "Impossible de créer votre compte. Il y a eu une erreur lors de l'envoi du courriel.";
$feedback["FEEDBACK_ACCOUNT_CREATION_FAILED"] = "Désolé, votre création de compte a échoué. S.V.P. essayer de nouveau.";
$feedback["FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR"] = "Le courriel de vérification n'a pu être envoyé car: ";
$feedback["FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL"] = "Un courriel de vérification a été envoyé avec succès.";
$feedback["FEEDBACK_ACCOUNT_ACTIVATION_SUCCESSFUL"] = "Votre compte est maintenant activé. Vous pouvez désormais vous connecter.";
$feedback["FEEDBACK_ACCOUNT_ACTIVATION_FAILED"] = "Le code de vérification fourni n'existe pas.";
$feedback["FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL"] = "Téléchargement d'icône réussie.";
$feedback["FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE"] = "Seulement les formats JPEG et PNG sont supportés.";
$feedback["FEEDBACK_AVATAR_UPLOAD_TOO_SMALL"] = "L'image téléchargée est trop petite. Le minimum requis est de 100x100 pixels.";
$feedback["FEEDBACK_AVATAR_UPLOAD_TOO_BIG"] = "L'icône est trop grosse. Le maximum est de 5 Mégabytes.";
$feedback["FEEDBACK_AVATAR_FOLDER_DOES_NOT_EXIST_OR_NOT_WRITABLE"] = "Le fichier avatar n'existe pas. Corriger cela avec chmod 775 ou 777.";
$feedback["FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED"] = "Une erreur est survenue lors du téléchargement.";
$feedback["FEEDBACK_PASSWORD_RESET_TOKEN_FAIL"] = "Impossible d'écrire dans la base de données.";
$feedback["FEEDBACK_PASSWORD_RESET_TOKEN_MISSING"] = "Le code de réinitialisation du mot de passe n'existe pas.";
$feedback["FEEDBACK_PASSWORD_RESET_MAIL_SENDING_ERROR"] = "Le courriel de réinitialisation du mot de passe n'a pu être envoyé car: ";
$feedback["FEEDBACK_PASSWORD_RESET_MAIL_SENDING_SUCCESSFUL"] = "Un courriel de réinitialisation du mot de passe a été envoyé.";
$feedback["FEEDBACK_PASSWORD_RESET_LINK_EXPIRED"] = "Votre lien de réinitialisation du mot de passe est expiré. Ces liens sont actifs pendant 1 heure seulement";
$feedback["FEEDBACK_PASSWORD_RESET_COMBINATION_DOES_NOT_EXIST"] = "Code de réinitialisation introuvable";
$feedback["FEEDBACK_PASSWORD_RESET_LINK_VALID"] = "Le code de réinitialisation du mot de passe est valide. Réinitialisez votre mot de passe";
$feedback["FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL"] = "Le mot de passe a été réinitialisé.";
$feedback["FEEDBACK_PASSWORD_CHANGE_FAILED"] = "La réinitialisation du mot de passe a échoué.";
$feedback["FEEDBACK_ACCOUNT_UPGRADE_SUCCESSFUL"] = "La mise à niveau a été complétée avec succès.";
$feedback["FEEDBACK_ACCOUNT_UPGRADE_FAILED"] = "La mise à niveau a échoué.";
$feedback["FEEDBACK_ACCOUNT_DOWNGRADE_SUCCESSFUL"] = "Votre compte a été rétrogradé avec succès.";
$feedback["FEEDBACK_ACCOUNT_DOWNGRADE_FAILED"] = "Votre compte n'a pas pu être rétrogradé.";
$feedback["FEEDBACK_NOTE_CREATION_FAILED"] = "Création de note échouée.";
$feedback["FEEDBACK_NOTE_EDITING_FAILED"] = "Modification de note échouée.";
$feedback["FEEDBACK_NOTE_DELETION_FAILED"] = "Suppression de note échouée.";
$feedback["FEEDBACK_COOKIE_INVALID"] = "Votre cookie de connection automatique est invalide.";
$feedback["FEEDBACK_COOKIE_LOGIN_SUCCESSFUL"] = "Vous avez été reconnecté automatiquement";
$feedback["FEEDBACK_FACEBOOK_LOGIN_NOT_REGISTERED"] = "Ce compte n'existe pas sur ce site. Veuillez vous enregistrer avec Facebook avant.";
$feedback["FEEDBACK_FACEBOOK_EMAIL_NEEDED"] = "Vous devez nous permettre de voir votre courriel pour compléter l'enregistrement.";
$feedback["FEEDBACK_FACEBOOK_UID_ALREADY_EXISTS"] = "Ce ID de Facebook existe déjà dans notre base de données. Connectez-vous avec Facebook";
$feedback["FEEDBACK_FACEBOOK_EMAIL_ALREADY_EXISTS"] = "Votre courriel Facebook existe déjà dans notre base de données. Veuillez vous connecter avec celle-ci.";
$feedback["FEEDBACK_FACEBOOK_USERNAME_ALREADY_EXISTS"] = "Votre pseudo Facebook existe déjà dans notre base de données.";
$feedback["FEEDBACK_FACEBOOK_REGISTER_SUCCESSFUL"] = "Enregistrement avec Facebook effectué avec succès.";
$feedback["FEEDBACK_FACEBOOK_OFFLINE"] = "Impossible de se connecter à Facebook. L'API de Facebook peut être hors-ligne (oui, cela peut se produire à l'occasion).";
$feedback["FEEDBACK_LANGUAGE_NOT_SET"] = "Code de langue introuvable.";
$feedback["FEEDBACK_LANGUAGE_NOT_VALID"] = "Code de langue invalide";
$feedback["FEEDBACK_LANGUAGE_NOT_EXIST"] = "Aucune langue choisie.";
