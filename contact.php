<?php
session_start();
require_once 'config.php';
$success = ''; $error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom     = trim($_POST['nom'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if($nom && $email && $message) {
        $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?,?,?)")->execute([$nom, $email, $message]);
        $success = "Message envoyé ! Nous vous répondrons sous 24h.";
    } else { $error = "Veuillez remplir tous les champs."; }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Contact — Animozen</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="style.css"/>
</head>
<body>
<nav class="navbar scrolled">
  <div class="nav-container">
    <a href="index.php" class="nav-logo"><i class="fa-solid fa-paw"></i><span>Animozen</span></a>
    <ul class="nav-links">
      <li><a href="index.php">Accueil</a></li>
      <li><a href="pets.php">Animaux</a></li>
      <li><a href="contact.php" class="active">Contact</a></li>
    </ul>
    <div class="nav-auth">
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="logout.php" class="btn-nav">Déconnexion</a>
      <?php else: ?>
        <a href="connexion.php" class="btn-nav-solid">Connexion</a>
      <?php endif; ?>
    </div>
    <button class="hamburger" id="hamburger"><i class="fa-solid fa-bars"></i></button>
  </div>
</nav>
<div class="page-top">
  <div class="page-header"><h1>Contactez-nous</h1></div>
  <div class="contact-grid">
    <div>
      <div class="form-card">
        <h2>Envoyer un message</h2>
        <?php if($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <form method="POST">
          <div class="form-row">
            <div class="form-group"><label class="form-label">Nom *</label><input type="text" name="nom" class="form-input" placeholder="Votre nom" required/></div>
            <div class="form-group"><label class="form-label">Email *</label><input type="email" name="email" class="form-input" placeholder="vous@example.com" required/></div>
          </div>
          <div class="form-group"><label class="form-label">Message *</label><textarea name="message" class="form-textarea" placeholder="Votre message..." required></textarea></div>
          <button type="submit" class="btn-submit">Envoyer</button>
        </form>
      </div>
    </div>
  </div>
</div>
<footer class="footer">
  <div class="footer-bottom" style="max-width:1200px;margin:0 auto;">
    <span class="footer-copy">© 2025 Animozen</span>
    <div class="footer-socials">
      <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
      <a href="#"><i class="fa-brands fa-instagram"></i></a>
    </div>
  </div>
</footer>
<script>document.getElementById('hamburger').addEventListener('click',()=>{document.querySelector('.nav-links').classList.toggle('open');});</script>
</body>
</html>
