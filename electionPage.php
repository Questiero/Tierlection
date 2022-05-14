<?php 

    session_start();

    if(!isset($_SESSION['user']) || !isset($_GET["idElection"]) || early()) {
        header("Location: index.php");
    } else if(userParticipated() || late() || userOrganized()) {
        header("Location: resultats.php?idElection=" . $_GET["idElection"]);
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
    
    <div class="vote">
        <div class="card" id="card-left"></div>
        <div class="card" id="card-right"></div>
    </div>

    <script type="text/javascript" src="electionPage.js"></script>
    <script type="text/javascript">
        var idElection = <?php echo $_GET["idElection"]; ?>;
        loadJSON(idElection);
        
    </script>



    <div id="titre">
        <img id="tierlection-logo" src="data/TierLection-Logo.png">
        <h2><?php echo getNameElection(); ?></h2>
        <p id="voteTxt"> Choisis ton préféré en cliquant dessus : </p>
        <div id="nbrVotes"></div>
    </div>

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
    
    function getNameElection() {

        require 'base.php';

        $query = "SELECT name FROM election WHERE idElection = :idElection";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_STR);
        $statement->execute();

        foreach($statement as $row) {
            return $row["name"];
        }

    }

    function userParticipated() {

        require 'base.php';

        // Get election ID
        $query = "SELECT COUNT(*) FROM participate WHERE idUser = :idUser AND idElection = :idElection";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idUser", $_SESSION["user"]["idUser"], PDO::PARAM_INT);
        $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_INT);
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            return((bool) $row['COUNT(*)']);
        }

    }

    function early() {

        $startYear;
        $startMonth;
        $startDay;

        $todayYear = intval(date('y'));
        $todayMonth = intval(date('m'));
        $todayDay = intval(date('d'));

        require 'base.php';

        // Get election ID
        $query = "SELECT startDate FROM election WHERE idElection = :idElection";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_INT);
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            $startDate = $row["startDate"];
            $startYear = intval(substr($startDate, 2, -6));
            $startMonth = intval(substr($startDate, 5, -3));
            $startDay = intval(substr($startDate, 8));
        }

        return ($startYear > $todayYear || ($startYear <= $todayYear && ($startMonth > $todayMonth || ($startMonth <= $todayMonth && $startDay > $todayDay))));

    }

    function late() {

        $endYear;
        $endMonth;
        $endDay;

        $todayYear = intval(date('y'));
        $todayMonth = intval(date('m'));
        $todayDay = intval(date('d'));

        require 'base.php';

        // Get election ID
        $query = "SELECT endDate FROM election WHERE idElection = :idElection";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_INT);
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            $endDate = $row["endDate"];
            $endYear = intval(substr($endDate, 2, -6));
            $endMonth = intval(substr($endDate, 5, -3));
            $endDay = intval(substr($endDate, 8));
        }

        return ($endYear < $todayYear || ($endYear >= $todayYear && ($endMonth < $todayMonth || ($endMonth >= $todayMonth && $endDay < $todayDay))));

    }

    function userOrganized() {

        require 'base.php';

        // Get election ID
        $query = "SELECT COUNT(*) FROM election WHERE idOrganizator = :idUser AND idElection = :idElection";
        $statement = $connection->prepare($query);

        // Bind value and execute query
        $statement->bindValue(":idUser", $_SESSION["user"]["idUser"], PDO::PARAM_INT);
        $statement->bindValue(":idElection", $_GET["idElection"], PDO::PARAM_INT);
        $statement->execute();

        // Browse the results
        foreach ($statement as $row) {
            return((bool) $row['COUNT(*)']);
        }

    }

?>