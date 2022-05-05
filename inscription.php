<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <title>Enregistrement</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="TierLection.css"/>
</head>

<body>
  <div id="global">
    <!-- Panel for subscription -->
    <div class="panel">
        <form action="inscription.php" method="post">
          <div id="valeurs">
                <div>
                    <label for="identifiant">Nom d'utilisateur</label>
                    <input type="text" id="identifiant" name="identifiant" size="20"/>
                </div>
            <div>
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password"/>
            </div>
            <div>
                <label for="confirm">Confirmation</label>
                <input type="password" id="confirm" name="confirm"/>
            </div>

          

            
          
          </div>

            <div class="errors">       
            
      <?php
        
        // A boolean for the validity of the user input
        $valid = true;

        // The message to display
        $message = "";
  
        // Check the user name
        if (!isset($_POST['identifiant']) || strlen($_POST['identifiant']) < 2){
          $valid = false;
          $message .= '<p class="error">Le nom d\'utilisateur est trop court.</p>';
        }
        else{
          $username = $_POST['identifiant'];  
        }
  
        // Check the password
        if (!isset($_POST['password']) || strlen($_POST['password']) < 7){
          $valid = false;
          $message .= '<p class="error">Le mot de passe doit contenir au moins 7 caractères</p>';
          
        } else if (!isset($_POST['password']) || !preg_match('/[A-Z]/', $_POST['password']) || !preg_match("/[0-9]/", $_POST['password'])){
         	$valid = false;
         	$message .= '<p class="error">Le mot de passe est invalide (majuscule et chiffre requis).</p>';
        }
        else{
          $password = $_POST['password'];
	
          // Check the password confirmation
          if (!isset($_POST['confirm']) || $_POST['confirm'] != $password){
            $valid = false;
            $message .= '<p class="error">Le mot de passe et sa confirmation ne sont pas identiques.</p>';
          }
        }

        //affichage à l'appuie du bouton
        if($valid){
        	
              echo "<p>L'inscription a été réalisée avec succès.</p>";
              echo '<p><a href="pageInscription.html">Retour vers la page d\'accueil.</a></p>';
              echo '<p><a href="inscriptionPlus.html">Ajouter des informations supplémentaires facultatives.</a></p>';
  
        // If at least one criterion was not respected, display error messages
        }else{
          echo "<p class=\"error\">L'inscription n'a pas pu être réalisée pour les raisons suivantes :</p>";
          echo $message;
          echo '<p><a href="inscription.html">Retour vers la page d\'accueil</a></p>';
        }
        

   /*     // Check the availability of the user name
        if ($valid){
          $available = checkUserName($connection, $username);
          if (!$available){
            $valid = false;
            $message .= '<p class="error">Le nom d\'utilisateur '.$username.' est déjà pris.</p>';
          }
        } */

    /*   // If everything is good, add the user
        if ($valid){
          echo "<p>Les champs ont été validés par le serveur.<p/>";
	
          // Salt generation
          $salt = generateRandomString(10);

          // Password encryption
          $cryptedPw = hash('sha384', $password.$salt);
	
          // Gets emails?
          if (isset($_POST['newsletter'])){
            $get_emails = true;
          }
          else{
            $get_emails = false;
          }

          // Add the user to the database
          $userOK = addUser($connection, $username, $email, $cryptedPw, $salt, $get_emails);
	
          if ($userOK){
            // Add the user genres
            $userid = $connection->lastInsertId();
            $genresOK = addGenres($connection, $userid, $genres);
	
        	  if ($genresOK){
              echo "<p>L'inscription a été réalisée avec succès.</p>";
              echo '<p><a href="pageInscription.html">Retour vers la page d\'accueil.</a></p>';
              echo '<p><a href="inscriptionPlus.html">Ajouter des informations supplémentaires facultatives.</a></p>';
            }
          }
        }
  
        // If at least one criterion was not respected, display error messages
        }else{
          echo "<p class=\"error\">L'inscription n'a pas pu être réalisée pour les raisons suivantes :</p>";
          echo $message;
          echo '<p><a href="pageInscription.html">Retour vers la page d\'accueil</a></p>';
        }*/
      ?>
    </div>
  </div>
</body>
</html>

<!-- PHP functions -->
<?php
  /**
   * Test if a character $c is in a string $s.
   */
  function contains($c, $s){
    for ($i = 0; $i < strlen($s); $i++){
  	  if ($s[$i] == $c){
        return true;
	    }
    }
    return false;
  }
  
  /**
   * Check the availability of a user name.
   */
  function checkUserName($connection, $username){
    $query = "SELECT COUNT(*) AS count FROM users WHERE name=:username";
    $statement = $connection->prepare($query);
    $statement->bindValue(":username", $username, PDO::PARAM_STR);
    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    return $row["count"] == "0";
  }

  /**
   * Put a user in the user table.
   */
  function addUser($connection, $username, $email, $cryptedPw, $salt, $get_emails){
    $query = "INSERT INTO users (name, email, password, salt, gets_emails) VALUES (:username, :email, :cryptedPw, :salt, :get_emails)";
    $statement = $connection->prepare($query);
    $statement->bindValue(":username", $username, PDO::PARAM_STR);
    $statement->bindValue(":email", $email, PDO::PARAM_STR);
    $statement->bindValue(":cryptedPw", $cryptedPw, PDO::PARAM_STR);
    $statement->bindValue(":salt", $salt, PDO::PARAM_STR);
    $statement->bindValue(":get_emails", $get_emails, PDO::PARAM_INT);
    $OK = $statement->execute();
    return $OK;
  }

  /**
   * Put the genres of a user in the users_genres table.
   */
  function addGenres($connection, $userid, $genres){
    $query = "INSERT INTO users_genres (user_id, genre) VALUES (:userid, :genre)";
    $statement = $connection->prepare($query);
    $statement->bindValue(":userid", $userid);
    for ($i = 0; $i < sizeof($genres); $i++){
      $statement->bindValue(":genre", $genres[$i]);
      $OK = $statement->execute();
      if (!$OK){
        return false;
      }
    }
    return true;
  }

  /**
   * Generate an alphanumeric string.
   */
  function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
?>






        <!-- Choice of  -->
      

        <div id="subscription">
          <input type="submit" value="S'inscrire"/>
        </div>      
      </form>
    </div>

    <div class="facultatif">
      <form action="inscriptionPlus.php" method="post">
        <div id="infosSupp">
          <input type="submit" value="+"></input>
        </div>
      </form>
    </div>