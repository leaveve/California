<?php
require_once '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $nombre_personnes = $_POST['nombre_personnes'];

     $conn = openDatabaseConnection();
     $stmt = $conn->prepare("INSERT INTO clients (prenom, nom , email , telephone , nombre_personnes) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $prenom,  $email , $telephone, $nombre_personnes]);
    closeDatabaseConnection($conn);

     header("Location: listClients.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
     <title>Ajouter un Client </title>
</head>
<body>
     <h1>Ajouter une Client</h1>
     <form method="post">
         <div>
             <label>Nom:</label>
             <input type="text" name="nom" required>
         </div>
         <div>
             <label>Prenom:</label>
             <input type="text" name="prenom" required>
         </div>
         <div>
             <label>Mail : </label>
             <input type="text" name="email" required>
         </div>
         <div>
             <label>telephone :</label>
             <input type="text" name="telephone" required>
         </div>
         <div>
             <label>Nombre de personnes</label>
             <input type="number" name="nombre_personnes" required>
         </div>
         <button type="submit">Enregistrer</button>
     </form>
     <a href="listClients.php">Retour à la liste</a>
</body>
</html>