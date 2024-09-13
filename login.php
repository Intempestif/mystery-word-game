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
        $result = $stmt->get_result();

        if($result->num_rows == 0) {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
        } else {
            $user = $result->fetch_assoc();
            if(password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $error = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Connexion</h1>
    <form method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
    <?php if($error != ''): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a>.</p>
</body>
</html>
