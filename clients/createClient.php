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
    $stmt->execute([$nom, $prenom, $email, $telephone, $nombre_personnes]);
    closeDatabaseConnection($conn);

    header("Location: listClients.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un Client</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <h1 class="mb-4 text-center">Ajouter un Client</h1>

        <form method="post" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label class="form-label">Nom :</label>
                <input type="text" name="nom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Prénom :</label>
                <input type="text" name="prenom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email :</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Téléphone :</label>
                <input type="text" name="telephone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Nombre de personnes :</label>
                <input type="number" name="nombre_personnes" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
            <a href="listClients.php" class="btn btn-secondary ms-2">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>