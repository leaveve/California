<?php
// Inclusion du fichier de connexion à la base de données
require_once '../config/db_connect.php';


// Fonction pour formater les dates
function formatDate($date)
{
    $timestamp = strtotime($date);
    return date('d/m/Y', $timestamp);
}
// Récupération des réservations avec les informations des clients et des chambres
$conn = openDatabaseConnection();
$query = "SELECT r.id, r.date_arrivee, r.date_depart,
 c.nom AS client_nom, c.telephone AS client_telephone, c.email AS client_email,
 c.nombre_personnes,
 ch.numero AS chambre_numero, ch.capacite AS chambre_capacite
 FROM reservations r
 JOIN clients c ON r.id_client = c.id_client
 JOIN chambres ch ON r.chambre_id = ch.id
 ORDER BY r.date_arrivee DESC";

$stmt = $conn->query($query);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<?php include_once '../assets/gestionMessage.php'; ?>
<?php include '../assets/navbar.php'; ?>

<head>

    <title>Liste des Réservations</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Lien vers la feuille de style externe -->
    <link rel="stylesheet" href="../assets/style.css">

    <!-- Bootstrap + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

</head>

<body class="bg-light">
    <div class="container mt-5 pt-5 text-center">
        <h1 class="mb-4">Liste des Réservations</h1>

        <div class="mb-3 text-end">
            <a href="createReservation.php" class="btn btn-success">Nouvelle Réservation</a>
        </div>

        <div class="table-responsive d-flex justify-content-center">
            <table class="table table-bordered table-striped w-auto">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Contact</th>
                        <th>Chambre</th>
                        <th>Personnes</th>
                        <th>Arrivée</th>
                        <th>Départ</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($reservations) > 0): ?>
                        <?php foreach ($reservations as $reservation): ?>
                            <?php
                            $aujourd_hui = date('Y-m-d');
                            $statut = '';
                            if ($reservation['date_depart'] < $aujourd_hui) {
                                $statut_class = 'status-past';
                                $statut = '<i class="fas fa-check-circle text-success"></i> Terminée';
                            } elseif (
                                $reservation['date_arrivee'] <= $aujourd_hui &&
                                $reservation['date_depart'] >= $aujourd_hui
                            ) {
                                $statut_class = 'status-active';
                                $statut = '<i class="fas fa-spinner fa-spin text-primary"></i> En cours';
                            } else {
                                $statut_class = '';
                                $statut = '<i class="fas fa-hourglass-start text-secondary"></i> À venir';
                            }
                            ?>
                            <tr>
                                <td><?= $reservation['id'] ?></td>
                                <td><?= htmlspecialchars($reservation['client_nom']) ?></td>
                                <td>
                                    <strong>Tél:</strong>
                                    <?= htmlspecialchars($reservation['client_telephone']) ?><br>
                                    <strong>Courriel:</strong>
                                    <?= htmlspecialchars($reservation['client_email']) ?>
                                </td>
                                <td>N° <?= htmlspecialchars($reservation['chambre_numero']) ?>
                                    (<?= $reservation['chambre_capacite'] ?> pers.)</td>
                                <td><?= $reservation['nombre_personnes'] ?></td>
                                <td><?= formatDate($reservation['date_arrivee']) ?></td>
                                <td><?= formatDate($reservation['date_depart']) ?></td>
                                <td class="<?= $statut_class ?>"><?= $statut ?></td>
                                <td>
                                    </a>
                                    <a href="editReservation.php?id=<?= $reservation['id'] ?>" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="deleteReservation.php?id=<?= $reservation['id'] ?>" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Supprimer cette réservation?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">Aucune réservation trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>