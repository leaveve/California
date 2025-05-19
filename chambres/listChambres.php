<?php
require_once '../config/db_connect.php';

$conn = openDatabaseConnection();
$stmt = $conn->query("SELECT * FROM chambres ORDER BY numero");
$chambres = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<?php include_once '../assets/gestionMessage.php'; ?>
<?php include '../assets/navbar.php'; ?>
<head>
    <meta charset="UTF-8">
    <title>Liste des Chambres</title>

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-dark">Liste des Chambres</h1>
            <a href="createChambre.php" class="btn btn-success">
                <i class="fas fa-plus-circle me-1"></i> Ajouter une chambre
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Numéro</th>
                        <th>Capacité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($chambres as $chambre): ?>
                        <tr>
                            <td><?= htmlspecialchars($chambre['id']) ?></td>
                            <td><?= htmlspecialchars($chambre['numero']) ?></td>
                            <td><?= htmlspecialchars($chambre['capacite']) ?></td>
                            <td>
                                <a href="editChambre.php?id=<?= $chambre['id'] ?>" class="btn btn-outline-warning btn-sm me-2">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="deleteChambre.php?id=<?= $chambre['id'] ?>" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Êtes-vous sûr ?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>