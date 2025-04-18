<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérifier si l'ID est valide
if ($id <= 0) {
    header("Location: listClients.php");
    exit;
}

$conn = openDatabaseConnection();

// Vérifier si le client existe
$stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$id]);
$chambre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$chambre) {
    header("Location: listClients.php");
    exit;
}
    
    // Supprimer le client
    $stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
    $stmt->execute([$id]);
    
    // Rediriger vers la liste des clients
    header("Location: listClients.php?deleted=1");
    exit;


closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer une Chambre</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Supprimer un client</h1>
        
        <div class="alert alert-warning">
            <p><i class="fa fa-warning"></i> <strong>Attention :</strong> Vous êtes sur le point de supprimer le client <?= htmlspecialchars($clients['nom']) ?>.</p>
        </div>
            
            <p>Êtes-vous sûr de vouloir supprimer ce client ?</p>
            
            <div class="actions">
                <input type="hidden" name="confirm" value="yes">
                <button type="submit" class="btn btn-danger">Confirmer la suppression</button>
                <a href="listClients.php" class="btn btn-primary" autofocus>Annuler</a>
            </div>
        </form>
    </div>
</body>
</html>