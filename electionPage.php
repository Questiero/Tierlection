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
        <div class="card" id="card-left"></div>
        <div class="card" id="card-right"></div>
    </div>

    <script type="text/javascript" src="electionPage.js"></script>
    <script type="text/javascript">loadJSON(<?php echo $_GET["idElection"]; ?>);</script>

</body>
</html>
