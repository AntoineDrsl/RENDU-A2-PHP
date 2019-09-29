<?php
require __DIR__ . "/vendor/autoload.php";

## ETAPE 0

## CONNECTEZ VOUS A VOTRE BASE DE DONNEE
try {
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=fight;charset=utf8', 'root', '');
} catch (Exception $e) {
    exit('Erreur: ' . $e->getMessage());
}

### ETAPE 1

####CREE UNE BASE DE DONNEE AVEC UNE TABLE PERSONNAGE, UNE TABLE TYPE
/*
 * personnages
 * id : primary_key int (11)
 * name : varchar (255)
 * atk : int (11)
 * pv: int (11)
 * type_id : int (11)
 * stars : int (11)
 */

/*
 * types
 * id : primary_key int (11)
 * name : varchar (255)
 */

 // FAIS A LA MAIN, MAIS JE METS QUAND MEME LE CODE ICI: 
// $create_perso = $bdd->prepare("CREATE TABLE personnages (
//     id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
//     name VARCHAR(255),
//     atk INT(11),
//     type_id INT(11),
//     pv INT(11),
//     stars INT(11)
// )");
// $create_perso->execute();

// $create_type = $bdd->prepare("CREATE TABLE types (
//     id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
//     name VARCHAR(255),
//     bonus INT(11)
// )");
// $create_type->execute(); 

#######################
## ETAPE 2

#### CREE DEUX LIGNE DANS LA TABLE types
# une ligne avec comme name = feu
# une ligne avec comme name = eau


//FAIS A LA MAIN, MAIS JE METS LE CODE ICI:
// $new_type = $bdd->prepare("INSERT INTO types (name, bonus) VALUES (:name)");

// $name = 'feu';
// $bonus = 10;
// $new_type->execute(["name" => $name]);

// $name = 'eau';
// $bonus = 20;
// $new_type->execute(["name" => $name]);

#######################
## ETAPE 3

# AFFICHER DANS LE SELECTEUR (<select name="" id="">) tout les types qui sont disponible (cad tout les type contenu dans la table types)
$search_types = $bdd->query("SELECT name FROM types");
$all_types = $search_types->fetchAll();
//Voir HTML

#######################
## ETAPE 4

# ENREGISTRER EN BASE DE DONNEE LE PERSONNAGE, AVEC LE BON TYPE ASSOCIER

//On demande le nom de tous les perso de la bdd pour vérifier que le perso créé n'existe pas déjà
$search_perso = $bdd->query("SELECT name FROM personnages");
$all_perso = $search_perso->fetchAll(PDO::FETCH_ASSOC);

//On initialise la variable $message_info
$message_info = "";

//On insère le perso créé
if (isset($_POST['name'], $_POST['atk'], $_POST['pv'], $_POST['type'])) {
    $name = ucfirst(htmlspecialchars($_POST['name']));
    $atk = htmlspecialchars($_POST['atk']);
    $pv = htmlspecialchars($_POST['pv']);
    $type = htmlspecialchars($_POST['type']);
    if (!empty($name) && !empty($atk) && !empty($pv) && !empty($type)) {
        if (!in_array(["name" => $name], $all_perso)) {
            $prepare_perso = $bdd->prepare("INSERT INTO personnages (name, atk, pv, type_id) VALUES (
                :name, 
                :atk, 
                :pv, 
                (SELECT id FROM types WHERE name = :type)
            )");
            $new_perso = $prepare_perso->execute([':name' => $name, ':atk' => $atk, ':pv' => $pv, ':type' => $type]);
            $message_info = "Le personnage " . $name . " a bien été créé.";
        } else {
            $message_info = 'Ce personnage existe déjà';
        }
    } else {
        $message_info = 'Veuillez renseigner tous les champs';
    }
} else {
    $message_info = 'Veuillez renseigner tous les champs';
}

#######################
## ETAPE 5
# AFFICHER LE MSG "PERSONNAGE ($name) CREER"

//Voir au-dessus

#######################
## ETAPE 6

# ENREGISTRER 5 / 6 PERSONNAGE DIFFERENT


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
<h1>Acceuil</h1>
<div class="w-100 mt-5">
    <form action="" method="POST" class="form-group">
        <div class="form-group col-md-4">
            <label for="">Nom du personnage</label>
            <input type="text" class="form-control" placeholder="Nom" name="name">
        </div>

        <div class="form-group col-md-4">
            <label for="">Attaque du personnage</label>
            <input type="text" class="form-control" placeholder="Atk" name="atk">
        </div>
        <div class="form-group col-md-4">
            <label for="">Pv du personnage</label>
            <input type="text" class="form-control" placeholder="Pv" name="pv">
        </div>
        <div class="form-group col-md-4">
            <label for="">Type</label>
            <select name="type" id="">
                <option value="" selected disabled>Choissisez un type</option>
                <?php foreach ($all_types as $type) { ?>
                <option value="<?= $type['name'] ?>"><?= $type['name'] ?></option>
                <?php } ?>
            </select>
        </div>
        <button class="btn btn-primary">Enregistrer</button>
    </form>
</div>
<?= $message_info ?>

</body>
</html>
