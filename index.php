<?php
session_start();
require_once 'config.php';


// Fetch animaux (latest 6)
$stmt = $pdo->query("SELECT a.*, u.whatsapp FROM animaux a LEFT JOIN utilisateurs u ON a.id_utilisateur = u.id ORDER BY a.id DESC LIMIT 6");
$animaux = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Animozen — Adoption Animaux</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="style.css"/>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
  <div class="nav-container">
    <a href="index.php" class="nav-logo"><i class="fa-solid fa-paw"></i><span>Animozen</span></a>
    <ul class="nav-links">
      <li><a href="index.php" class="active">Accueil</a></li>
      <li><a href="pets.php">Animaux</a></li>
      <li><a href="contact.php">Contact</a></li>
      <?php if(isset($_SESSION['user_id'])): ?>
        <li><a href="ajouter.php">+ Ajouter</a></li>
        <li><a href="mes_animaux.php">Mes animaux</a></li>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <li><a href="admin.php">Admin</a></li>
        <?php endif; ?>
      <?php endif; ?>
    </ul>
    <div class="nav-auth">
      <?php if(isset($_SESSION['user_id'])): ?>
        <span style="color:rgba(255,255,255,.7);font-size:13px;margin-right:8px;"><i class="fa-solid fa-user"></i> <?= htmlspecialchars($_SESSION['nom']) ?></span>
        <a href="logout.php" class="btn-nav">Déconnexion</a>
      <?php else: ?>
        <a href="inscription.php" class="btn-nav">Inscription</a>
        <a href="connexion.php" class="btn-nav-solid">Connexion</a>
      <?php endif; ?>
    </div>
    <button class="hamburger" id="hamburger"><i class="fa-solid fa-bars"></i></button>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <h1>Accueillez votre nouveau  <br/><span> compagnon</span></h1>
    <p>Notre refuge est plein d'animaux affectueux qui espèrent trouver un foyer pour la vie. Ils attendent quelqu'un comme vous pour leur donner de la chaleur, de l'amour et des soins.</p>
    <a href="pets.php" class="btn-adopt">Adopter un animal</a>
  </div>
</section>


<div class="section">
  <div class="section-header">
    <span class="sec-label">COMMENT ADOPTER ?</span>
    <h2>Adopter un compagnon en 4 étapes</h2>
  </div>
  <div class="cards-grid">
    <div class="card"><i class="fa-solid fa-hand"></i><h3>Contact Direct</h3><p>Trouvez votre compagnon et contactez son maître sur WhatsApp en un clic.</p></div>
    <div class="card"><i class="fa-solid fa-dog"></i><h3>Nos Animaux</h3><p>Des compagnons sains, vaccinés et prêts à rejoindre votre foye.</p></div>
    <div class="card"><i class="fa-solid fa-paw"></i><h3>Adoption Gratuite</h3><p>Donnez une seconde chance à un animal sans aucun frais..</p></div>
    <div class="card"><i class="fa-solid fa-house"></i><h3>Un Nouveau Foyer</h3><p>Offrez une nouvelle maison et une vraie famille à un animalt.</p></div>
  </div>
</div>

<!-- ANIMAUX -->
<div style="background:var(--white);border-top:1px solid var(--border);">
<div class="section">
  <div class="section-header">
    <span class="sec-label">Nos animaux</span>
    <h2>Ils cherchent une famille</h2>
    <p>Des compagnons de tous âges attendent leur foyer idéal.</p>
  </div>
  <div class="pets-grid">
    <?php foreach($animaux as $a): 
