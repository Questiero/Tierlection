<?php 

    session_start();

    if(!isset($_SESSION['user']) || !isset($_GET["idElection"]) || !userParticipated() || !late()) {
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