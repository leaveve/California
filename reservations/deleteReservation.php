<?php
require_once '../config/db_connect.php';

if (!isset($_GET['id'])) {
    die("ID de réservation manquant.");
}

$id = (int) $_GET['id'];

$conn = openDatabaseConnection();


$stmtClient = $conn->prepare("SELECT id FROM reservations WHERE id = ?");
$stmtClient->execute([$id_client]);
$client = $stmtClient->fetch(PDO::FETCH_ASSOC);
$client_id = $client ? $client['client_id'] : null;

// Supprimer la réservation
$stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
$stmt->execute([$id]);


closeDatabaseConnection($conn);

header('Location: listReservations.php');
exit();
