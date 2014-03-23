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
