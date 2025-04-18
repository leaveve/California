<?php
require_once '../config/db_connect.php';

$conn = openDatabaseConnection();
$stmt = $conn->query("SELECT * FROM clients ORDER BY id");
$client = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des Clients</title>
</head>
<body>
    <h1>Liste des Clients</h1>
    <a href="createClient.php">Ajouter un Client</a>
    <table border="1" style="width: 60%; min-width: 400px; margin: 0 auto;">
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Mail</th>
            <th>telephone</th>
            <th>nombres de personnes</th>
        </tr>
        <?php foreach($client as $client): ?>
        <tr>
            <td><?php echo $client['id']; ?></td>
            <td><?php echo $client['nom']; ?></td>
            <td><?php echo $client['prenom']; ?></td>
            <td><?php echo $client['email']; ?></td>
            <td><?php echo $client['telephone']; ?></td>
            <td><?php echo $client['nombre_personnes']; ?></td>
            
            <td>
                <a href="editClient.php?id=<?= $client['id'] ?>">Modifier</a>
                <a href="deleteClient.php?id=<?= $client['id'] ?>" onclick="return confirm('Êtes-vous sûr?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>