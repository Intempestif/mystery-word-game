<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'pendu');

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username == '' || $password == '') {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        if($stmt->get_result()->num_rows > 0) {
            $error = "Nom d'utilisateur déjà pris.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO utilisateurs (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);
            if($stmt->execute()) {
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $error = "Erreur lors de l'inscription.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Inscription</h1>
    <form method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">S'inscrire</button>
    </form>
    <?php if($error != ''): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <p>Déjà inscrit ? <a href="login.php">Connectez-vous ici</a>.</p>
</body>
</html>
