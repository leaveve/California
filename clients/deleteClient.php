<?php
require_once '../config/db_connect.php';

$id_client = isset($_GET['id_client']) ? (int)$_GET['id_client'] : 0;

if ($id_client <= 0) {
    header("Location: listClients.php");
    exit;
}

$conn = openDatabaseConnection();

// Rechercher le client
$stmt = $conn->prepare("SELECT * FROM clients WHERE id_client = ?");
$stmt->execute([$id_client]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    header("Location: listClients.php");
    exit;
}

// Si l'utilisateur confirme la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        $stmt = $conn->prepare("DELETE FROM clients WHERE id_client = ?");
        $stmt->execute([$id_client]);

        closeDatabaseConnection($conn);
        header("Location: listClients.php?deleted=1");
        exit;
    }
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Suppression d'un client</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Supprimer un client</h1>
        <div class="alert alert-warning">
            <p><i class="fas fa-exclamation-triangle"></i> Vous êtes sur le point de supprimer <strong><?= htmlspecialchars($client['prenom']) ?> <?= htmlspecialchars($client['nom']) ?></strong>.</p>
            <p>Cette action est irréversible.</p>
        </div>

        <form method="post">
            <input type="hidden" name="confirm" value="yes">
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i> Supprimer définitivement
            </button>
            <a href="listClients.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>

</html>