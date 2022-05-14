<?php 

    session_start();

    if(isset($_SESSION['user'])) {
        header("Location: index.php");
    }

?>

<!DOCTYPE html>
<html>
<head>
  <title>Enregistrement</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="TierLection.css"/>
</head>

<body>

    <div id="titre">
        <img id="tierlection-logo" src="data/TierLection-Logo.png">
    </div>

    <div class="panel">
        <form action="inscription.php" method="post">
            <div id="valeurs">
                <div>
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" size="20"/>
                </div>
                <div>
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password"/>
                </div>
                <div>
                    <label for="confirm">Confirmation</label>
                    <input type="password" id="confirm" name="confirm"/>
                </div>
                <div id="canOrganize">
                    <label for="canOrganize">Souhaitez-vous organiser des élections ?</label>
                    <input type="checkbox" id="canOrganize" value="canOrganize" name="canOrganize"/>
                </div>

            </div>

            <div id="inscription">
                <input type="submit" value="S'inscrire"/>
            </div> 

        </form>
    </div>

    <div class="messages">

        <?php

            if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["confirm"])) {

                $username = $_POST["username"];
                $password = $_POST["password"];
                $confirm = $_POST["confirm"];

                // Vérification de la validité des données saisies
                $valid = true;

                if (strlen($username) < 4) {
                    $valid = false;
                    echo "<p class='errors'> Le nom d'utilisateur est trop court (minimum 4 caractères). </p>";
                }

                if (strlen($password) < 8) {
                    $valid = false;
                    echo "<p class='errors'> Le mot de passe est trop court (minimum 8 caractères). </p>";
                }

                if ($password != $confirm) {
                    $valid = false;
                    echo "<p class='errors'> Le mot de passe et sa confirmation ne sont pas identiques.</p>";
                }

                if(isset($_POST["canOrganize"])) {
                    $canOrganize = true;
                } else {
                    $canOrganize = false;
                }


                if($valid) {

                    // Génération d'un sel et hachage du mot de passe
                    $salt = generateSalt(10);
                    $hash = hash('sha384', $password.$salt);

                    if(!checkUsername($username)) {
                        echo "<p class='errors'> Nom d'utilisateur indisponible. </p>";
                    } else {

                        // Insertion de l'utilisateur dans la base de donnée
                        createUser($username, $hash, $salt, $canOrganize);

                        // Redireciton sur la page de connection
                        header("Location: connexion.php");

                    }

                }

            }

        ?>

    </div>

    <script type="text/javascript" src="logoIndex.js"></script>

</body>
</html>

<?php 

    // Génération d'une chaine de caractère aléatoire de taille $size
    function generateSalt($size) {

        $chars = "0123456789abcdefghijklmnopqrstuvABCDEFGHIJKLMNOPQRSTUV";

        $salt = "";

        for ($i = 0; $i < $size; $i++) {
            $salt .= $chars[rand(0, strlen($chars)-1)];
        }

        return $salt;

    }

    // Vérificaiton que le nom d'utilisateur n'est pas déjà pris
    function checkUsername($username) {

        require 'base.php';

        $result = false;

        $query = "SELECT COUNT(*) FROM user WHERE username = :username";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":username", $username, PDO::PARAM_STR);
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            if($row['COUNT(*)']==0) {
                $result = true;
            }
        }

        return $result;

    }

    // Insertion d'un nouvel utilisateur dans la base de donnée
    function createUser($username, $password, $salt, $canOrganize) {

        require 'base.php';

        // Creation user
        $query = "INSERT INTO user (username, password, salt, canOrganize) VALUES (:username, :password, :salt, :canOrganize)";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":username", $username, PDO::PARAM_STR);
        $statement->bindValue(":password", $password, PDO::PARAM_STR);
        $statement->bindValue(":salt", $salt, PDO::PARAM_STR);
        $statement->bindValue(":canOrganize", $canOrganize, PDO::PARAM_BOOL);

        $statement->execute();

        // Get user ID
        $query = "SELECT idUser FROM user WHERE username = :username";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":username", $username, PDO::PARAM_STR);
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            $idUser = $row['idUser'];
        }

        return $idUser;

    }

 ?>