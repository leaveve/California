<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';
// Méthode GET : on recherche le client demandée
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
// Vérifier si l'ID est valide
if ($id <= 0) {
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
    $nbPersonne = $_POST['nombre_personnes'];
    
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
        $stmt = $conn->prepare("UPDATE clients SET nom = ?, prenom = ? ,email = ?  , telephone = ? , nbPersonne = ? WHERE id = ?");
        $stmt->execute([$nom, $prenom , $email , $telephone  , $nombre_personnes , $id]);

        // Rediriger vers la liste des clients
        header("Location: listClients.php?success=1");
        exit;
    }
} else {
    // Méthode GET : Récupérer les données du client
    $stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
    $stmt->execute([$id]);
    $chambre = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le client n'existe pas, rediriger
    if (!$chambre) {
        header("Location: listClients.php");
        exit;
    }
}
closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Modifier un Client</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
</head>

<body>
    <div class="navbar">
        <a href="../index.php">Accueil</a>
        <a href="listChambres.php">Chambres</a>
        <a href="../clients/listClients.php">Clients</a>
        <a href="../reservations/listReservations.php">Réservations</a>
    </div>
    <div class="container">
        <h1>Modifier un client </h1>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="error-message">
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="numero">Numéro de Chambre:</label>
                <input type="text" id="numero" name="numero"
                    value="<?= htmlspecialchars($chambre['numero']) ?>" required>
            </div>

            <div class="form-group">
                <label for="capacite">Capacité (nombre de personnes):</label>
                <input type="number" id="capacite" name="capacite"
                    value="<?= $chambre['capacite'] ?>" min="1" required>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <a href="listClients.php" class="btn btn-danger">Annuler</a>
            </div>
        </form>
    </div>
</body>

</html>