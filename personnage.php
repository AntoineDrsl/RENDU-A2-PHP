<?php
require __DIR__ . "/vendor/autoload.php";

## ETAPE 0

## CONNECTEZ VOUS A VOTRE BASE DE DONNEE

try {
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=fight;charset=utf8', 'root', '');
} catch (Exception $e) {
    exit('Erreur: ' . $e->getMessage());
}

## ETAPE 1

## RECUPERER TOUT LES PERSONNAGES CONTENU DANS LA TABLE personnages
$search_perso = $bdd->query("SELECT * FROM personnages");
$all_perso = $search_perso->fetchAll(PDO::FETCH_ASSOC);

## ETAPE 2

## LES AFFICHERS DANS LE HTML
## AFFICHER SON NOM, SON ATK, SES PV, SES STARS

//Voir HTML

## ETAPE 3

## DANS CHAQUE PERSONNAGE JE VEUX POUVOIR APPUYER SUR UN BUTTON OU IL EST ECRIT "STARS"

## LORSQUE L'ON APPUIE SUR LE BOUTTON "STARS"

## ON SOUMET UN FORMULAIRE QUI METS A JOURS LE PERSONNAGE CORRESPONDANT (CELUI SUR LEQUEL ON A CLIQUER) EN INCREMENTANT LA COLUMN STARS DU PERSONNAGE DANS LA BASE DE DONNEE


#######################
## ETAPE 4
# AFFICHER LE MSG "PERSONNAGE ($name) A GAGNER UNE ETOILES"

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rendu Php</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<nav class="nav mb-3">
    <a href="./rendu.php" class="nav-link">Acceuil</a>
    <a href="./personnage.php" class="nav-link">Mes Personnages</a>
    <a href="./combat.php" class="nav-link">Combats</a>
</nav>

<h1>Mes personnages</h1>
<div class="w-100 mt-5">

<ul>
<?php foreach ($all_perso as $perso) {?>
    <li>
        <p>Nom: <?= $perso['name'] ?></p>
        <p>Attaque: <?= $perso['atk'] ?></p>
        <p>PV: <?= $perso['pv'] ?></p>
        <?php   $message_stars = "";
                if (isset($_POST['stars' . $perso['id']])) {
                    if ($perso['stars'] < 5) {
                        $perso['stars']++;
                        $prepare_stars = $bdd->prepare("UPDATE personnages SET stars = :stars WHERE name = :name");
                        $new_stars = $prepare_stars->execute([':stars' => $perso['stars'], ':name' => $perso['name']]);
                        $message_stars = $perso['name'] . ' a gagné 1 étoile !';
                    } else {
                        $message_stars = "Un personnage ne peut pas avoir plus de 5 étoiles.";
                    }
                } ?>
        <p>Stars: <?php if ($perso['stars'] > 0) { echo $perso['stars']; } else { echo " Aucune étoile :("; } ?></p>
        <form action="" method="POST">
            <button class="btn btn-warning" name="stars<?= $perso['id'] ?>">Stars</button>                       
        </form>
        <?= $message_stars ?>
    </li>
    <hr>
<?php } ?>
</ul>

</div>

</body>
</html>
