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

## POUVOIR SELECTIONER UN PERSONNE DANS LE PREMIER SELECTEUR
//Voir HTML

## ETAPE 2

## POUVOIR SELECTIONER UN PERSONNE DANS LE DEUXIEME SELECTEUR
//Voir HTML

## ETAPE 3

## LORSQUE LON APPPUIE SUR LE BOUTON FIGHT, RETIRER LES PV DE CHAQUE PERSONNAGE PAR RAPPORT A LATK DU PERSONNAGE QUIL COMBAT

$message_fight = "";
$message_fighter1 = "";
$message_fighter2 = "";

if (isset($_POST['perso1'], $_POST['perso2'])) {
    $fighter1 = htmlspecialchars($_POST['perso1']);
    $fighter2 = htmlspecialchars($_POST['perso2']);
    if ($fighter1 != $fighter2) {
        if(!empty($fighter1) && !empty($fighter2)) {
            //On cherche les infos des combattants
            $search_fighters = $bdd->prepare("SELECT * FROM personnages WHERE name IN (:fighter1, :fighter2)");
            $search_fighters->execute([':fighter1' => $fighter1, ':fighter2' => $fighter2]);
            $fighters = $search_fighters->fetchAll(PDO::FETCH_ASSOC);

            //On stock les infos de chaque combattant
            $fighter1_name = $fighters[0]['name'];
            $fighter1_atk = $fighters[0]['atk'];
            $fighter1_pv = $fighters[0]['pv'];

            $fighter2_name = $fighters[1]['name'];
            $fighter2_atk = $fighters[1]['atk'];
            $fighter2_pv = $fighters[1]['pv'];

            //On les fait se combattre
            $fighter1_pv = $fighter1_pv - $fighter2_atk;
            if ($fighter1_pv >= 10) {
                $message_fighter1 = $fighter2_name . ' inflige ' . $fighter2_atk . ' dégâts à son adversaire. Il reste ' . $fighter1_pv . ' à ' . $fighter1_name . '.';
            } else {
                $message_fighter1 = $fighter1_name . ' est K.O ';
            }
            $prepare_pv = $bdd->prepare("UPDATE personnages SET pv = :fighter1_pv WHERE name = :fighter1_name");
            $new_pv = $prepare_pv->execute([':fighter1_pv' => $fighter1_pv, ':fighter1_name' => $fighter1_name]);

            $fighter2_pv = $fighter2_pv - $fighter1_atk;
            if ($fighter2_pv >= 10) {
                $message_fighter2 = $fighter1_name . ' inflige ' . $fighter1_atk . ' dégâts à son adversaire. Il reste ' . $fighter2_pv . ' à ' . $fighter2_name . '.';
            } else {
                $message_fighter2 = $fighter2_name . ' est K.O ';
            }
            $prepare_pv = $bdd->prepare("UPDATE personnages SET pv = :fighter2_pv WHERE name = :fighter2_name");
            $new_pv = $prepare_pv->execute([':fighter2_pv' => $fighter2_pv, ':fighter2_name' => $fighter2_name]);


        } else {
            $message_fight = "Veuillez renseigner tous les champs";
        }
    } else {
        $message_fight = "Un personnage ne peut pas se combattre lui-même !";
    }
} else {
    $message_fight = "Veuillez renseigner tous les champs";
}

## ETAPE 4

## UNE FOIS LE COMBAT LANCER (QUAND ON APPPUIE SUR LE BTN FIGHT) AFFICHER en dessous du formulaire
# pour le premier perso PERSONNAGE X (name) A PERDU X PV (l'atk du personnage d'en face)
# pour le second persoPERSONNAGE X (name) A PERDU X PV (l'atk du personnage d'en face)

## ETAPE 5

## N'AFFICHER DANS LES SELECTEUR QUE LES PERSONNAGES QUI ONT PLUS DE 10 PV

//On prend le nom et les pv de tous les personnages
$search_perso = $bdd->query("SELECT name, pv FROM personnages");
$all_perso = $search_perso->fetchAll(PDO::FETCH_ASSOC);

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
<h1>Combats</h1>
<div class="w-100 mt-5">

    <form action="" method="POST">
        <div class="form-group">
            <select name="perso1" id="">
            <option value="" selected disabled>Personnage 1</option>
            <?php foreach ($all_perso as $perso) if ($perso['pv'] >= 10) {{ ?>
            <option value="<?= $perso['name'] ?>"><?= $perso['name'] ?></option>
            <?php }} ?>
            </select>
        </div>
        <div class="form-group">
            <select name="perso2" id="">
            <option value="" selected disabled>Personnage 2</option>
            <?php foreach ($all_perso as $perso) if ($perso['pv'] >= 10) {{ ?>
            <option value="<?= $perso['name'] ?>"><?= $perso['name'] ?></option>
            <?php }} ?>
            </select>
        </div>

        <button class="btn btn-danger">Fight</button>
    </form>
    <?= $message_fight . "<br>" . $message_fighter1 . "<br>" . $message_fighter2 ?>

</div>

</body>
</html>
