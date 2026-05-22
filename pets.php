<?php
session_start();
require_once 'config.php';
$type_filter = trim($_GET['type'] ?? '');
$search = trim($_GET['q'] ?? '');
$sql = "SELECT a.*, u.whatsapp FROM animaux a LEFT JOIN utilisateurs u ON a.id_utilisateur = u.id WHERE 1=1";
$params = [];
if($type_filter) { $sql .= " AND LOWER(a.type) = LOWER(?)"; $params[] = $type_filter; }
if($search) { $sql .= " AND a.nom LIKE ?"; $params[] = "%$search%"; }
$sql .= " ORDER BY a.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$animaux = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Animaux — Animozen</title>
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
      <li><a href="pets.php" class="active">Animaux</a></li>
      <li><a href="contact.php">Contact</a></li>
      <?php if(isset($_SESSION['user_id'])): ?><li><a href="ajouter.php">+ Ajouter</a></li><?php endif; ?>
    </ul>
    <div class="nav-auth">
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="mes_animaux.php" class="btn-nav">Mes animaux</a>
        <a href="logout.php" class="btn-nav">Déconnexion</a>
      <?php else: ?>
        <a href="connexion.php" class="btn-nav-solid">Connexion</a>
      <?php endif; ?>
    </div>
    <button class="hamburger" id="hamburger"><i class="fa-solid fa-bars"></i></button>
  </div>
</nav>
<div class="page-top">
  <div class="page-header"><h1>Nos animaux</h1></div>
  <div style="max-width:1200px;margin:0 auto;padding:1.5rem 2.5rem;display:flex;gap:10px;flex-wrap:wrap;align-items:center;border-bottom:1px solid var(--border);">
    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;flex:1;">
      <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" class="form-input" placeholder="🔍 Rechercher..." style="max-width:220px;border-radius:999px;"/>
      <?php foreach([''=>'<i class="fa-solid fa-paw"></i> Tous','chien'=>'<i class="fa-solid fa-dog"></i> Chien','chat'=>'<i class="fa-solid fa-cat"></i> Chat','oiseau'=>'<i class="fa-solid fa-crow"></i> Oiseau','hamster'=>'<i class="fa-solid fa-otter"></i> Hamster'] as $t => $label):
    $active = strtolower($type_filter) === $t ? 'background:var(--beige);color:var(--brown-dark);border-color:var(--beige);' : '';
?>
<a href="pets.php?type=<?= $t ?>&q=<?= urlencode($search) ?>" style="padding:8px 18px;border:1.5px solid var(--border);border-radius:999px;font-size:13px;font-weight:600;cursor:pointer;background:var(--white);color:var(--text2);text-decoration:none;<?= $active ?>"><?= $label ?></a>
<?php endforeach; ?>
    </form>
  </div>
  <div class="section">
    <div class="pets-grid">
      <?php foreach($animaux as $a):
$type_lower = strtolower($a['type']);
$icon = $type_lower === 'chat'    ? '<i class="fa-solid fa-cat"></i>'
      : ($type_lower === 'oiseau'  ? '<i class="fa-solid fa-dove"></i>'
      : ($type_lower === 'hamster' ? '<i class="fa-solid fa-paw"></i>'
      :                              '<i class="fa-solid fa-dog"></i>'));      ?>
      <div class="pet-card">
        <div class="pet-thumb">
          <?php if(!empty($a['image']) && file_exists('uploads/'.$a['image'])): ?>
            <img src="uploads/<?= htmlspecialchars($a['image']) ?>" alt="<?= htmlspecialchars($a['nom']) ?>"/>
          <?php else: ?><span class="pet-thumb-placeholder" style="font-size:2.5rem;"><?= $icon ?></span>
          <?php endif; ?>
        </div>
        <div class="pet-body">
          <h3><?= htmlspecialchars($a['nom']) ?></h3>
          <p class="meta"><?= htmlspecialchars($a['type']) ?> · <?= $a['age'] ?> <?= $a['age_unite'] ?? 'ans' ?></p>          <div class="pet-foot">
            <span class="badge badge-available">Disponible</span>
            <div class="pet-actions">
              <a href="detail.php?id=<?= $a['id'] ?>" class="btn-detail">Plus</a>
              <?php if(!empty($a['whatsapp'])): ?>
                <a href="https://wa.me/<?= htmlspecialchars($a['whatsapp']) ?>?text=<?= urlencode('Bonjour, je veux adopter: '.$a['nom']) ?>" target="_blank" class="btn-adopt-sm">Adopter</a>
              <?php else: ?>
                <a href="detail.php?id=<?= $a['id'] ?>" class="btn-adopt-sm">Adopter</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if(empty($animaux)): ?>
        <div style="grid-column:1/-1;text-align:center;padding:4rem;color:var(--text3);">
          <div style="font-size:4rem;margin-bottom:1rem;">🔍</div>
          <p>Aucun animal trouvé.</p>
        </div>
      <?php endif; ?>
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
