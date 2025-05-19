<?php
require_once '../config/db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: listChambres.php");
    exit;
}

$conn = openDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = $_POST['numero'];
    $capacite = (int)$_POST['capacite'];

    $errors = [];

    if (empty($numero)) {
        $errors[] = "Le numéro de chambre est obligatoire.";
    }

    if ($capacite <= 0) {
        $errors[] = "La capacité doit être un nombre positif.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE chambres SET numero = ?, capacite = ? WHERE id = ?");
        $stmt->execute([$numero, $capacite, $id]);
        header("Location: listChambres.php?success=1");
        exit;
    }
} else {
    $stmt = $conn->prepare("SELECT * FROM chambres WHERE id = ?");
    $stmt->execute([$id]);
    $chambre = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chambre) {
        header("Location: listChambres.php");
        exit;
    }
}
closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier une Chambre</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS + Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card p-4 shadow" style="width: 100%; max-width: 500px;">
            <h2 class="text-center mb-4"><i class="fas fa-pen-square me-2"></i>Modifier une Chambre</h2>

            <?php if (isset($errors) && !empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label for="numero" class="form-label">Numéro de Chambre</label>
                    <input type="text" id="numero" name="numero" class="form-control"
                        value="<?= htmlspecialchars($chambre['numero']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="capacite" class="form-label">Capacité (nombre de personnes)</label>
                    <input type="number" id="capacite" name="capacite" class="form-control"
                        value="<?= $chambre['capacite'] ?>" min="1" required>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                    <a href="listChambres.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>