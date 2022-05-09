<?php 

    session_start();

    if(!isset($_SESSION['user'])) {
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


    <table>
        <tr>
            <th>Nom</th>
            <th>Date de début</th>
            <th>Date de fin</th>
            <th>Organisateur</th>
        </tr>
        
        <?php 



            foreach(getElectionList() as $election) {

                echo "<tr>";
                echo "<td>" . $election["name"] . "</td>";
                echo "<td>" . $election["startDate"] . "</td>";
                echo "<td>" . $election["endDate"] . "</td>";
                echo "<td>" . $election["organizator"] . "</td>";
                echo "</tr>";

            }

        ?>

    </table>

    <div id="titre">
        <img src="data/TierLection-Logo.png">
        <h2>Liste des votes</h2>
        <div id="profile">
                    <form action="profile.php">
                        <button type="submit">Mon profil</button>  
                    </form>
                    <form action="disconnect.php\">
                        <button type="submit">Déconnexion</button>
                    </form>

    </div>
        

        

</body>
</html>

<?php 

    function getElectionList() {

        $datas = array();

        $connection = new PDO(
            "mysql:host=mysql-questiero.alwaysdata.net;dbname=questiero_tierlection",
            "questiero_tl",
            "tierlection"
        );

        $query = "SELECT e.name, e.startDate, e.endDate, u.username FROM election e, user u WHERE u.idUser = e.idOrganizator";
        $statement = $connection->prepare($query);

        // Execute query
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            $temp = [
                "name" => $row['name'], 
                "startDate" => $row['startDate'],
                "endDate" => $row['endDate'],
                "organizator" => $row['username']];
            array_push($datas, $temp);
        }

        $connection = null;

        return $datas;

    }

?>