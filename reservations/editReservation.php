<?php
require_once '../config/db_connect.php';
$conn = openDatabaseConnection();

$errors = [];

$reservation_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($reservation_id <= 0) {
    header("Location: listReservations.php");
    exit;
}

$stmtClients = $conn->query("SELECT id_client, nom FROM clients ORDER BY nom");
$clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);

$stmtChambres = $conn->query("SELECT id, numero, capacite FROM chambres ORDER BY numero");
$chambres = $stmtChambres->fetchAll(PDO::FETCH_ASSOC);

$today = date('Y-m-d');

$stmt = $conn->prepare("SELECT * FROM reservations WHERE id = ?");
$stmt->execute([$reservation_id]);
$reservation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reservation) {
    closeDatabaseConnection($conn);
    echo "<h2 class='text-center text-danger mt-5'>Réservation introuvable.</h2>";
    exit;
}

if ($reservation['date_depart'] < $today) {
    closeDatabaseConnection($conn);
    echo "<h2 class='text-center text-danger mt-5'>Impossible de modifier une réservation déjà terminée.</h2>";
    exit;
}

$client_id = $reservation['id_client'];
$chambre_id = $reservation['chambre_id'];
$date_arrivee = $reservation['date_arrivee'];
$date_depart = $reservation['date_depart'];

// Get nombre_personnes from reservation by default()
$nombre_personnes = $reservation['nombre_personnes'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = $_POST['client_id'] ?? '';
    $chambre_id = $_POST['chambre_id'] ?? '';
    $date_arrivee = $_POST['date_arrivee'] ?? '';
    $date_depart = $_POST['date_depart'] ?? '';

    // Fetch nombre_personnes from client table
    $stmtClient = $conn->prepare("SELECT nombre_personnes FROM clients WHERE id_client = ?");
    $stmtClient->execute([$client_id]);
    $clientData = $stmtClient->fetch(PDO::FETCH_ASSOC);
    $nombre_personnes = $clientData ? (int)$clientData['nombre_personnes'] : 1;

    if (!$client_id) $errors[] = "Client obligatoire.";
    if (!$chambre_id) $errors[] = "Chambre obligatoire.";

    if (empty($date_arrivee) || empty($date_depart)) {
        $errors[] = "Les dates sont obligatoires.";
    } else {
        if ($date_arrivee < $today) $errors[] = "La date d'arrivée ne peut pas être dans le passé.";
        if ($date_depart < $today) $errors[] = "La date de départ ne peut pas être dans le passé.";
        if ($date_arrivee > $date_depart) $errors[] = "La date de départ doit être après la date d'arrivée.";
    }

    // Vérification capacité chambre
    $chambreCapacite = null;
    foreach ($chambres as $chambre) {
        if ($chambre['id'] == $chambre_id) {
            $chambreCapacite = (int)$chambre['capacite'];
            break;
        }
    }

    if ($chambreCapacite !== null && $nombre_personnes > $chambreCapacite) {
        $errors[] = "Le nombre de personnes dépasse la capacité maximale de la chambre ($chambreCapacite).";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE reservations SET id_client = ?, chambre_id = ?, date_arrivee = ?, date_depart = ?, nombre_personnes = ? WHERE id = ?");
        $stmt->execute([$client_id, $chambre_id, $date_arrivee, $date_depart, $nombre_personnes, $reservation_id]);

        closeDatabaseConnection($conn);
        header("Location: listReservations.php?success=1");
        exit;
    }
}

closeDatabaseConnection($conn);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier une Réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background-color: #f8f9fa;">

    <?php include_once '../assets/gestionMessage.php'; ?>
    <?php include '../assets/navbar.php'; ?>

    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="mb-4 text-center">Modifier la Réservation</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Client</label>
                    <select name="client_id" class="form-select" required>
                        <option value="">-- Sélectionner un client --</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?= $client['id_client'] ?>" <?= $client['id_client'] == $client_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($client['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Chambre</label>
                    <select name="chambre_id" class="form-select" required>
                        <option value="">-- Sélectionner une chambre --</option>
                        <?php foreach ($chambres as $chambre): ?>
                            <option value="<?= $chambre['id'] ?>" <?= $chambre['id'] == $chambre_id ? 'selected' : '' ?>>
                                N°<?= $chambre['numero'] ?> (<?= $chambre['capacite'] ?> pers.)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date d'arrivée</label>
                    <input type="date" name="date_arrivee" class="form-control"
                        value="<?= $date_arrivee ?>" min="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Date de départ</label>
                    <input type="date" name="date_depart" class="form-control"
                        value="<?= $date_depart ?>" min="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nombre de personnes (auto)</label>
                    <input type="text" class="form-control" value="<?= $nombre_personnes ?>" disabled>
                    <input type="hidden" name="nombre_personnes" value="<?= $nombre_personnes ?>">
                </div>

                <div class="col-12 d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="listReservations.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>