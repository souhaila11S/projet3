<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') { header('Location: index.php'); exit; }
require_once 'config.php';

// Admin can delete any animal
if(isset($_GET['delete_animal'])) {
    $pdo->prepare("DELETE FROM animaux WHERE id=?")->execute([intval($_GET['delete_animal'])]);
    header('Location: admin.php'); exit;
}
if(isset($_GET['delete_user'])) {
    $del = intval($_GET['delete_user']);
    if($del !== $_SESSION['user_id']) {
        $pdo->prepare("DELETE FROM utilisateurs WHERE id=?")->execute([$del]);
    }
    header('Location: admin.php'); exit;
}
if(isset($_GET['delete_msg'])) {
    $pdo->prepare("DELETE FROM messages WHERE id=?")->execute([intval($_GET['delete_msg'])]);
    header('Location: admin.php'); exit;
}

$animaux = $pdo->query("SELECT a.*, u.nom as owner FROM animaux a LEFT JOIN utilisateurs u ON a.id_utilisateur=u.id ORDER BY a.id DESC")->fetchAll();
$users   = $pdo->query("SELECT * FROM utilisateurs ORDER BY id DESC")->fetchAll();
$messages= $pdo->query("SELECT * FROM messages ORDER BY date_envoi DESC")->fetchAll();
$tab = $_GET['tab'] ?? 'animaux';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Admin — PetShelter</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="style.css"/>
</head>
<body>
<nav class="navbar scrolled">
  <div class="nav-container">
    <a href="index.php" class="nav-logo"><i class="fa-solid fa-paw"></i><span>PetShelter</span></a>
    <ul class="nav-links">
      <li><a href="index.php">Site</a></li>
      <li><a href="admin.php?tab=animaux" <?= $tab==='animaux'?'class="active"':'' ?>>Animaux (<?= count($animaux) ?>)</a></li>
      <li><a href="admin.php?tab=users" <?= $tab==='users'?'class="active"':'' ?>>Utilisateurs (<?= count($users) ?>)</a></li>
      <li><a href="admin.php?tab=messages" <?= $tab==='messages'?'class="active"':'' ?>>Messages (<?= count($messages) ?>)</a></li>
    </ul>
    <div class="nav-auth"><a href="logout.php" class="btn-nav">Déconnexion</a></div>
    <button class="hamburger" id="hamburger"><i class="fa-solid fa-bars"></i></button>
  </div>
</nav>
<div class="page-top">
  <div class="page-header" style="background:linear-gradient(135deg,#2d1508,#5c3d1e);">
    <h1> Dashboard Admin</h1>
    <p>Gestion complète du site</p>
  </div>
  <div class="section">

  <?php if($tab === 'animaux'): ?>
    <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--brown-dark);margin-bottom:1.5rem;">Tous les animaux</h2>
    <table class="manage-table">
      <thead><tr><th>#</th><th>Nom</th><th>Type</th><th>Âge</th><th>Propriétaire</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach($animaux as $a): ?>
        <tr>
          <td><?= $a['id'] ?></td>
          <td><strong><?= htmlspecialchars($a['nom']) ?></strong></td>
          <td><?= htmlspecialchars($a['type']) ?></td>
          <td><?= $a['age'] ?> ans</td>
          <td><?= htmlspecialchars($a['owner'] ?? '—') ?></td>
          <td>
            <a href="detail.php?id=<?= $a['id'] ?>" class="btn-edit">Voir</a>
            <a href="admin.php?delete_animal=<?= $a['id'] ?>&tab=animaux" class="btn-delete" onclick="return confirm('Supprimer ?')"><i class="fa-solid fa-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  <?php elseif($tab === 'users'): ?>
    <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--brown-dark);margin-bottom:1.5rem;">Utilisateurs</h2>
    <table class="manage-table">
      <thead><tr><th>#</th><th>Nom</th><th>Email</th><th>WhatsApp</th><th>Rôle</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach($users as $u): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= htmlspecialchars($u['nom']) ?></td>
          <td><?= htmlspecialchars($u['email']) ?></td>
          <td><?= htmlspecialchars($u['whatsapp'] ?? '—') ?></td>
          <td><span class="badge <?= $u['role']==='admin'?'badge-adopted':'badge-available' ?>"><?= $u['role'] ?></span></td>
          <td>
            <?php if($u['id'] !== $_SESSION['user_id']): ?>
            <a href="admin.php?delete_user=<?= $u['id'] ?>&tab=users" class="btn-delete" onclick="return confirm('Supprimer cet utilisateur ?')"><i class="fa-solid fa-trash"></i> Supprimer</a>
            <?php else: ?>
              <span style="font-size:12px;color:var(--text3);">Vous</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  <?php elseif($tab === 'messages'): ?>
    <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--brown-dark);margin-bottom:1.5rem;">Messages reçus</h2>
    <table class="manage-table">
      <thead><tr><th>#</th><th>Nom</th><th>Email</th><th>Message</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach($messages as $m): ?>
        <tr>
          <td><?= $m['id'] ?></td>
          <td><?= htmlspecialchars($m['nom']) ?></td>
          <td><?= htmlspecialchars($m['email']) ?></td>
          <td style="max-width:250px;"><?= htmlspecialchars(substr($m['message'],0,80)) ?>...</td>
          <td style="font-size:12px;"><?= date('d/m/Y', strtotime($m['date_envoi'])) ?></td>
          <td><a href="admin.php?delete_msg=<?= $m['id'] ?>&tab=messages" class="btn-delete" onclick="return confirm('Supprimer ?')"><i class="fa-solid fa-trash"></i></a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  </div>
</div>
<script>document.getElementById('hamburger').addEventListener('click',()=>{document.querySelector('.nav-links').classList.toggle('open');});</script>
</body>
</html>
