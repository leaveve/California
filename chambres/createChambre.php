<?php
require_once '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'];
    $capacite = $_POST['capacite'];

    $conn = openDatabaseConnection();
    $stmt = $conn->prepare("INSERT INTO chambres (numero, capacite) VALUES (?, ?)");
    $stmt->execute([$numero, $capacite]);
    closeDatabaseConnection($conn);

    header("Location: listChambres.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter une Chambre</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card p-4 shadow" style="width: 100%; max-width: 500px;">
            <h2 class="text-center mb-4"><i class="fas fa-bed me-2"></i>Ajouter une Chambre</h2>

            <form method="post">
                <div class="mb-3">
                    <label for="numero" class="form-label">Numéro</label>
                    <input type="text" id="numero" name="numero" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="capacite" class="form-label">Capacité</label>
                    <input type="number" id="capacite" name="capacite" class="form-control" required min="1">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-1"></i> Enregistrer
                    </button>
                    <a href="listChambres.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>