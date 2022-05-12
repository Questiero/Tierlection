<?php 

    session_start();

    if(!isset($_SESSION['user']) || !isset($_GET["idElection"])) {
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
    
    <div class="vote">
        <div id="left">
            <div class="card" id="card-left"></div>
            <div class="button" id="button-left"></div>
        </div>
        <div id="right">
            <div class="card" id="card-right"></div>
            <div class="button" id="button-right"></div>
        </div>
    </div>

    <script type="text/javascript" source="electionPage.js"></script>

</body>
</html>
