<?php
session_start();

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'pendu');

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupération du classement
$sql = "SELECT username, score FROM scores ORDER BY score DESC LIMIT 10";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Classement</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Classement des joueurs</h1>
    <table>
        <tr>
            <th>Joueur</th>
            <th>Score</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo $row['score']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <button onclick="window.location.href='index.php'">Revenir à l'accueil</button>
</body>
</html>