$type_lower = strtolower($a['type']);
$icon = $type_lower === 'chat'    ? '<i class="fa-solid fa-cat"></i>'
      : ($type_lower === 'oiseau'  ? '<i class="fa-solid fa-dove"></i>'
      : ($type_lower === 'hamster' ? '<i class="fa-solid fa-paw"></i>'
      :                              '<i class="fa-solid fa-dog"></i>'));    ?>
    <div class="pet-card">
      <div class="pet-thumb">
        <?php if(!empty($a['image']) && file_exists('uploads/'.$a['image'])): ?>
          <img src="uploads/<?= htmlspecialchars($a['image']) ?>" alt="<?= htmlspecialchars($a['nom']) ?>"/>
        <?php else: ?>
          <span class="pet-thumb-placeholder" style="font-size:2.5rem;"><?= $icon ?></span>        <?php endif; ?>
      </div>
      <div class="pet-body">
        <h3><?= htmlspecialchars($a['nom']) ?></h3>
        <p class="meta"><?= htmlspecialchars($a['type']) ?> · <?= $a['age'] ?> <?= htmlspecialchars($a['age_unite'] ?? 'ans') ?></p>
        <div class="pet-foot">
          <span class="badge badge-available">Disponible</span>
          <div class="pet-actions">
            <a href="detail.php?id=<?= $a['id'] ?>" class="btn-detail">Plus</a>
            <?php if(!empty($a['whatsapp'])): ?>
              <a href="https://wa.me/<?= htmlspecialchars($a['whatsapp']) ?>?text=Bonjour, je suis intéressé par <?= urlencode($a['nom']) ?>" target="_blank" class="btn-adopt-sm">Adopter</a>
            <?php else: ?>
              <a href="detail.php?id=<?= $a['id'] ?>" class="btn-adopt-sm">Adopter</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
    <?php if(empty($animaux)): ?>
      <div style="grid-column:1/-1;text-align:center;padding:3rem;color:var(--text3);">
        <i class="fa-solid fa-paw" style="font-size:3rem;margin-bottom:1rem;display:block;"></i>
        Aucun animal disponible pour l'instant.
      </div>
    <?php endif; ?>
  </div>
  <div style="text-align:center;">
    <a href="pets.php" class="btn-adopt">Voir tous les animaux</a>
  </div>
</div>
</div>

<!-- STATS -->
<div class="stats-bar">
  <?php
  $total  = $pdo->query("SELECT COUNT(*) FROM animaux")->fetchColumn();
  $users  = $pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE role='user'")->fetchColumn();
  ?>
  <div class="stat"><span class="stat-num"><?= $total ?>+</span><span class="stat-lbl">Animaux</span></div>
  <div class="stat"><span class="stat-num"><?= $users ?></span><span class="stat-lbl">Familles inscrites</span></div>
  <div class="stat"><span class="stat-num">98%</span><span class="stat-lbl">Satisfaits</span></div>
  <div class="stat"><span class="stat-num">5 ans</span><span class="stat-lbl">D'expérience</span></div>
</div>

<footer class="footer">
  <div class="footer-grid">
    <div>
      <div class="footer-brand"><i class="fa-solid fa-paw"></i> Animozen</div>
      <p class="footer-desc">Un refuge pour les animaux qui cherchent une famille aimante au Maroc. Adoptez, ne faites pas d'achats.</p>
    </div>
    <div class="footer-col">
      <h4>Navigation</h4>
      <a href="index.php">Accueil</a>
      <a href="pets.php">Animaux</a>
      <a href="contact.php">Contact</a>
    </div>
    <div class="footer-col">
      <h4>Compte</h4>
      <a href="connexion.php">Connexion</a>
      <a href="inscription.php">Inscription</a>
      <?php if(isset($_SESSION['user_id'])): ?>
      <a href="ajouter.php">Ajouter un animal</a>
      <a href="mes_animaux.php">Mes animaux</a>
      <?php endif; ?>
    </div>
    <div class="footer-col">
      <h4>Contact</h4>
      <p><i class="fa-solid fa-location-dot"></i> Tanger , Maroc</p>
      <p><i class="fa-solid fa-envelope"></i> contact@Animozen.ma</p>
      <p><i class="fa-solid fa-phone"></i> +212 554650060</p>
    </div>
  </div>
  <div class="footer-bottom">
    <span class="footer-copy">© 2025 Animozen · Tous droits réservés</span>
    <div class="footer-socials">
      <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
      <a href="#"><i class="fa-brands fa-instagram"></i></a>
      <!-- <a href="#"><i class="fa-brands fa-whatsapp"></i></a> -->
    </div>
  </div>
</footer>

<script>
window.addEventListener('scroll', () => {
  document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 60);
});
document.getElementById('hamburger').addEventListener('click', () => {
  document.querySelector('.nav-links').classList.toggle('open');
});
</script>
</body>
</html>
