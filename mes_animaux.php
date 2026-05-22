<?php
session_start();
if(!isset($_SESSION['user_id'])) { header('Location: connexion.php'); exit; }
require_once 'config.php';

// Delete
if(isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    $check = $pdo->prepare("SELECT id FROM animaux WHERE id=? AND id_utilisateur=?");
    $check->execute([$del_id, $_SESSION['user_id']]);
    if($check->fetch()) {
        $pdo->prepare("DELETE FROM animaux WHERE id=?")->execute([$del_id]);
    }
    header('Location: mes_animaux.php'); exit;
}

$stmt = $pdo->prepare("SELECT * FROM animaux WHERE id_utilisateur = ? ORDER BY id DESC");
$stmt->execute([$_SESSION['user_id']]);
$animaux = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Mes animaux — Animozen</title>
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
      <li><a href="ajouter.php">+ Ajouter</a></li>
      <li><a href="mes_animaux.php" class="active">Mes animaux</a></li>
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
    <h1>Mes animaux</h1>
    <p>Gérez vos annonces d'adoption</p>
  </div>
  <div class="section">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
      <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--brown-dark);">Mes annonces (<?= count($animaux) ?>)</h2>
      <a href="ajouter.php" class="btn-adopt"><i class="fa-solid fa-plus"></i> Ajouter un animal</a>
    </div>
    <?php if(empty($animaux)): ?>
      <div style="text-align:center;padding:4rem;background:var(--white);border-radius:var(--radius);border:1px solid var(--border);">
        <div style="font-size:4rem;margin-bottom:1rem;">🐾</div>
        <h3 style="font-family:'Playfair Display',serif;color:var(--brown-dark);margin-bottom:.5rem;">Aucun animal publié</h3>
        <p style="color:var(--text3);margin-bottom:1.5rem;">Ajoutez votre premier animal à adopter</p>
        <a href="ajouter.php" class="btn-adopt">Ajouter maintenant</a>
      </div>
    <?php else: ?>
    <table class="manage-table">
      <thead>
        <tr><th>Photo</th><th>Nom</th><th>Type</th><th>Âge</th><th>Description</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach($animaux as $a):
          $emoji = ($a['type'] === 'chat' || $a['type'] === 'Chat') ? '🐈' : (($a['type'] === '' || $a['type'] === '') ? '' : '🐕');
        ?>
        <tr>
          <td style="font-size:28px;">
            <?php if(!empty($a['image']) && file_exists('uploads/'.$a['image'])): ?>
              <img src="uploads/<?= htmlspecialchars($a['image']) ?>" style="width:48px;height:48px;object-fit:cover;border-radius:8px;"/>
            <?php else: ?>
              <?= $emoji ?>
            <?php endif; ?>
          </td>
          <td><strong><?= htmlspecialchars($a['nom']) ?></strong></td>
          <td><?= htmlspecialchars($a['type']) ?></td>
          <td><?= $a['age'] ?> <?= htmlspecialchars($a['age_unite'] ?? 'ans') ?></td>
          <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($a['description'] ?? '') ?></td>
          <td>
            <a href="modifier.php?id=<?= $a['id'] ?>" class="btn-edit"><i class="fa-solid fa-pen"></i> Modifier</a>
            <a href="mes_animaux.php?delete=<?= $a['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer cet animal ?')"><i class="fa-solid fa-trash"></i> Supprimer</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>
<script>
document.getElementById('hamburger').addEventListener('click', () => { document.querySelector('.nav-links').classList.toggle('open'); });
</script>
</body>
</html>
