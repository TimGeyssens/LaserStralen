<?php
require_once __DIR__ . '/auth.php';
require_login();

$content = load_content();
$success = $_GET['saved'] ?? '';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin — LaserStralen</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Inter', sans-serif;
      background: #0e0e0e;
      color: #e0e0e0;
      line-height: 1.6;
    }

    /* ── Top bar ── */
    .topbar {
      position: fixed;
      top: 0;
      width: 100%;
      height: 60px;
      background: #111;
      border-bottom: 1px solid rgba(77, 255, 0, 0.15);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 2rem;
      z-index: 100;
    }

    .topbar-logo {
      font-family: 'Russo One', sans-serif;
      font-size: 1.1rem;
      color: #fff;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .topbar-logo span { color: #4dff00; }

    .topbar-actions { display: flex; gap: 1rem; align-items: center; }

    .topbar-actions a {
      color: rgba(255,255,255,0.5);
      text-decoration: none;
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-weight: 600;
      transition: color 0.2s;
    }
    .topbar-actions a:hover { color: #4dff00; }
    .topbar-actions a.view-site { color: #4dff00; }

    /* ── Layout ── */
    .sidebar {
      position: fixed;
      top: 60px;
      left: 0;
      width: 220px;
      height: calc(100vh - 60px);
      background: #111;
      border-right: 1px solid rgba(255,255,255,0.06);
      padding: 1.5rem 0;
      overflow-y: auto;
    }

    .sidebar a {
      display: block;
      padding: 0.65rem 1.5rem;
      color: rgba(255,255,255,0.5);
      text-decoration: none;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      border-left: 3px solid transparent;
      transition: all 0.2s;
    }
    .sidebar a:hover, .sidebar a.active {
      color: #4dff00;
      background: rgba(77, 255, 0, 0.04);
      border-left-color: #4dff00;
    }

    .main {
      margin-left: 220px;
      margin-top: 60px;
      padding: 2rem;
      max-width: 900px;
    }

    /* ── Success message ── */
    .toast {
      position: fixed;
      top: 76px;
      right: 2rem;
      background: #111;
      border: 1px solid rgba(77, 255, 0, 0.3);
      color: #4dff00;
      padding: 0.75rem 1.5rem;
      font-size: 0.85rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      z-index: 200;
      animation: fadeout 3s forwards;
    }
    @keyframes fadeout {
      0%, 70% { opacity: 1; }
      100% { opacity: 0; pointer-events: none; }
    }

    /* ── Section cards ── */
    .section-card {
      background: #161616;
      border: 1px solid rgba(255,255,255,0.06);
      margin-bottom: 2rem;
      position: relative;
    }

    .section-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0;
      width: 4px; height: 100%;
      background: #4dff00;
    }

    .section-header {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid rgba(255,255,255,0.06);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .section-header h2 {
      font-family: 'Russo One', sans-serif;
      font-size: 1rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #fff;
    }

    .section-body {
      padding: 1.5rem;
    }

    /* ── Form elements ── */
    .field {
      margin-bottom: 1.25rem;
    }

    label {
      display: block;
      font-size: 0.7rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: rgba(255,255,255,0.4);
      margin-bottom: 0.4rem;
    }

    input[type="text"], textarea {
      width: 100%;
      padding: 0.7rem 0.9rem;
      background: #1a1a1a;
      border: 1px solid #333;
      color: #e0e0e0;
      font-size: 0.9rem;
      font-family: inherit;
      transition: border-color 0.2s;
    }

    input[type="text"]:focus, textarea:focus {
      outline: none;
      border-color: #4dff00;
      box-shadow: 0 0 0 2px rgba(77, 255, 0, 0.08);
    }

    textarea { resize: vertical; min-height: 80px; }

    /* ── Repeater items ── */
    .repeater-item {
      background: #111;
      border: 1px solid rgba(255,255,255,0.06);
      padding: 1.25rem;
      margin-bottom: 1rem;
      position: relative;
    }

    .repeater-item .item-header {
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: rgba(255,255,255,0.3);
      margin-bottom: 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 1px solid rgba(255,255,255,0.06);
    }

    .repeater-item .field-row {
      display: grid;
      grid-template-columns: 80px 1fr;
      gap: 1rem;
    }

    .repeater-item .field-row.full {
      grid-template-columns: 1fr;
    }

    .btn-add {
      display: inline-block;
      padding: 0.5rem 1rem;
      background: transparent;
      border: 1px dashed rgba(77, 255, 0, 0.3);
      color: #4dff00;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      cursor: pointer;
      font-family: inherit;
      transition: all 0.2s;
      margin-top: 0.5rem;
    }
    .btn-add:hover {
      background: rgba(77, 255, 0, 0.05);
      border-color: #4dff00;
    }

    .btn-remove {
      position: absolute;
      top: 0.75rem;
      right: 0.75rem;
      background: none;
      border: none;
      color: rgba(255,255,255,0.2);
      cursor: pointer;
      font-size: 1.1rem;
      transition: color 0.2s;
      font-family: inherit;
    }
    .btn-remove:hover { color: #ff4444; }

    /* ── Save button ── */
    .save-bar {
      position: sticky;
      bottom: 0;
      background: #111;
      border-top: 1px solid rgba(77, 255, 0, 0.15);
      padding: 1rem 1.5rem;
      margin: 0 -2rem;
      display: flex;
      justify-content: flex-end;
    }

    .btn-save {
      padding: 0.8rem 2rem;
      background: #4dff00;
      color: #0a0a0a;
      border: none;
      font-family: 'Inter', sans-serif;
      font-size: 0.8rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 2px;
      cursor: pointer;
      transform: skewX(-3deg);
      transition: all 0.2s;
    }
    .btn-save span { display: inline-block; transform: skewX(3deg); }
    .btn-save:hover {
      background: #fff;
      transform: skewX(-3deg) translateY(-1px);
      box-shadow: 0 4px 15px rgba(77, 255, 0, 0.2);
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
      .sidebar { display: none; }
      .main { margin-left: 0; }
      .repeater-item .field-row {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

  <!-- Top bar -->
  <div class="topbar">
    <div class="topbar-logo"><span>//</span> LaserStralen Admin</div>
    <div class="topbar-actions">
      <a href="../index.php" class="view-site" target="_blank">↗ Website bekijken</a>
      <a href="logout.php">Uitloggen</a>
    </div>
  </div>

  <!-- Sidebar -->
  <nav class="sidebar">
    <a href="#meta">Meta / SEO</a>
    <a href="#hero">Hero</a>
    <a href="#wat">Wat is het?</a>
    <a href="#voordelen">Voordelen</a>
    <a href="#vergelijk">Vergelijking</a>
    <a href="#toepassingen">Toepassingen</a>
    <a href="#werkwijze">Werkwijze</a>
    <a href="#cta">CTA</a>
    <a href="#contact">Contact</a>
  </nav>

  <?php if ($success): ?>
    <div class="toast">✓ Wijzigingen opgeslagen</div>
  <?php endif; ?>

  <!-- Main content -->
  <div class="main">
    <form method="POST" action="save.php">

      <!-- Meta -->
      <div class="section-card" id="meta">
        <div class="section-header"><h2>Meta / SEO</h2></div>
        <div class="section-body">
          <div class="field">
            <label>Pagina titel</label>
            <input type="text" name="meta[site_title]" value="<?= e($content['meta']['site_title'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Meta beschrijving</label>
            <textarea name="meta[meta_description]" rows="2"><?= e($content['meta']['meta_description'] ?? '') ?></textarea>
          </div>
          <div class="field">
            <label>Footer tekst</label>
            <input type="text" name="meta[footer]" value="<?= e($content['meta']['footer'] ?? '') ?>">
          </div>
        </div>
      </div>

      <!-- Hero -->
      <div class="section-card" id="hero">
        <div class="section-header"><h2>Hero</h2></div>
        <div class="section-body">
          <div class="field">
            <label>Titel (voor highlight)</label>
            <input type="text" name="hero[title_pre]" value="<?= e($content['hero']['title_pre'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Titel (highlight)</label>
            <input type="text" name="hero[title_highlight]" value="<?= e($content['hero']['title_highlight'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Ondertitel</label>
            <textarea name="hero[subtitle]" rows="2"><?= e($content['hero']['subtitle'] ?? '') ?></textarea>
          </div>
          <div class="field">
            <label>CTA Primair</label>
            <input type="text" name="hero[cta_primary]" value="<?= e($content['hero']['cta_primary'] ?? '') ?>">
          </div>
          <div class="field">
            <label>CTA Secundair</label>
            <input type="text" name="hero[cta_secondary]" value="<?= e($content['hero']['cta_secondary'] ?? '') ?>">
          </div>
        </div>
      </div>

      <!-- Wat is het -->
      <div class="section-card" id="wat">
        <div class="section-header"><h2>Wat is Laser Stralen?</h2></div>
        <div class="section-body">
          <div class="field">
            <label>Titel</label>
            <input type="text" name="wat[title]" value="<?= e($content['wat']['title'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Subtitel</label>
            <input type="text" name="wat[heading]" value="<?= e($content['wat']['heading'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Tekst alinea 1</label>
            <textarea name="wat[text_1]" rows="3"><?= e($content['wat']['text_1'] ?? '') ?></textarea>
          </div>
          <div class="field">
            <label>Tekst alinea 2</label>
            <textarea name="wat[text_2]" rows="3"><?= e($content['wat']['text_2'] ?? '') ?></textarea>
          </div>
        </div>
      </div>

      <!-- Voordelen -->
      <div class="section-card" id="voordelen">
        <div class="section-header"><h2>Voordelen</h2></div>
        <div class="section-body">
          <div class="field">
            <label>Titel</label>
            <input type="text" name="voordelen[title]" value="<?= e($content['voordelen']['title'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Ondertitel</label>
            <textarea name="voordelen[subtitle]" rows="2"><?= e($content['voordelen']['subtitle'] ?? '') ?></textarea>
          </div>
          <div id="voordelen-items">
            <?php foreach (($content['voordelen']['items'] ?? []) as $i => $item): ?>
            <div class="repeater-item" data-group="voordelen">
              <button type="button" class="btn-remove" onclick="this.parentElement.remove()">✕</button>
              <div class="item-header">Voordeel <?= $i + 1 ?></div>
              <div class="field-row">
                <div class="field">
                  <label>Icoon</label>
                  <input type="text" name="voordelen[items][<?= $i ?>][icon]" value="<?= e($item['icon']) ?>">
                </div>
                <div class="field">
                  <label>Titel</label>
                  <input type="text" name="voordelen[items][<?= $i ?>][title]" value="<?= e($item['title']) ?>">
                </div>
              </div>
              <div class="field">
                <label>Tekst</label>
                <textarea name="voordelen[items][<?= $i ?>][text]" rows="2"><?= e($item['text']) ?></textarea>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <button type="button" class="btn-add" onclick="addRepeaterItem('voordelen')">+ Voordeel toevoegen</button>
        </div>
      </div>

      <!-- Vergelijking -->
      <div class="section-card" id="vergelijk">
        <div class="section-header"><h2>Vergelijking</h2></div>
        <div class="section-body">
          <div class="field">
            <label>Titel</label>
            <input type="text" name="vergelijk[title]" value="<?= e($content['vergelijk']['title'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Ondertitel</label>
            <textarea name="vergelijk[subtitle]" rows="2"><?= e($content['vergelijk']['subtitle'] ?? '') ?></textarea>
          </div>
          <div class="field">
            <label>Zandstralen nadelen (één per regel)</label>
            <textarea name="vergelijk[zandstralen_text]" rows="6"><?= e(implode("\n", $content['vergelijk']['zandstralen'] ?? [])) ?></textarea>
          </div>
          <div class="field">
            <label>Laser stralen voordelen (één per regel)</label>
            <textarea name="vergelijk[laserstralen_text]" rows="6"><?= e(implode("\n", $content['vergelijk']['laserstralen'] ?? [])) ?></textarea>
          </div>
        </div>
      </div>

      <!-- Toepassingen -->
      <div class="section-card" id="toepassingen">
        <div class="section-header"><h2>Toepassingen</h2></div>
        <div class="section-body">
          <div class="field">
            <label>Titel</label>
            <input type="text" name="toepassingen[title]" value="<?= e($content['toepassingen']['title'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Ondertitel</label>
            <textarea name="toepassingen[subtitle]" rows="2"><?= e($content['toepassingen']['subtitle'] ?? '') ?></textarea>
          </div>
          <div id="toepassingen-items">
            <?php foreach (($content['toepassingen']['items'] ?? []) as $i => $item): ?>
            <div class="repeater-item" data-group="toepassingen">
              <button type="button" class="btn-remove" onclick="this.parentElement.remove()">✕</button>
              <div class="item-header">Toepassing <?= $i + 1 ?></div>
              <div class="field-row">
                <div class="field">
                  <label>Icoon</label>
                  <input type="text" name="toepassingen[items][<?= $i ?>][icon]" value="<?= e($item['icon']) ?>">
                </div>
                <div class="field">
                  <label>Titel</label>
                  <input type="text" name="toepassingen[items][<?= $i ?>][title]" value="<?= e($item['title']) ?>">
                </div>
              </div>
              <div class="field">
                <label>Tekst</label>
                <textarea name="toepassingen[items][<?= $i ?>][text]" rows="2"><?= e($item['text']) ?></textarea>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <button type="button" class="btn-add" onclick="addRepeaterItem('toepassingen')">+ Toepassing toevoegen</button>
        </div>
      </div>

      <!-- Werkwijze -->
      <div class="section-card" id="werkwijze">
        <div class="section-header"><h2>Werkwijze</h2></div>
        <div class="section-body">
          <div class="field">
            <label>Titel</label>
            <input type="text" name="werkwijze[title]" value="<?= e($content['werkwijze']['title'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Ondertitel</label>
            <textarea name="werkwijze[subtitle]" rows="2"><?= e($content['werkwijze']['subtitle'] ?? '') ?></textarea>
          </div>
          <div id="werkwijze-items">
            <?php foreach (($content['werkwijze']['stappen'] ?? []) as $i => $stap): ?>
            <div class="repeater-item" data-group="werkwijze">
              <button type="button" class="btn-remove" onclick="this.parentElement.remove()">✕</button>
              <div class="item-header">Stap <?= $i + 1 ?></div>
              <div class="field-row full">
                <div class="field">
                  <label>Titel</label>
                  <input type="text" name="werkwijze[stappen][<?= $i ?>][title]" value="<?= e($stap['title']) ?>">
                </div>
              </div>
              <div class="field">
                <label>Tekst</label>
                <textarea name="werkwijze[stappen][<?= $i ?>][text]" rows="2"><?= e($stap['text']) ?></textarea>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <button type="button" class="btn-add" onclick="addRepeaterItem('werkwijze')">+ Stap toevoegen</button>
        </div>
      </div>

      <!-- CTA -->
      <div class="section-card" id="cta">
        <div class="section-header"><h2>CTA Sectie</h2></div>
        <div class="section-body">
          <div class="field">
            <label>Titel</label>
            <input type="text" name="cta[title]" value="<?= e($content['cta']['title'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Ondertitel</label>
            <textarea name="cta[subtitle]" rows="2"><?= e($content['cta']['subtitle'] ?? '') ?></textarea>
          </div>
          <div class="field">
            <label>Knoptekst</label>
            <input type="text" name="cta[button]" value="<?= e($content['cta']['button'] ?? '') ?>">
          </div>
        </div>
      </div>

      <!-- Contact -->
      <div class="section-card" id="contact">
        <div class="section-header"><h2>Contact</h2></div>
        <div class="section-body">
          <div class="field">
            <label>Titel</label>
            <input type="text" name="contact[title]" value="<?= e($content['contact']['title'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Ondertitel</label>
            <textarea name="contact[subtitle]" rows="2"><?= e($content['contact']['subtitle'] ?? '') ?></textarea>
          </div>
          <div class="field">
            <label>Telefoon</label>
            <input type="text" name="contact[telefoon]" value="<?= e($content['contact']['telefoon'] ?? '') ?>">
          </div>
          <div class="field">
            <label>E-mail</label>
            <input type="text" name="contact[email]" value="<?= e($content['contact']['email'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Locatie</label>
            <input type="text" name="contact[locatie]" value="<?= e($content['contact']['locatie'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Bereikbaar</label>
            <input type="text" name="contact[bereikbaar]" value="<?= e($content['contact']['bereikbaar'] ?? '') ?>">
          </div>
          <div class="field">
            <label>Diensten (één per regel)</label>
            <textarea name="contact[diensten_text]" rows="6"><?= e(implode("\n", $content['contact']['diensten'] ?? [])) ?></textarea>
          </div>
          <div class="field">
            <label>Formulier knoptekst</label>
            <input type="text" name="contact[form_button]" value="<?= e($content['contact']['form_button'] ?? '') ?>">
          </div>
        </div>
      </div>

      <!-- Save bar -->
      <div class="save-bar">
        <button type="submit" class="btn-save"><span>Opslaan</span></button>
      </div>

    </form>
  </div>

  <script>
    // Sidebar active link
    const sections = document.querySelectorAll('.section-card');
    const links = document.querySelectorAll('.sidebar a');

    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          links.forEach(l => l.classList.remove('active'));
          const link = document.querySelector(`.sidebar a[href="#${entry.target.id}"]`);
          if (link) link.classList.add('active');
        }
      });
    }, { rootMargin: '-80px 0px -60% 0px' });

    sections.forEach(s => observer.observe(s));

    // Add repeater items
    function addRepeaterItem(group) {
      const container = document.getElementById(group + '-items');
      const items = container.querySelectorAll('.repeater-item');
      const idx = items.length;

      let html = '';
      if (group === 'werkwijze') {
        html = `
          <div class="repeater-item" data-group="${group}">
            <button type="button" class="btn-remove" onclick="this.parentElement.remove()">✕</button>
            <div class="item-header">Stap ${idx + 1}</div>
            <div class="field-row full">
              <div class="field">
                <label>Titel</label>
                <input type="text" name="werkwijze[stappen][${idx}][title]" value="">
              </div>
            </div>
            <div class="field">
              <label>Tekst</label>
              <textarea name="werkwijze[stappen][${idx}][text]" rows="2"></textarea>
            </div>
          </div>`;
      } else {
        const label = group === 'voordelen' ? 'Voordeel' : 'Toepassing';
        html = `
          <div class="repeater-item" data-group="${group}">
            <button type="button" class="btn-remove" onclick="this.parentElement.remove()">✕</button>
            <div class="item-header">${label} ${idx + 1}</div>
            <div class="field-row">
              <div class="field">
                <label>Icoon</label>
                <input type="text" name="${group}[items][${idx}][icon]" value="">
              </div>
              <div class="field">
                <label>Titel</label>
                <input type="text" name="${group}[items][${idx}][title]" value="">
              </div>
            </div>
            <div class="field">
              <label>Tekst</label>
              <textarea name="${group}[items][${idx}][text]" rows="2"></textarea>
            </div>
          </div>`;
      }
      container.insertAdjacentHTML('beforeend', html);
    }
  </script>
</body>
</html>
