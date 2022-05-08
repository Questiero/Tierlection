<?php 

    session_start();

    if(!isset($_SESSION['user']) && !$_SESSION['user']["canOrganize"]) {
        header("Location: index.php");
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>TierLection</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="Tierlection.css"/>
</head>
<body>

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
                    <select id="theme" name="theme">

                        <option value="">Sélectionnez un thème</option>

                        <?php 

                            foreach(getThemes() as $theme) {
                                echo "<option value=\"" . $theme[0] . "\">" . $theme[1] . "</option>";
                            }

                        ?>

                    </select>
                </div>
                    
            </div>

            <div id="create">
                <input type="submit" value="Créer"/>
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

                if($startYear < $todayYear || ($startYear == $todayYear && ($startMonth < $todayMonth || ($startMonth == $todayMonth && $startDay < $todayDay)))) {
                    echo "<p class='errors'> Date de début incorrecte. </p>";
                } else {

                    $endYear = intval(substr($_POST["endDate"], 2, -6));
                    $endMonth = intval(substr($_POST["endDate"], 5, -3));
                    $endDay = intval(substr($_POST["endDate"], 8));

                    if($endYear < $todayYear || ($endYear == $todayYear && ($endMonth < $todayMonth || ($endMonth == $todayMonth && $endDay < $todayDay)))) {
                        echo "<p class='errors'> Date de fin incorrecte. </p>";
                    } else {

                        createElection($_POST["name"], $_POST["startDate"], $_POST["endDate"], $_SESSION["user"]["idUser"], $_POST["theme"]);

                    }

                }
                
            }

        ?>

    </div>

</body>
</html>

<?php 

    function getThemes() {

        $datas = array();

        $connection = new PDO(
            "mysql:host=mysql-questiero.alwaysdata.net;dbname=questiero_tierlection",
            "questiero_tl",
            "tierlection"
        );

        $query = "SELECT * FROM itemSet";
        $statement = $connection->prepare($query);
        $statement->execute();

        foreach ($statement as $row) {
            $theme[0] = $row["idSet"];
            $theme[1] = $row["name"];
            array_push($datas, $theme);
        }

        $connection = null;

        return $datas;

    }

    function createElection($name, $startDate, $endDate, $idOrganizator, $idSet) {

        try {

            $connection = new PDO(
                "mysql:host=mysql-questiero.alwaysdata.net;dbname=questiero_tierlection",
                "questiero_tl",
                "tierlection"
            );

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

            // Close connection
            $connection = null;

        } catch(PDOException $e){
            echo $e->getMessage();
        }

    }

?>