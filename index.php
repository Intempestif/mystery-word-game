<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pendu Multijoueur</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Bienvenue sur le Pendu Multijoueur</h1>

    <?php if(isset($_SESSION['username'])): ?>
        <p>Connecté en tant que <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <button onclick="window.location.href='game.php'">Commencer une partie</button>
        <button onclick="window.location.href='leaderboard.php'">Voir le classement</button>
        <button onclick="window.location.href='logout.php'">Se déconnecter</button>
    <?php else: ?>
        <button onclick="window.location.href='login.php'">Se connecter</button>
        <button onclick="window.location.href='register.php'">S'inscrire</button>
        <button onclick="window.location.href='leaderboard.php'">Voir le classement</button>
    <?php endif; ?>

</body>
</html>
