<?php
session_start();

require "conf/autoload.php";
require "conf/global.php";

// Afficher le contenu d'une superglobale (à décommenter si besoin)
// var_dump($_GET);
// var_dump($_POST);
// var_dump($_SESSION);

// On vérifie qu'une route est bien transmise en paramètre, si ce n'est pas le cas, on lui donne une valeur par défaut
// pour éviter que ça "casse" à l'étape suivante
$route = (isset($_REQUEST['route'])) ? $_REQUEST['route'] : 'home';

// switch($route) {
//     case 'home' : home(); // affichage
//     break;
//     case 'membre' : membre(); // affichage
//     break;
//     case 'modify' : modify();
//     break;
//     case 'modify_tache' : modify_tache();
//     break;
//     case 'insert_user': insert_user();
//     break;
//     case 'connect_user': connect_user();
//     break;
//     case 'deconnexion': deconnexion();
//     break;
//     case 'insert_tache' : insert_tache();
//     break;
//     case 'delete_tache' : delete_tache();
//     break;
//     case 'delete_user' : delete_user();
//     break;
//     default : home();
// }

try {
    $view = $route();
} catch (Error $e) {
    $view = home();
}

// Fonctionnalités d'affichage
function home()
{
    return ['view' => 'views/home.html'];
}

function membre()
{
    if (isset($_SESSION['user'])) {

        $tache = new Models\Tache();
        $tache->setIdUtilisateur($_SESSION['user']['idUtilisateur']);
        $taches = $tache->selectByUser();

        if (isset($_REQUEST['idTache'])) {
            $tache->setId($_REQUEST['idTache']);
            $item = $tache->select();



            return ['view' => 'views/membre.php', 'datas' => [
                'taches' => $taches,
                'item' => $item
            ]];
        }
    } else {
        header("Location:index.php?route=home");
    };
    return ['view' => 'views/membre.php', 'datas' => [
        'taches' => $taches,
    ]];
}

function modify()
{
    if (isset($_SESSION['user'])) {

        $tache = new Models\Tache();
        $tache->setIdUtilisateur($_SESSION['user']['idUtilisateur']);
        $tache->setId($_REQUEST['idTache']);
        $item = $tache->select();
        $_SESSION['token']['id_tache'] = mkToken($tache->getId());
        var_dump($_SESSION);
        $view = 'views/modify.php';
        return ['view' => $view, 'datas' => [
            'item' => $item
        ]];
    } else {
        header("Location:index.php?route=home");
    }
}

// Fonctionnalités de traitement, redirigées
function insert_user()
{
    var_dump($_POST);
    // Première verif : Si les "champs" du formulaire ont tous bien été renseignés
    if (!empty($_POST['pseudo']) && !empty($_POST['passwd']) && !empty($_POST['passwd2'])) {

        // Je vérifie que les deux mots de passe entrés correspondent
        if ($_POST['passwd'] === $_POST['passwd2']) {

            if (preg_match("#^[a-zA-Z'àâäéèêïôöëùûüçÀÂÉÈÔÙÛÇ\s-]+$#", $_POST['nom'])) {
                $_SESSION['validerrors']['nom'] = "Votre nom n'est pas valide";
            }

            if (!isset($_SESSION['validerrors'])) {
                // Dans ce cas, j'instancie un nouvel objet utilisateur, et lui renseigne ses propriétés
                $utilisateur = new Models\Utilisateur();
                $utilisateur->setPseudo($_POST['pseudo']);
                $utilisateur->setPasswd(password_hash($_POST['passwd'], PASSWORD_DEFAULT));
                $utilisateur->setNom($_POST['nom']);
                $utilisateur->setPrenom($_POST['prenom']);
                $utilisateur->setMail($_POST['mail']);
                $utilisateur->insert();
                var_dump($utilisateur);
            }
        }
    }

    header("Location:index.php?route=home");
}

function connect_user()
{

    $utilisateur = new Models\Utilisateur();
    $utilisateur->setPseudo($_POST['pseudo']);

    $utilisateur->verify_user();
    if (password_verify($_POST['passwd'], $utilisateur->getPasswd())) {
        // Dans ce cas on est connecté, on place donc l'utilisateur en session
        $_SESSION['user']['idUtilisateur'] = $utilisateur->getIdUtilisateur();
        $_SESSION['user']['pseudo'] = $utilisateur->getPseudo();
        // Et on le redirige sur son espace
        header("Location:index.php?route=membre");
    } else {
        header("Location:index.php?route=home");
    }
}

function deconnexion()
{
    $_SESSION = array();
    session_destroy();
    header("Location:index.php?route=home");
}

function insert_tache()
{

    $tache = new Models\Tache();
    $tache->setDescription($_POST['description']);
    $tache->setDeadline($_POST['date_limite']);
    $tache->setIdUtilisateur($_SESSION['user']['idUtilisateur']);

    $tache->insert();
    header("Location:index.php?route=membre");
}

function modify_tache()
{
    if (isset($_REQUEST['idTache']) && chkToken($_REQUEST['idTache'])) {
        $tache = new Models\Tache();
        $tache->setDescription($_POST['description']);
        $tache->setDeadline($_POST['date_limite']);
        $tache->setId($_REQUEST['idTache']);
        $tache->setIdUtilisateur($_SESSION['user']['idUtilisateur']);
        $tache->update();
    }
    header("Location:index.php?route=membre");
}

function delete_tache()
{
    $tache = new Models\Tache();
    $tache->setId($_REQUEST['idTache']);
    $tache->setIdUtilisateur($_SESSION['user']['idUtilisateur']);

    $tache->delete();
    header("Location:index.php?route=membre");
}

function delete_user()
{
    $utilisateur = new Models\Utilisateur();
    $utilisateur->setIdUtilisateur($_SESSION['user']['idUtilisateur']);
    $utilisateur->setPseudo($_SESSION['user']['pseudo']);
    $utilisateur->delete();
    session_destroy();
    header("Location:index.php?route=membre");
}




// ----- AFFICHAGE -------
require "template.php";
