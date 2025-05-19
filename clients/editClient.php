<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';
// Méthode GET : on recherche le client demandée
$id_client = isset($_GET['id_client']) ? (int)$_GET['id_client'] : 0;
// Vérifier si l'ID est valide
if ($id_client <= 0) {
    header("Location: listClients.php");
    exit;
}
$conn = openDatabaseConnection();
// Méthode POST : Traitement du formulaire si soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $nombre_personnes = $_POST['nombre_personnes'];

    // Validation des données
    $errors = [];

    if (empty($nom)) {
        $errors[] = "Le nom du client est obligatoire.";
    }

    if (empty($prenom)) {
        $errors[] = "Le prenom du client est obligatoire.";
    }

    if (empty($email)) {
        $errors[] = "Le mail du client est obligatoire.";
    }


    if (empty($telephone)) {
        $errors[] = "Le numéro de téléphone du client est obligatoire.";
    }


    if ($nombre_personnes <= 0) {
        $errors[] = "Le nombre de personne doit être un nombre positif.";
    }

    // Si pas d'erreurs, mettre à jour les données
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE clients SET nom = ?, prenom = ? ,email = ?  , telephone = ? , nombre_personnes = ? WHERE id_client = ?");
        $stmt->execute([$nom, $prenom, $email, $telephone, $nombre_personnes, $id_client]);

        // Rediriger vers la liste des clients
        header("Location: listClients.php?success=1");
        exit;
    }
} else {
    // Méthode GET : Récupérer les données du client
    $stmt = $conn->prepare("SELECT * FROM clients WHERE id_client = ?");
    $stmt->execute([$id_client]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le client n'existe pas, rediriger
    if (!$client) {
        header("Location: listClients.php");
        exit;
    }
}
closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un Client</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            padding-top: 70px;
        }

        .navbar {
            margin-bottom: 30px;
        }

        .container {
            max-width: 600px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">Accueil</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="listChambres.php">Chambres</a></li>
                    <li class="nav-item"><a class="nav-link" href="../clients/listClients.php">Clients</a></li>
                    <li class="nav-item"><a class="nav-link" href="../reservations/listReservations.php">Réservations</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="mb-4"><i class="fas fa-user-edit"></i> Modifier un client</h1>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($client['nom']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars($client['prenom']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" id="telephone" name="telephone" class="form-control" value="<?= htmlspecialchars($client['telephone']) ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($client['email']) ?>">
            </div>

            <div class="mb-3">
                <label for="nombre_personnes" class="form-label">Nombre de personnes</label>
                <input type="number" id="nombre_personnes" name="nombre_personnes" class="form-control" value="<?= $client['nombre_personnes'] ?>" min="1" required>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer</button>
                <a href="listClients.php" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>