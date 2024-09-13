<?php
session_start();

if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'pendu');

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Initialisation du jeu
if (!isset($_SESSION['mot'])) {
    $mots = ['PROGRAMMATION', 'INTERNET', 'ORDINATEUR', 'DEVELOPPEUR', 'LANGAGE'];
    $_SESSION['mot'] = $mots[array_rand($mots)];
    $_SESSION['essais'] = 6;
    $_SESSION['lettres'] = [];
}

// Traitement de la lettre proposée
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lettre = strtoupper($_POST['lettre']);
    if (!in_array($lettre, $_SESSION['lettres']) && ctype_alpha($lettre)) {
        $_SESSION['lettres'][] = $lettre;
        if (strpos($_SESSION['mot'], $lettre) === false) {
            $_SESSION['essais']--;
        }
    }
}

// Vérification de la fin de partie
$mot_affiche = '';
$gagne = true;
for ($i = 0; $i < strlen($_SESSION['mot']); $i++) {
    $lettre = $_SESSION['mot'][$i];
    if (in_array($lettre, $_SESSION['lettres'])) {
        $mot_affiche .= $lettre . ' ';
    } else {
        $mot_affiche .= '_ ';
        $gagne = false;
    }
}

if ($_SESSION['essais'] <= 0 || $gagne) {
    if ($gagne) {
        // Mise à jour du classement
        $stmt = $conn->prepare("INSERT INTO scores (username, score) VALUES (?, 1) ON DUPLICATE KEY UPDATE score = score + 1");
        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $message = "Félicitations, vous avez gagné !";
    } else {
        $message = "Désolé, vous avez perdu. Le mot était " . $_SESSION['mot'];
    }
    unset($_SESSION['mot']);
    // unset($_SESSION['essais']);
    $_SESSION['essais'] = '';
    unset($_SESSION['lettres']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Jeu du Pendu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Jeu du Pendu</h1>
    <p>Connecté en tant que <?php echo htmlspecialchars($_SESSION['username']); ?></p>
    <p>Mot à deviner : <?php echo $mot_affiche; ?></p>
    <p>Essais restants : <?php echo $_SESSION['essais']; ?></p>

    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
        <button onclick="window.location.href='game.php'">Rejouer</button>
        <button onclick="window.location.href='index.php'">Revenir à l'accueil</button>
    <?php else: ?>
        <form method="post">
            <input type="text" name="lettre" maxlength="1" required autofocus>
            <button type="submit">Proposer</button>
        </form>
        <p>Lettres déjà proposées : <?php echo implode(', ', $_SESSION['lettres']); ?></p>
    <?php endif; ?>
</body>
</html>
