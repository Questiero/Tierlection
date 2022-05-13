<?php 

    session_start();

    if(!isset($_SESSION['user']) || !isset($_GET["idElection"]) || early()) {
        header("Location: index.php");
    } else if(userParticipated() || late()) {
        header("Location: resultats.php?idElection=" . $_GET["idElection"]);
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
    
    <div class="vote">
        <div class="card" id="card-left"></div>
        <div class="card" id="card-right"></div>
    </div>

    <script type="text/javascript" src="electionPage.js"></script>
    <script type="text/javascript">
        var idElection = <?php echo $_GET["idElection"]; ?>;
        loadJSON(idElection);
    </script>

</body>
</html>

<?php 

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
            return($row['COUNT(*)'] == 0);
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

?>