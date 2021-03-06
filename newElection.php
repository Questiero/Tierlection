<?php 

    session_start();

    require 'base.php';

    if(!isset($_SESSION['user']) && !$_SESSION['user']["canOrganize"]) {
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
        <h2>Nouveau vote</h2>
        <div id="profile">
                    <form action="profile.php">
                        <button type="submit">Mon profil</button>  
                    </form>
                    <form action="disconnect.php">
                        <button type="submit">Déconnexion</button>
                    </form>

    </div>

    <div class="panel">
        <form action="newElection.php" method="post">
            <div id="valeurs">
                <div>
                    <label for="name">Nom de l'élection</label>
                    <input type="text" id="name" name="name" size="20"/>
                </div>
                <div>
                    <label for="startDate">Date de début</label>
                    <input type="date" id="startDate" name="startDate"/>
                </div>
                <div>
                    <label for="endDate">Date de fin</label>
                    <input type="date" id="endDate" name="endDate"/>
                </div>
                <div>
                    <label for ="theme">Theme</label>
                    <select id="theme" name="theme" for="newTheme">

                        <option value="">Sélectionnez un thème</option>

                        <?php 

                            // Affichage des thèmes disponibles
                            foreach(getThemes() as $theme) {
                                echo "<option value=\"" . $theme["idSet"] . "\">" . $theme["name"] . "</option>";
                            }

                        ?>

                    </select>
                    <div id="newTheme">Cliquez ici pour ajouter un thème</div>
                </div>
                    
            </div>

            <div id="create">
                <button type="submit">Créer</button>
            </div>          
        </form>
    </div>

    <div class="messages">

        <?php

            if(isset($_POST["name"]) && isset($_POST["startDate"]) && isset($_POST["endDate"]) && isset($_POST["theme"])) {

                $todayYear = intval(date('y'));
                $todayMonth = intval(date('m'));
                $todayDay = intval(date('d'));

                $startYear = intval(substr($_POST["startDate"], 2, -6));
                $startMonth = intval(substr($_POST["startDate"], 5, -3));
                $startDay = intval(substr($_POST["startDate"], 8));

                // Vérification de la validité des dates
                if($startYear < $todayYear || ($startYear >= $todayYear && ($startMonth < $todayMonth || ($startMonth >= $todayMonth && $startDay < $todayDay)))) {
                    echo "<p class='errors'> Date de début incorrecte. </p>";
                } else {

                    $endYear = intval(substr($_POST["endDate"], 2, -6));
                    $endMonth = intval(substr($_POST["endDate"], 5, -3));
                    $endDay = intval(substr($_POST["endDate"], 8));

                    if(($endYear < $todayYear || ($endYear >= $todayYear && ($endMonth < $todayMonth || ($endMonth >= $todayMonth && $endDay < $todayDay)))) || $endYear > $startYear || ($endYear <= $startYear && ($endMonth > $startMonth || ($endMonth <= $startMonth && $endMonth > $startDay)))) {
                        echo "<p class='errors'> Date de fin incorrecte. </p>";
                    } else {

                        // Insertion de l'élection dans la base de données
                        $idElection = createElection($_POST["name"], $_POST["startDate"], $_POST["endDate"], $_SESSION["user"]["idUser"], $_POST["theme"]);

                        // Insertion d'un vote pour chaque item dans la base de données
                        foreach(getIdItems($idElection) as $item) {
                            createVotes($idElection, $item["idItem"]);
                        }

                        // Redirection vers la page de l'élection
                        header("Location: electionPage.php?idElection=".$idElection);

                    }

                }
                
            }

        ?>

    </div>

    <script type="text/javascript" src="logoIndex.js"></script>
    <script type="text/javascript">
        
        // Redirection vers la page de création d'un nouveau thème
        document.getElementById("newTheme").addEventListener("click", function() {window.location.href = "newTheme.php";});

    </script>

</body>
</html>

<?php 

    // Récupération de la liste des thèmes disponibles
    function getThemes() {

        require 'base.php';

        $datas = array();

        $query = "SELECT idSet, name FROM itemSet";
        $statement = $connection->prepare($query);
        $statement->execute();

        foreach ($statement as $row) {
            $theme = [
                "idSet" => $row["idSet"],
                "name" => $row["name"]];
            array_push($datas, $theme);
        }

        return $datas;

    }

    // Insertion d'une éleciton dans la base de données, retourne son id
    function createElection($name, $startDate, $endDate, $idOrganizator, $idSet) {

        require 'base.php';

        // Creation election
        $query = "INSERT INTO election (name, startDate, endDate, idOrganizator, idSet) VALUES (:name, :startDate, :endDate, :idOrganizator, :idSet)";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":name", $name, PDO::PARAM_STR);
        $statement->bindValue(":startDate", $startDate, PDO::PARAM_STR);
        $statement->bindValue(":endDate", $endDate, PDO::PARAM_STR);
        $statement->bindValue(":idOrganizator", $idOrganizator, PDO::PARAM_INT);
        $statement->bindValue("idSet", $idSet, PDO::PARAM_INT);

        $statement->execute();

        // Get election ID
        $query = "SELECT COUNT(*) FROM election";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            $idElection = $row['COUNT(*)'];
        }

        return $idElection;

    }

    // Récupération des items en fonction du thème choisi pour l'élection
    function getIdItems($idElection) {

        require 'base.php';

        $query = "SELECT i.idItem FROM item i, election e WHERE e.idElection = :idElection AND i.idSet = e.idSet";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idElection", $idElection, PDO::PARAM_STR);
        $statement->execute();

        $items = array();
        foreach($statement as $row) {
            array_push($items, ["idItem" => $row["idItem"]]);
        }

        return $items;

    }

    // Insertion d'un vote dans la base de données
    function createVotes($idElection, $idItem) {

        require 'base.php';

        $query = "INSERT INTO vote (idElection, idItem, nbrVotes) VALUES (:idElection, :idItem, 0)";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idElection", $idElection, PDO::PARAM_INT);
        $statement->bindValue(":idItem", $idItem, PDO::PARAM_INT);

        $statement->execute();

    }

?>