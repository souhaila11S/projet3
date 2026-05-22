<?php
session_start();
if(isset($_SESSION['user_id'])) header('Location: index.php');
require_once 'config.php';
$error = ''; $success = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom   = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mdp   = trim($_POST['mot_de_passe'] ?? '');
    $tel   = trim($_POST['telephone'] ?? '');
    $wa    = trim($_POST['whatsapp'] ?? '');
    if($nom && $email && $mdp) {
        $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $check->execute([$email]);
        if($check->fetch()) {
            $error = "Cet email est déjà utilisé.";
        } else {
            $hash = password_hash($mdp, PASSWORD_DEFAULT);
            $ins = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, telephone, whatsapp, role) VALUES (?,?,?,?,?,'user')");
            $ins->execute([$nom, $email, $hash, $tel, $wa]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['nom']     = $nom;
            $_SESSION['role']    = 'user';
            header('Location: index.php');
            exit;
        }
    } else {
        $error = "Veuillez remplir les champs obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Inscription — Animozen</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="style.css"/>
</head>
<body>
<div class="auth-page">
  <div class="auth-left">
    <div style="font-size:48px;margin-bottom:1.5rem;"></div>
    <h2>Rejoignez Animozen</h2>
    <p>Créez votre compte gratuitement et commencez à aider les animaux à trouver un foyer aimant.</p>
    <div class="auth-perks">
      <div class="perk"><div class="perk-icon"><i class="fa-solid fa-file-signature"></i></div> Inscription 100% gratuite</div>
      <div class="perk"><div class="perk-icon"><i class="fa-solid fa-paw"></i></div> Publiez vos animaux à adopter</div>
      <div class="perk"><div class="perk-icon"><i class="fa-brands fa-whatsapp"></i></div> Contact direct via WhatsApp</div>
      <div class="perk"><div class="perk-icon"><i class="fa-solid fa-lock"></i></div> Gérez vos annonces en toute sécurité</div>
    </div>
  </div>
  <div class="auth-right">
    <div class="auth-form">
      <h2>Créer un compte</h2>
      <p class="auth-sub">Remplissez le formulaire pour vous inscrire</p>
      <?php if($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <form method="POST">
        <div class="form-row">
          <div class="form-group"><label class="form-label">Nom *</label><input type="text" name="nom" class="form-input" placeholder="Votre nom" required/></div>
          <div class="form-group"><label class="form-label">Email *</label><input type="email" name="email" class="form-input" placeholder="vous@example.com" required/></div>
        </div>
        <div class="form-group"><label class="form-label">Mot de passe *</label><input type="password" name="mot_de_passe" class="form-input" placeholder="Minimum 6 caractères" required/></div>
        <div class="form-row">
          <div class="form-group"><label class="form-label">Téléphone</label><input type="tel" name="telephone" class="form-input" placeholder="+212 6 00 00 00 00"/></div>
          <div class="form-group"><label class="form-label">WhatsApp</label><input type="tel" name="whatsapp" class="form-input" placeholder="212600000000"/></div>
        </div>
        <button type="submit" class="btn-submit">Créer mon compte</button>
      </form>
      <p class="form-switch" style="margin-top:1rem;">Déjà un compte ? <a href="connexion.php">Se connecter</a></p>
    </div>
  </div>
</div>
</body>
</html>
