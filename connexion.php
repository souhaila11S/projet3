<?php
session_start();
if(isset($_SESSION['user_id'])) header('Location: index.php');
require_once 'config.php';
$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mdp   = trim($_POST['mot_de_passe'] ?? '');
    if($email && $mdp) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if($user && ($user['mot_de_passe'] === $mdp || password_verify($mdp, $user['mot_de_passe']))) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nom']     = $user['nom'];
            $_SESSION['role']    = $user['role'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Connexion — Animozen</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="style.css"/>
</head>
<body>
<div class="auth-page">
  <div class="auth-left">
    <div style="font-size:48px;margin-bottom:1.5rem;"></div>
    <h2>Bienvenue sur Animozen</h2>
    <p>Connectez-vous pour ajouter vos animaux, gérer vos annonces et aider des milliers d'animaux à trouver un foyer.</p>
    <div class="auth-perks">
      <div class="perk"><div class="perk-icon"><i class="fa-solid fa-cat"></i></div> Ajoutez vos animaux à adopter</div>
      <div class="perk"><div class="perk-icon"><i class="fas fa-pencil-alt"></i></div> Modifiez et supprimez vos annonces</div>
      <div class="perk"><div class="perk-icon"><i class="fa-solid fa-comment-dots"></i></div> Recevez des messages des adoptants</div>
      <div class="perk"><div class="perk-icon"><i class="fa-solid fa-heart"></i></div> Aidez les animaux à trouver un foyer</div>
    </div>
  </div>
  <div class="auth-right">
    <div class="auth-form">
      <h2>Connexion</h2>
      <p class="auth-sub">Entrez vos identifiants pour accéder à votre espace</p>
      <?php if($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="POST">
        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-input" placeholder="vous@example.com" required/>
        </div>
        <div class="form-group">
          <label class="form-label">Mot de passe</label>
          <input type="password" name="mot_de_passe" class="form-input" placeholder="••••••••" required/>
        </div>
        <button type="submit" class="btn-submit">Se connecter</button>
      </form>
      <p class="form-switch" style="margin-top:1rem;">Pas encore de compte ? <a href="inscription.php">S'inscrire</a></p>
      <p class="form-switch"><a href="index.php">← Retour à l'accueil</a></p>
    </div>
  </div>
</div>
</body>
</html>
