<?php
session_start();
if(!isset($_SESSION['user_id'])) { header('Location: connexion.php'); exit; }
require_once 'config.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM animaux WHERE id=? AND id_utilisateur=?");
$stmt->execute([$id, $_SESSION['user_id']]);
$animal = $stmt->fetch();
if(!$animal) { header('Location: mes_animaux.php'); exit; }

$error = ''; $success = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom        = trim($_POST['nom'] ?? '');
  $type       = trim($_POST['type'] ?? '');
  $age_valeur = intval($_POST['age_valeur'] ?? 0);
  $age_unite  = trim($_POST['age_unite'] ?? 'ans');
  $desc       = trim($_POST['description'] ?? '');
  $img        = $animal['image'];
  if(!empty($_FILES['image']['name'])) {
      $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
      $img = uniqid() . '.' . $ext;
      if(!is_dir('uploads')) mkdir('uploads', 0755, true);
      move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $img);
  }
  if($nom && $type && $age_valeur > 0) {
      $pdo->prepare("UPDATE animaux SET nom=?, type=?, age=?, age_unite=?, image=?, description=? WHERE id=? AND id_utilisateur=?")
          ->execute([$nom, $type, $age_valeur, $age_unite, $img, $desc, $id, $_SESSION['user_id']]);
      $success = "Animal mis à jour !";
      $animal = array_merge($animal, ['nom'=>$nom,'type'=>$type,'age'=>$age_valeur,'age_unite'=>$age_unite,'description'=>$desc,'image'=>$img]);
  } else { $error = "Remplissez les champs obligatoires."; }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Modifier — Animozen</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="style.css"/>
</head>
<body>
<nav class="navbar scrolled">
  <div class="nav-container">
    <a href="index.php" class="nav-logo"><i class="fa-solid fa-paw"></i><span>Animozen</span></a>
    <ul class="nav-links"><li><a href="mes_animaux.php">← Mes animaux</a></li></ul>
    <div class="nav-auth"><a href="logout.php" class="btn-nav">Déconnexion</a></div>
    <button class="hamburger" id="hamburger"><i class="fa-solid fa-bars"></i></button>
  </div>
</nav>
<div class="page-top">
  <div class="page-header"><h1>Modifier l'annonce</h1><p><?= htmlspecialchars($animal['nom']) ?></p></div>
  <div class="form-wrap">
    <div class="form-card">
      <h2>Modifier les informations</h2>
      <?php if($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
      <?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?> <a href="mes_animaux.php">Retour →</a></div><?php endif; ?>
      <form method="POST" enctype="multipart/form-data">
        <div class="form-row">
          <div class="form-group"><label class="form-label">Nom *</label><input type="text" name="nom" class="form-input" value="<?= htmlspecialchars($animal['nom']) ?>" required/></div>
          <div class="form-group">
            <label class="form-label">Type *</label>
            <select name="type" class="form-select" required>
              <?php foreach(['Chien','Chat','Oiseau','Autre'] as $t): ?>
                <option value="<?= $t ?>" <?= $animal['type']===$t?'selected':'' ?>><?= $t ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-group">
  <label class="form-label">Âge *</label>
  <div style="display:flex;gap:10px;">
    <input type="number" name="age_valeur" class="form-input" 
           value="<?= intval($animal['age']) ?>" 
           placeholder="Ex: 3" min="0" required style="flex:2;"/>
    <select name="age_unite" class="form-input" style="flex:1;">
      <option value="mois" <?= ($animal['age_unite'] ?? 'ans') === 'mois' ? 'selected' : '' ?>>Mois</option>
      <option value="ans"  <?= ($animal['age_unite'] ?? 'ans') === 'ans'  ? 'selected' : '' ?>>Ans</option>
    </select>
  </div>
</div>        <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-textarea"><?= htmlspecialchars($animal['description'] ?? '') ?></textarea></div>
        <div class="form-group"><label class="form-label">Nouvelle photo</label><input type="file" name="image" class="form-input" accept="image/*"/></div>
        <button type="submit" class="btn-submit"><i class="fa-solid fa-floppy-disk"></i> Enregistrer</button>
      </form>
    </div>
  </div>
</div>
<script>document.getElementById('hamburger').addEventListener('click',()=>{document.querySelector('.nav-links').classList.toggle('open');});</script>
</body>
</html>
