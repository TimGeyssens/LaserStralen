<?php
require_once __DIR__ . '/config.php';

$json = file_get_contents(DATA_FILE);
$c = json_decode($json, true) ?? [];

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($c['meta']['site_title'] ?? 'Laser Stralen') ?></title>
  <meta name="description" content="<?= e($c['meta']['meta_description'] ?? '') ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --neon: #4dff00;
      --neon-dim: #3acc00;
      --neon-glow: rgba(77, 255, 0, 0.3);
      --dark: #0a0a0a;
      --dark-card: #111111;
      --dark-mid: #1a1a1a;
      --white: #ffffff;
      --light: #f5f5f5;
      --text: #111111;
      --text-light: #555555;
      --text-on-dark: rgba(255, 255, 255, 0.8);
      --radius: 4px;
    }

    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; color: var(--text); line-height: 1.6; background: var(--dark); }

    /* Navigation */
    nav { position: fixed; top: 0; width: 100%; background: rgba(10,10,10,0.95); backdrop-filter: blur(12px); z-index: 1000; padding: 0 1.5rem; border-bottom: 1px solid rgba(77,255,0,0.15); }
    .nav-inner { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; height: 68px; }
    .logo { font-family: 'Russo One', sans-serif; font-size: 1.5rem; color: var(--white); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; text-transform: uppercase; letter-spacing: 1px; }
    .logo .beam { color: var(--neon); text-shadow: 0 0 10px var(--neon-glow); }
    .nav-links { display: flex; list-style: none; gap: 2rem; }
    .nav-links a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px; transition: color 0.2s; }
    .nav-links a:hover { color: var(--neon); }
    .hamburger { display: none; flex-direction: column; gap: 5px; background: none; border: none; cursor: pointer; padding: 4px; }
    .hamburger span { display: block; width: 24px; height: 2px; background: var(--white); transition: 0.3s; }

    /* Hero */
    .hero { min-height: 100vh; display: flex; align-items: center; justify-content: center; text-align: center; background: var(--dark); color: var(--white); padding: 6rem 1.5rem 4rem; position: relative; overflow: hidden; }
    .hero::before { content: ''; position: absolute; top: -20%; left: -10%; width: 55%; height: 140%; background: var(--neon); transform: skewX(-12deg); opacity: 1; z-index: 0; }
    .hero::after { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: repeating-linear-gradient(90deg, transparent, transparent 60px, rgba(255,255,255,0.02) 60px, rgba(255,255,255,0.02) 61px); z-index: 1; pointer-events: none; }
    .hero-content { position: relative; z-index: 2; max-width: 800px; }
    .hero h1 { font-family: 'Russo One', sans-serif; font-size: clamp(2.5rem, 7vw, 5rem); font-weight: 400; line-height: 1.05; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 2px; text-shadow: 2px 4px 20px rgba(0,0,0,0.5); }
    .hero h1 .highlight { color: var(--dark); background: var(--white); padding: 0 0.3em; display: inline-block; transform: skewX(-3deg); }
    .hero p { font-size: clamp(1rem, 2.5vw, 1.2rem); color: rgba(255,255,255,0.85); max-width: 560px; margin: 0 auto 2.5rem; text-shadow: 1px 2px 8px rgba(0,0,0,0.4); }
    .btn { display: inline-block; padding: 1rem 2.5rem; font-size: 0.9rem; font-weight: 700; text-decoration: none; text-transform: uppercase; letter-spacing: 2px; transition: all 0.25s; cursor: pointer; border: none; font-family: 'Inter', sans-serif; transform: skewX(-3deg); }
    .btn > span { display: inline-block; transform: skewX(3deg); }
    .btn-primary { background: var(--neon); color: var(--dark); }
    .btn-primary:hover { background: var(--white); transform: skewX(-3deg) translateY(-2px); box-shadow: 0 8px 30px var(--neon-glow); }
    .btn-outline { border: 2px solid var(--white); color: var(--white); background: transparent; margin-left: 1rem; }
    .btn-outline:hover { border-color: var(--neon); color: var(--neon); }
    .race-number { position: absolute; top: 50%; right: 8%; transform: translateY(-50%) skewX(-8deg); font-family: 'Russo One', sans-serif; font-size: clamp(8rem, 20vw, 16rem); color: rgba(255,255,255,0.04); line-height: 1; z-index: 1; user-select: none; }

    /* Sections */
    section { padding: 5rem 1.5rem; }
    .section-inner { max-width: 1100px; margin: 0 auto; }
    .section-title { font-family: 'Russo One', sans-serif; font-size: clamp(1.6rem, 4vw, 2.6rem); font-weight: 400; margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 1px; }
    .section-subtitle { color: var(--text-light); font-size: 1.05rem; max-width: 600px; margin-bottom: 3rem; }
    .green-bar { width: 60px; height: 4px; background: var(--neon); margin-bottom: 1.5rem; transform: skewX(-12deg); }
    .centered { text-align: center; }
    .centered .green-bar { margin-left: auto; margin-right: auto; }
    .centered .section-subtitle { margin-left: auto; margin-right: auto; }

    /* Wat */
    #wat { background: var(--white); }
    .intro-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center; }
    .intro-visual { background: var(--dark); height: 380px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; clip-path: polygon(0 0, 100% 0, 95% 100%, 0% 100%); }
    .laser-demo { position: relative; width: 120px; height: 200px; }
    .laser-demo .source { width: 40px; height: 40px; background: var(--neon); border-radius: 50%; margin: 0 auto; box-shadow: 0 0 40px var(--neon), 0 0 80px var(--neon-glow); animation: neon-pulse 2s ease-in-out infinite alternate; }
    @keyframes neon-pulse { 0% { box-shadow: 0 0 30px var(--neon), 0 0 60px var(--neon-glow); } 100% { box-shadow: 0 0 50px var(--neon), 0 0 100px var(--neon-glow); transform: scale(1.05); } }
    .laser-demo .beam-line { width: 3px; height: 100px; background: linear-gradient(to bottom, var(--neon), rgba(77,255,0,0.2)); margin: 0 auto; box-shadow: 0 0 15px var(--neon); animation: beam-flicker 1.5s ease-in-out infinite; }
    @keyframes beam-flicker { 0%, 100% { opacity: 0.8; height: 95px; } 50% { opacity: 1; height: 105px; } }
    .laser-demo .surface { width: 120px; height: 8px; background: linear-gradient(90deg, #444, #666, #888); position: relative; }
    .laser-demo .surface::after { content: ''; position: absolute; top: -6px; left: 50%; transform: translateX(-50%); width: 24px; height: 14px; background: radial-gradient(ellipse, var(--neon), transparent); opacity: 0.9; }
    .intro-visual::before { content: ''; position: absolute; top: -50%; right: -10%; width: 40%; height: 200%; background: var(--neon); opacity: 0.06; transform: skewX(-12deg); }
    .intro-text h3 { font-size: 1.15rem; margin-bottom: 1rem; color: var(--neon-dim); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
    .intro-text p { color: var(--text-light); margin-bottom: 1rem; }

    /* Voordelen */
    #voordelen { background: var(--light); }
    .voordelen-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    .voordeel-card { background: var(--white); border: 1px solid #e0e0e0; padding: 2rem; transition: transform 0.25s, box-shadow 0.25s; position: relative; overflow: hidden; }
    .voordeel-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--neon); transform: scaleY(0); transition: transform 0.3s; }
    .voordeel-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,0.1); }
    .voordeel-card:hover::before { transform: scaleY(1); }
    .voordeel-icon { width: 52px; height: 52px; background: var(--dark); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.25rem; transform: skewX(-6deg); }
    .voordeel-card h3 { font-size: 1.05rem; margin-bottom: 0.5rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .voordeel-card p { color: var(--text-light); font-size: 0.95rem; }

    /* Vergelijking */
    #vergelijk { background: var(--dark); color: var(--white); }
    #vergelijk .section-subtitle { color: rgba(255,255,255,0.5); }
    #vergelijk .green-bar { background: var(--neon); }
    .compare-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
    .compare-card { padding: 2.5rem; position: relative; }
    .compare-card.zand { background: var(--dark-mid); border: 1px solid rgba(255,255,255,0.08); }
    .compare-card.laser { background: var(--dark-card); border: 1px solid rgba(77,255,0,0.3); box-shadow: inset 0 0 40px rgba(77,255,0,0.03); }
    .compare-card.laser::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--neon); box-shadow: 0 0 15px var(--neon-glow); }
    .compare-card h3 { font-family: 'Russo One', sans-serif; font-size: 1.3rem; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px; }
    .compare-card.laser h3 { color: var(--neon); text-shadow: 0 0 20px var(--neon-glow); }
    .compare-list { list-style: none; display: flex; flex-direction: column; gap: 0.85rem; }
    .compare-list li { display: flex; align-items: flex-start; gap: 0.75rem; font-size: 0.95rem; color: rgba(255,255,255,0.75); }
    .compare-list .icon { flex-shrink: 0; margin-top: 2px; }

    /* Toepassingen */
    #toepassingen { background: var(--white); }
    .toep-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; }
    .toep-card { background: var(--light); padding: 2rem; border: 1px solid #e0e0e0; transition: all 0.25s; position: relative; }
    .toep-card:hover { border-color: var(--neon-dim); box-shadow: 0 4px 20px rgba(77,255,0,0.08); }
    .toep-card .emoji { font-size: 2rem; margin-bottom: 0.75rem; }
    .toep-card h3 { font-size: 1rem; font-weight: 700; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .toep-card p { color: var(--text-light); font-size: 0.9rem; }

    /* Werkwijze */
    #werkwijze { background: var(--dark); color: var(--white); }
    #werkwijze .section-subtitle { color: rgba(255,255,255,0.5); }
    .stappen { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; counter-reset: stap; }
    .stap { text-align: center; padding: 2rem 1.5rem; counter-increment: stap; background: var(--dark-mid); border: 1px solid rgba(255,255,255,0.06); position: relative; }
    .stap::before { content: counter(stap); display: flex; align-items: center; justify-content: center; width: 52px; height: 52px; background: var(--neon); color: var(--dark); font-family: 'Russo One', sans-serif; font-weight: 400; font-size: 1.3rem; margin: 0 auto 1.25rem; transform: skewX(-6deg); box-shadow: 0 0 20px var(--neon-glow); }
    .stap h3 { font-size: 1.05rem; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; }
    .stap p { color: rgba(255,255,255,0.5); font-size: 0.9rem; }

    /* CTA */
    .cta-section { background: var(--neon); color: var(--dark); text-align: center; position: relative; overflow: hidden; }
    .cta-section::before { content: ''; position: absolute; top: -50%; right: -5%; width: 40%; height: 200%; background: rgba(255,255,255,0.15); transform: skewX(-12deg); }
    .cta-section .section-inner { position: relative; z-index: 1; }
    .cta-section .section-title { color: var(--dark); }
    .cta-section .section-subtitle { color: rgba(0,0,0,0.6); margin-left: auto; margin-right: auto; }
    .cta-section .btn-primary { background: var(--dark); color: var(--neon); }
    .cta-section .btn-primary:hover { background: var(--white); color: var(--dark); }

    /* Contact */
    #contact { background: var(--light); }
    .contact-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; }
    .contact-info h3 { font-size: 1.1rem; font-weight: 700; margin-bottom: 1.25rem; text-transform: uppercase; letter-spacing: 1px; }
    .contact-item { display: flex; gap: 0.75rem; margin-bottom: 1.25rem; color: var(--text-light); }
    .contact-item strong { color: var(--text); }
    form { display: flex; flex-direction: column; gap: 1rem; }
    input, textarea, select { padding: 0.85rem 1rem; border: 2px solid #ddd; background: var(--white); font-size: 0.95rem; font-family: inherit; transition: border-color 0.2s; width: 100%; }
    input:focus, textarea:focus, select:focus { outline: none; border-color: var(--neon-dim); box-shadow: 0 0 0 3px rgba(77,255,0,0.1); }
    textarea { resize: vertical; min-height: 120px; }
    .form-btn { align-self: flex-start; }

    /* Footer */
    footer { background: var(--dark); color: rgba(255,255,255,0.35); text-align: center; padding: 2rem 1.5rem; font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase; border-top: 1px solid rgba(77,255,0,0.1); }

    /* Responsive */
    @media (max-width: 900px) {
      .intro-grid, .compare-grid, .contact-grid { grid-template-columns: 1fr; }
      .voordelen-grid { grid-template-columns: 1fr 1fr; }
      .stappen { grid-template-columns: 1fr 1fr; }
      .intro-visual { height: 280px; clip-path: polygon(0 0, 100% 0, 100% 95%, 0% 100%); }
      .race-number { display: none; }
    }
    @media (max-width: 640px) {
      .nav-links { display: none; flex-direction: column; position: absolute; top: 68px; left: 0; right: 0; background: rgba(10,10,10,0.98); padding: 1.5rem; gap: 1rem; border-bottom: 1px solid rgba(77,255,0,0.15); }
      .nav-links.open { display: flex; }
      .hamburger { display: flex; }
      .voordelen-grid { grid-template-columns: 1fr; }
      .stappen { grid-template-columns: 1fr; }
      .hero .btn-outline { margin-left: 0; margin-top: 0.75rem; }
      .hero-buttons { display: flex; flex-direction: column; align-items: center; gap: 0; }
      .hero::before { width: 120%; height: 45%; top: 0; left: -10%; transform: skewY(-5deg); }
      section { padding: 3.5rem 1.25rem; }
    }
  </style>
</head>
<body>

  <!-- Navigation -->
  <nav>
    <div class="nav-inner">
      <a href="#" class="logo">
        <span class="beam">//</span> LaserStralen
      </a>
      <ul class="nav-links" id="navLinks">
        <li><a href="#wat">Wat is het?</a></li>
        <li><a href="#voordelen">Voordelen</a></li>
        <li><a href="#vergelijk">Vergelijking</a></li>
        <li><a href="#toepassingen">Toepassingen</a></li>
        <li><a href="#werkwijze">Werkwijze</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
      <button class="hamburger" id="hamburger" aria-label="Menu openen">
        <span></span><span></span><span></span>
      </button>
    </div>
  </nav>

  <!-- Hero -->
  <section class="hero">
    <div class="race-number">91</div>
    <div class="hero-content">
      <h1><?= e($c['hero']['title_pre'] ?? '') ?> <span class="highlight"><?= e($c['hero']['title_highlight'] ?? 'Laser Stralen') ?></span></h1>
      <p><?= e($c['hero']['subtitle'] ?? '') ?></p>
      <div class="hero-buttons">
        <a href="#contact" class="btn btn-primary"><span><?= e($c['hero']['cta_primary'] ?? 'Offerte aanvragen') ?></span></a>
        <a href="#wat" class="btn btn-outline"><span><?= e($c['hero']['cta_secondary'] ?? 'Meer informatie') ?></span></a>
      </div>
    </div>
  </section>

  <!-- Wat is Laser Stralen -->
  <section id="wat">
    <div class="section-inner">
      <div class="intro-grid">
        <div class="intro-visual">
          <div class="laser-demo">
            <div class="source"></div>
            <div class="beam-line"></div>
            <div class="surface"></div>
          </div>
        </div>
        <div class="intro-text">
          <div class="green-bar"></div>
          <h2 class="section-title"><?= e($c['wat']['title'] ?? '') ?></h2>
          <h3><?= e($c['wat']['heading'] ?? '') ?></h3>
          <p><?= e($c['wat']['text_1'] ?? '') ?></p>
          <p><?= e($c['wat']['text_2'] ?? '') ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- Voordelen -->
  <section id="voordelen">
    <div class="section-inner centered">
      <div class="green-bar"></div>
      <h2 class="section-title"><?= e($c['voordelen']['title'] ?? '') ?></h2>
      <p class="section-subtitle"><?= e($c['voordelen']['subtitle'] ?? '') ?></p>
      <div class="voordelen-grid">
        <?php foreach (($c['voordelen']['items'] ?? []) as $item): ?>
        <div class="voordeel-card">
          <div class="voordeel-icon"><?= e($item['icon']) ?></div>
          <h3><?= e($item['title']) ?></h3>
          <p><?= e($item['text']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Vergelijking -->
  <section id="vergelijk">
    <div class="section-inner centered">
      <div class="green-bar"></div>
      <h2 class="section-title"><?= e($c['vergelijk']['title'] ?? '') ?></h2>
      <p class="section-subtitle"><?= e($c['vergelijk']['subtitle'] ?? '') ?></p>
      <div class="compare-grid">
        <div class="compare-card zand">
          <h3>Zandstralen</h3>
          <ul class="compare-list">
            <?php foreach (($c['vergelijk']['zandstralen'] ?? []) as $item): ?>
            <li><span class="icon">⚠️</span> <?= e($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="compare-card laser">
          <h3>Laser Stralen</h3>
          <ul class="compare-list">
            <?php foreach (($c['vergelijk']['laserstralen'] ?? []) as $item): ?>
            <li><span class="icon">✅</span> <?= e($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Toepassingen -->
  <section id="toepassingen">
    <div class="section-inner centered">
      <div class="green-bar"></div>
      <h2 class="section-title"><?= e($c['toepassingen']['title'] ?? '') ?></h2>
      <p class="section-subtitle"><?= e($c['toepassingen']['subtitle'] ?? '') ?></p>
      <div class="toep-grid">
        <?php foreach (($c['toepassingen']['items'] ?? []) as $item): ?>
        <div class="toep-card">
          <div class="emoji"><?= e($item['icon']) ?></div>
          <h3><?= e($item['title']) ?></h3>
          <p><?= e($item['text']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Werkwijze -->
  <section id="werkwijze">
    <div class="section-inner centered">
      <div class="green-bar"></div>
      <h2 class="section-title"><?= e($c['werkwijze']['title'] ?? '') ?></h2>
      <p class="section-subtitle"><?= e($c['werkwijze']['subtitle'] ?? '') ?></p>
      <div class="stappen">
        <?php foreach (($c['werkwijze']['stappen'] ?? []) as $stap): ?>
        <div class="stap">
          <h3><?= e($stap['title']) ?></h3>
          <p><?= e($stap['text']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="section-inner centered">
      <h2 class="section-title"><?= e($c['cta']['title'] ?? '') ?></h2>
      <p class="section-subtitle"><?= e($c['cta']['subtitle'] ?? '') ?></p>
      <a href="#contact" class="btn btn-primary"><span><?= e($c['cta']['button'] ?? 'Neem contact op') ?></span></a>
    </div>
  </section>

  <!-- Contact -->
  <section id="contact">
    <div class="section-inner">
      <div class="green-bar" style="margin:0 auto"></div>
      <h2 class="section-title centered"><?= e($c['contact']['title'] ?? 'Contact') ?></h2>
      <p class="section-subtitle centered" style="margin-left:auto;margin-right:auto;"><?= e($c['contact']['subtitle'] ?? '') ?></p>
      <div class="contact-grid">
        <div class="contact-info">
          <h3>Contactgegevens</h3>
          <div class="contact-item">
            <span>📞</span>
            <div><strong>Telefoon</strong><br><?= e($c['contact']['telefoon'] ?? '') ?></div>
          </div>
          <div class="contact-item">
            <span>✉️</span>
            <div><strong>E-mail</strong><br><?= e($c['contact']['email'] ?? '') ?></div>
          </div>
          <div class="contact-item">
            <span>📍</span>
            <div><strong>Locatie</strong><br><?= e($c['contact']['locatie'] ?? '') ?></div>
          </div>
          <div class="contact-item">
            <span>🕐</span>
            <div><strong>Bereikbaar</strong><br><?= e($c['contact']['bereikbaar'] ?? '') ?></div>
          </div>
        </div>
        <form id="contactForm" onsubmit="handleSubmit(event)">
          <input type="text" name="naam" placeholder="Uw naam" required>
          <input type="email" name="email" placeholder="Uw e-mailadres" required>
          <input type="tel" name="telefoon" placeholder="Telefoonnummer">
          <select name="dienst">
            <option value="" disabled selected>Waar gaat het over?</option>
            <?php foreach (($c['contact']['diensten'] ?? []) as $dienst): ?>
            <option><?= e($dienst) ?></option>
            <?php endforeach; ?>
          </select>
          <textarea name="bericht" placeholder="Vertel ons over uw project..." required></textarea>
          <button type="submit" class="btn btn-primary form-btn"><span><?= e($c['contact']['form_button'] ?? 'Verstuur bericht') ?></span></button>
        </form>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>&copy; <?= e($c['meta']['footer'] ?? date('Y') . ' LaserStralen. Alle rechten voorbehouden.') ?></p>
  </footer>

  <script>
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('navLinks');
    hamburger.addEventListener('click', () => navLinks.classList.toggle('open'));
    navLinks.querySelectorAll('a').forEach(link => link.addEventListener('click', () => navLinks.classList.remove('open')));

    function handleSubmit(e) {
      e.preventDefault();
      const form = e.target;
      const btn = form.querySelector('button[type="submit"]');
      btn.querySelector('span').textContent = 'Verzonden! ✓';
      btn.style.background = '#fff';
      setTimeout(() => {
        btn.querySelector('span').textContent = '<?= e($c['contact']['form_button'] ?? 'Verstuur bericht') ?>';
        btn.style.background = '';
        form.reset();
      }, 3000);
    }
  </script>

</body>
</html>
