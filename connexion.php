<?php 

    session_start();

    if(isset($_SESSION['user'])) {
        header("Location: index.php");
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>TierLection</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="TierLection.css"/>
</head>
<body>

    <div id="titre">
        <img id="tierlection-logo" src="data/TierLection-Logo.png">
    </div>

    <div class="panel">
        <form action="connexion.php" method="post">
            <div id="valeurs">
                <div>
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" id="username" name="username" size="20"/>
                </div>
                <div>
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password"/>
                </div>
                    
            </div>

            <div id="connection">
                <button type="submit">Connexion</button>
            </div>          
        </form>
    </div>

    <div class="messages">

        <?php

            if(isset($_POST["username"]) && isset($_POST["password"])) {

                // Récupération des informations de connection de l'utilisateur
                $connexionData = getConnexionData($_POST["username"]);

                if(is_null($connexionData)) {
                    echo "<p class='errors'> Nom d'utilisateur inconnu </p>";
                } else {

                    $dbPassword = $connexionData[1];
                    // Hachage et salage du mot de passe fourni par l'utilisateur
                    $password = hash('sha384', $_POST['password'] . $connexionData[0]);

                    // Comparaison du mot de passe entré avec celui dans la base de donnée
                    if($password == $dbPassword) {

                        // Stockage des données de l'utilisateur dans une variable de session pour maintenant la connexion
                        $_SESSION["user"] = getUserData($_POST["username"]);

                        // Redirection vers la page d'accueil
                        header("Location: index.php");

                    } else {
                        echo "<p class='errors'> Mot de passe incorrect </p>";
                    }

                }
                
            }

        ?>

    </div>

    <script type="text/javascript" src="logoIndex.js"></script>

</body>
</html>

<?php

    // Récupération du mot de passe et du sel de l'utilisateur
    function getConnexionData($username) {

        require 'base.php';

        $datas = null;

        $query = "SELECT salt, password FROM user WHERE username = :username";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":username", $username, PDO::PARAM_STR);
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            $datas[0] = $row['salt'];
            $datas[1] = $row['password'];
        }

        return $datas;

    }

    // Récupération des données de l'utilisateur
    function getUserData($username) {

        require 'base.php';

        $datas = null;

        $query = "SELECT idUser, username, canOrganize FROM user WHERE username = :username";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":username", $username, PDO::PARAM_STR);
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            $datas = [
                "idUser" => $row['idUser'],
                "username" => $row['username'],
                "canOrganize" => $row['canOrganize']];
        }

        return $datas;

    }

?>