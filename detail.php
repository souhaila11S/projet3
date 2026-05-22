<?php
session_start();
require_once 'config.php';
$id = intval($_GET['id'] ?? 0);
if(!$id) { header('Location: pets.php'); exit; }

$stmt = $pdo->prepare("SELECT a.*, u.nom as owner_nom, u.whatsapp FROM animaux a LEFT JOIN utilisateurs u ON a.id_utilisateur = u.id WHERE a.id = ?");
$stmt->execute([$id]);
$animal = $stmt->fetch();
if(!$animal) { header('Location: pets.php'); exit; }

// Commentaires
$comments = $pdo->prepare("SELECT c.*, u.nom as user_nom FROM commentaires c LEFT JOIN utilisateurs u ON c.id_utilisateur = u.id WHERE c.id_animal = ? ORDER BY c.date_commentaire DESC");
$comments->execute([$id]);
$commentaires = $comments->fetchAll();

// Ajouter commentaire
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $contenu = trim($_POST['contenu'] ?? '');
    if($contenu) {
        $ins = $pdo->prepare("INSERT INTO commentaires (contenu, id_utilisateur, id_animal) VALUES (?,?,?)");
        $ins->execute([$contenu, $_SESSION['user_id'], $id]);
        header("Location: detail.php?id=$id"); exit;
    }
}

$emoji = ($animal['type'] === 'chat' || $animal['type'] === 'Chat') ? '🐈' : (($animal['type'] === 'lapin' || $animal['type'] === 'Lapin') ? '🐇' : '🐕');
$wa_msg = urlencode("Bonjour, je suis intéressé par l'animal : " . $animal['nom']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?= htmlspecialchars($animal['nom']) ?> — Animozen</title>
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
      <li><a href="contact.php">Contact</a></li>
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
  <div style="max-width:1100px;margin:0 auto;padding:1.5rem 2.5rem 0;">
    <a href="pets.php" style="font-size:13px;color:var(--text3);display:inline-flex;align-items:center;gap:6px;">
      <i class="fa-solid fa-arrow-left"></i> Retour aux animaux
    </a>
  </div>
  <div class="detail-wrap">
    <div>
      <div class="detail-img-wrap">
        <?php if(!empty($animal['image']) && file_exists('uploads/'.$animal['image'])): ?>
          <img src="uploads/<?= htmlspecialchars($animal['image']) ?>" alt="<?= htmlspecialchars($animal['nom']) ?>"/>
        <?php else: ?>
          <span class="detail-placeholder"><?= $emoji ?></span>
        <?php endif; ?>
      </div>
    </div>
    <div class="detail-info">
      <h1><?= htmlspecialchars($animal['nom']) ?></h1>
      <div class="detail-meta">
        <span class="detail-tag"><i class="fa-solid fa-paw"></i> <?= htmlspecialchars($animal['type']) ?></span>
        <span class="detail-tag"><i class="fa-solid fa-calendar"></i> <?= $animal['age'] ?> <?= htmlspecialchars($animal['age_unite'] ?? 'ans') ?></span>
        <span class="detail-tag badge-available" style="background:#e1f5ee;color:#0f6e56;">Disponible</span>
      </div>
      <p class="detail-desc"><?= nl2br(htmlspecialchars($animal['description'] ?? 'Aucune description disponible.')) ?></p>

      <?php if(!empty($animal['whatsapp'])): ?>
        <a href="https://wa.me/<?= htmlspecialchars($animal['whatsapp']) ?>?text=<?= $wa_msg ?>" target="_blank" class="btn-whatsapp">
          <i class="fa-brands fa-whatsapp"></i> Adopter via WhatsApp
        </a>
      <?php else: ?>
        <a href="contact.php" class="btn-adopt">Contacter le refuge</a>
      <?php endif; ?>

      <div class="owner-info">
        <strong>Publié par :</strong> <?= htmlspecialchars($animal['owner_nom'] ?? 'Anonyme') ?>
      </div>
    </div>
  </div>

  <!-- COMMENTAIRES -->
  <div class="comments-section">
    <h2><i class="fa-solid fa-comments"></i> Commentaires (<?= count($commentaires) ?>)</h2>
    <?php foreach($commentaires as $c): ?>
    <div class="comment">
      <div class="comment-author"><i class="fa-solid fa-user"></i> <?= htmlspecialchars($c['user_nom'] ?? 'Anonyme') ?></div>
      <div class="comment-text"><?= htmlspecialchars($c['contenu']) ?></div>
      <div class="comment-date"><?= date('d/m/Y H:i', strtotime($c['date_commentaire'])) ?></div>
    </div>
    <?php endforeach; ?>
    <?php if(empty($commentaires)): ?>
      <p style="color:var(--text3);font-size:14px;">Aucun commentaire pour l'instant.</p>
    <?php endif; ?>

    <?php if(isset($_SESSION['user_id'])): ?>
    <div style="margin-top:2rem;">
      <h3 style="font-family:'Playfair Display',serif;font-size:1.2rem;color:var(--brown-dark);margin-bottom:1rem;">Laisser un commentaire</h3>
      <form method="POST">
        <div class="form-group">
          <textarea name="contenu" class="form-textarea" placeholder="Votre commentaire..." style="min-height:90px;" required></textarea>
        </div>
        <button type="submit" class="btn-submit" style="width:auto;padding:10px 24px;">Publier</button>
      </form>
    </div>
    <?php else: ?>
      <p style="margin-top:1.5rem;font-size:14px;color:var(--text3);"><a href="connexion.php" style="color:var(--brown);font-weight:700;">Connectez-vous</a> pour laisser un commentaire.</p>
    <?php endif; ?>
  </div>
</div>
<script>
document.getElementById('hamburger').addEventListener('click', () => { document.querySelector('.nav-links').classList.toggle('open'); });
</script>
</body>
</html>
