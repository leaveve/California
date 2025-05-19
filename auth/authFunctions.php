<?php
// Initialiser la session
function initialiserSession()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
// Vérifier si l'employé est connecté
function isLoggedIn()
{
    initialiserSession();
    return isset($_SESSION['user_id']);
}
/** Vérifier si l'utilisateur a un niveau d'accès suffisant pour le rôle requis
 * rôle allant de 1 (admin) à 10 (aucun droit)
 * Renvoie True si rôle <= rôle attendu (less is better)
 */
function hasRole($required_role)
{
    initialiserSession();

    // Si l'employé n'est pas connecté, aucun accès
    if (!isLoggedIn()) return false;

    // Table de correspondance des rôles et de leurs niveaux d'accès
    $role_levels = [
        'admin' => 1, // Niveau administrateur maximum
        'directeur' => 2, // Directeur
        'manager' => 3, // Gestionnaire
        '-reserve-' => 4, // un chef de service plus tard ?
        'standard' => 5, // Utilisateur standard
        'interimaire' => 7, // employé temporaire
        'client' => 8 // éventuellement les client...
    ];
    // Récupérer le niveau requis
    if (isset($role_levels[$required_role])) {
        $required_level = $role_levels[$required_role];
    } else {
        $required_level = 10;
    }
    // Récupérer le rôle actuel de l'employé
    $user_role = $_SESSION['role'] ?? '';

    // Déterminer le niveau de l'employé
    $user_level = isset($role_levels[$user_role]) ? $role_levels[$user_role] : 10;
    return $user_level <= $required_level;
}
// Authentifier un employé
function authenticateUser($username, $password, $conn)
{
    try {
        error_log("resaHotelCalifornia : Authentification en cours...");
        // Préparation de la requête avec PDO
        $query = "SELECT id, username, password, role FROM employes WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$username]);

        // Récupérer les résultats
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            // Vérifier le mot de passe (hash sha-256) avec la valeur dans la base
            if (hash('sha256', $password) === $user['password']) {
                initialiserSession();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                error_log("resaHotelCalifornia : Authentification réussie");
                return true;
            } else {
                // vérifier les empreintes des mots de passe
                echo "BDD :" . hash('sha256', $user['password']) . "\n<br>";
                echo "FORM:" . hash('sha256', $password);
                exit;
            }
        }
        return false;
    } catch (PDOException $e) {
        // Gérer l'erreur
        error_log("Erreur d'authentification: " . $e->getMessage());
        return false;
    }
}
// Déconnecter l'employé
function logoutUser()
{
    initialiserSession(); // Lancement des opérations de cookie si non existante
    session_destroy(); // destruction de la session en cours
    // S'assurer de détruire également le cookie de session
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
}
// Vérification d'accès pour les pages protégées
function requireRole($role = null)
{
    initialiserSession();

    // Si l'utilisateur n'est pas connecté, rediriger vers la page de login
    if (!isLoggedIn()) {
        header("Location: /resaHotelCalifornia/auth/login.php");
        exit;
    }

    // Si un rôle est requis et que l'employe ne l'a pas, refuser l'accès
    if ($role !== null && !hasRole($role)) {
        $encodedMessage = urlencode("ERREUR : Accès refusé.");
        header("Location: /index.php?message=$encodedMessage");
        exit;
    }
}
