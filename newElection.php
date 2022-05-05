<?php 
    session_start();
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
                    <input id="theme" name="theme" list="themes">
                    <datalist id="themes">

                        <?php 

                            foreach(getThemes() as $theme) {
                                echo "<option value=\"" . $theme . "\">";
                            }

                        ?>

                    </datalist>
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


                
            }

        ?>

    </div>

</body>
</html>

<?php 

    function getThemes() {

        $datas = null;

        $connection = new PDO(
            "mysql:host=mysql-questiero.alwaysdata.net;dbname=questiero_tierlection",
            "questiero_tl",
            "tierlection"
        );

        $query = "SELECT * FROM itemSet";
        $statement = $connection->prepare($query);
        $statement->execute();

        foreach ($statement as $row) {
            array_push($datas, $row['name']);
        }

        return $datas;

    }

?>