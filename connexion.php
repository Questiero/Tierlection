<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>TierLection</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="Tierlection.css"/>
</head>
<body>

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
                <input type="submit" value="Connection"/>
            </div>          
        </form>
    </div>

    <div class="errors">

        <?php

            if(isset($_POST["username"])) {

                $connexionData = getConnexionData($_POST["username"]);

                if(is_null($connexionData)) {
                    echo "Nom d'utilisateur inconnu";
                } else {

                    $dbPassword = $connexionData[1];
                    $password = hash('sha384', $_POST['password'] . $connexionData[0]);

                    if($password == $dbPassword) {

                        $_SESSION["name"] = $_POST["username"];

                        header("Location: <ital>index.php</ital>");

                    } else {
                        echo "Mot de passe inconnu";
                    }

                }
                
            }

        ?>

    </div>

</body>
</html>

<?php

    function getConnexionData($username) {

        $datas = null;

        $connection = new PDO(
            "mysql:host=mysql-questiero.alwaysdata.net;dbname=questiero_tierlection",
            "questiero_tl",
            "tierlection"
        );

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

?>