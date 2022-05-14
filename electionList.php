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
    <link rel="stylesheet" href="TierLection.css"/>
</head>
<body>


    <table id="table-list">
        <tr>
            <th>Nom</th>
            <th>Date de début</th>
            <th>Date de fin</th>
            <th>Organisateur</th>
            <th>Ouvrir</th>
        </tr>
        
        <?php 

            // Affichage de chaque élection dans le tableau et création d'un bouton permettant d'y accéder
            foreach(getElectionList() as $election) {

                echo "<tr>";
                echo "<td>" . $election["name"] . "</td>";
                echo "<td>" . $election["startDate"] . "</td>";
                echo "<td>" . $election["endDate"] . "</td>";
                echo "<td>" . $election["organizator"] . "</td>";
                echo "<td><form action='electionPage.php' method='get'><input type='hidden' name='idElection' value = '" . $election["idElection"] . "'></input><button type='submit'>Ouvrir</button></form></td>";
                echo "</tr>";

            }

        ?>

    </table>

    <div id="titre">
        <img id="tierlection-logo" src="data/TierLection-Logo.png">
        <h2>Liste des votes</h2>
        <div id="profile">
                    <form action="profile.php">
                        <button type="submit">Mon profil</button>  
                    </form>
                    <form action="disconnect.php">
                        <button type="submit">Déconnexion</button>
                    </form>

    </div>

    <script type="text/javascript" src="logoIndex.js"></script>
        
</body>
</html>

<?php 

    // Récupération d'une liste des élections: nom, date de début, date de fin, pseudo de l'organisateur et id
    function getElectionList() {

        require 'base.php';

        $datas = array();

        $query = "SELECT e.name, e.startDate, e.endDate, u.username, e.idElection FROM election e, user u WHERE u.idUser = e.idOrganizator";
        $statement = $connection->prepare($query);

        // Execute query
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            $temp = [
                "name" => $row['name'], 
                "startDate" => $row['startDate'],
                "endDate" => $row['endDate'],
                "organizator" => $row['username'],
                "idElection" => $row['idElection']];
            array_push($datas, $temp);
        }

        return $datas;

    }

?>