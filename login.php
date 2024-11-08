<?php
session_start();

// Configuration de la base de données
$host = 'localhost';
$db = 'login_system';
$user = 'root';
$pass = '';

// Connexion à la base de données
$conn = new mysqli($host, $user, $pass, $db);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Fonction de déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Traitement du formulaire de connexion
$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        // Connexion
        $username = $conn->real_escape_string($_POST['username']);
        $password = $_POST['password'];

        $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['logged_in'] = true;
            } else {
                $error = "Mot de passe incorrect";
            }
        } else {
            $error = "Utilisateur non trouvé";
        }
    } elseif (isset($_POST['register'])) {
        // Inscription
        $username = $conn->real_escape_string($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Vérifier si l'utilisateur existe déjà
        $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $checkResult = $check->get_result();

        if ($checkResult->num_rows == 0) {
            $query = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $query->bind_param("ss", $username, $password);
            
            if ($query->execute()) {
                $success = "Inscription réussie! Vous pouvez maintenant vous connecter.";
            } else {
                $error = "Erreur lors de l'inscription";
            }
        } else {
            $error = "Ce nom d'utilisateur existe déjà";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        .form-container {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        .form-container.active {
            display: block;
            opacity: 1;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .toggle-button {
            margin-top: 15px;
            text-align: center;
            cursor: pointer;
            color: #007BFF;
        }
    </style>
</head>
<body>
    <?php if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true): ?>
        <h2>Connexion / Inscription</h2>
        
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo $error; ?></p>
 <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <div id="login-form" class="form-container active">
            <h3>Connexion</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Nom d'utilisateur:</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Mot de passe:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="login">Se connecter</button>
            </form>
            <div class="toggle-button" onclick="toggleForms()">Pas encore inscrit ? Inscrivez-vous ici</div>
        </div>

        <div id="register-form" class="form-container">
            <h3>Inscription</h3>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Nom d'utilisateur:</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Mot de passe:</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="register">S'inscrire</button>
            </form>
            <div class="toggle-button" onclick="toggleForms()">Déjà inscrit ? Connectez-vous ici</div>
        </div>

    <?php else: ?>
        <h2>Bienvenue, <?php echo $_SESSION['username']; ?> !</h2>
        <p>Vous êtes connecté.</p>
        <a href="?logout=true">Déconnexion</a>
    <?php endif; ?>

    <script>
        function toggleForms() {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            loginForm.classList.toggle('active');
            registerForm.classList.toggle('active');
        }
    </script>
</body>
</html>