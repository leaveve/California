<?php
require_once '../config/db_connect.php';
include '../assets/navbar.php';


$conn = openDatabaseConnection();
$stmt = $conn->query("SELECT * FROM clients ORDER BY id_client");
$client = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des Clients</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mt-5">
        <h1 class="mb-4 text-center">Liste des Clients</h1>
        <div class="mb-3 text-end">
            <a href="createClient.php" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Ajouter un Client
            </a>
        </div>

        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Nombre de personnes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($client as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['id_client']) ?></td>
                        <td><?= htmlspecialchars($client['nom']) ?></td>
                        <td><?= htmlspecialchars($client['prenom']) ?></td>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                        <td><?= htmlspecialchars($client['telephone']) ?></td>
                        <td><?= htmlspecialchars($client['nombre_personnes']) ?></td>
                        <td>
                            <a href="editClient.php?id_client=<?= $client['id_client'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="deleteClient.php?id_client=<?= $client['id_client'] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Êtes-vous sûr ?')">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>