<?php
session_start();
if(!isset($_SESSION['user_id'])) { header('Location: connexion.php'); exit; }
require_once 'config.php';
$error = ''; $success = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom        = trim($_POST['nom'] ?? '');
  $type       = trim($_POST['type'] ?? '');
  $age_valeur = intval($_POST['age_valeur'] ?? 0);
  $age_unite  = trim($_POST['age_unite'] ?? 'ans');
  $desc       = trim($_POST['description'] ?? '');
  $img        = '';

  if($nom && $type && $age_valeur > 0) {
      if(!empty($_FILES['image']['name'])) {
          $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
          $img = uniqid() . '.' . $ext;
          if(!is_dir('uploads')) mkdir('uploads', 0755, true);
          move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $img);
      }
      $stmt = $pdo->prepare("INSERT INTO animaux (nom, type, age, age_unite, image, description, id_utilisateur) VALUES (?,?,?,?,?,?,?)");
      $stmt->execute([$nom, $type, $age_valeur, $age_unite, $img, $desc, $_SESSION['user_id']]);
      $success = "Animal ajouté avec succès !";
  } else {
      $error = "Veuillez remplir tous les champs obligatoires.";
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Ajouter un animal — Animozen</title>
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
      <li><a href="mes_animaux.php">Mes animaux</a></li>
    </ul>
    <div class="nav-auth">
      <span style="font-size:13px;color:var(--text3);margin-right:8px;"> <i class="fa-solid fa-user"></i> <?= htmlspecialchars($_SESSION['nom']) ?></span>
      <a href="logout.php" class="btn-nav">Déconnexion</a>
    </div>
    <button class="hamburger" id="hamburger"><i class="fa-solid fa-bars"></i></button>
  </div>
</nav>

<div class="page-top">
  <div class="page-header">
    <h1>Ajouter un animal</h1>
  </div>
  <div class="form-wrap">
    <div class="form-card">
      <h2>Informations de l'animal</h2>
      <?php if($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?> <a href="mes_animaux.php">Voir mes animaux →</a></div><?php endif; ?>
      <form method="POST" enctype="multipart/form-data">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nom de l'animal *</label>
            <input type="text" name="nom" class="form-input" placeholder="Ex: Max" required/>
          </div>
          <div class="form-group">
            <label class="form-label">Type *</label>
            <select name="type" class="form-select" required>
              <option value="">-- Choisir --</option>
              <option value="Chien">Chien</option>
              <option value="Chat">Chat</option>
            
              <option value="Oiseau">Oiseau</option>
              <option value="hamster">hamster</option>
            </select>
          </div>
        </div>
        <div class="form-group">
        <label class="form-label">Âge *</label>
<div style="display: flex; gap: 10px;">
   
    <input type="number" name="age_valeur" class="form-input" placeholder="Ex: 3" min="0" required style="flex: 2;"/>
    
 
    <select name="age_unite" class="form-input" style="flex: 1;">
        <option value="mois">Mois</option>
        <option value="ans" selected>Ans</option>
    </select>
</div>
        </div>
        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-textarea" placeholder="Décrivez le caractère, les habitudes de l'animal..."></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Photo</label>
          <input type="file" name="image" class="form-input" accept="image/*"/>
        </div>
        <button type="submit" class="btn-submit"><i class="fa-solid fa-plus"></i> Publier l'annonce</button>
      </form>
    </div>
  </div>
</div>
<script>
document.getElementById('hamburger').addEventListener('click', () => { document.querySelector('.nav-links').classList.toggle('open'); });
</script>
</body>
</html>
