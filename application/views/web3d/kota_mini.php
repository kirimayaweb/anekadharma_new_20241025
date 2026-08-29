<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <style>
        :root {
            --accent: #ffcc66;
            --panel: rgba(8,14,24,0.88);
            --line: rgba(255,255,255,0.14);
            --hud-top: 110px;
            --hud-bottom: 88px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body {
            width: 100%; height: 100%; overflow: hidden;
            background: radial-gradient(circle at 50% 18%, #1a3050 0%, #060b14 55%, #03060c 100%);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; color: #f4f7fb;
        }
        #canvas-wrap, #css3d-wrap { position: fixed; inset: 0; overflow: hidden; }
        #css3d-wrap { pointer-events: none; z-index: 2; }
        #canvas-wrap { z-index: 1; }
        canvas { display: block; width: 100%; height: 100%; }

        .hud { position: fixed; inset: 0; pointer-events: none; z-index: 20; }
        .brand { position: absolute; top: 14px; left: 16px; max-width: min(480px, calc(100vw - 32px)); }
        .brand h1 { font-size: clamp(1.05rem, 2.2vw, 1.55rem); text-shadow: 0 6px 20px rgba(0,0,0,0.5); }
        .brand p { margin-top: 3px; opacity: 0.88; font-size: 0.82rem; }
        .stats {
            position: absolute; top: 14px; right: 16px; text-align: right;
            font-size: 0.78rem; line-height: 1.45; text-shadow: 0 4px 12px rgba(0,0,0,0.55);
        }
        .stats strong { color: var(--accent); }
        .mode-bar {
            position: absolute; top: 62px; left: 16px; right: 16px;
            display: flex; flex-wrap: wrap; gap: 7px; pointer-events: auto;
        }
        .panel {
            position: absolute; bottom: 14px; left: 14px; right: 14px;
            display: flex; flex-wrap: wrap; gap: 8px; align-items: flex-end; justify-content: space-between;
        }
        .card {
            pointer-events: auto; background: var(--panel); border: 1px solid var(--line);
            backdrop-filter: blur(10px); border-radius: 12px; padding: 10px 12px;
        }
        .card h2 { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.1em; opacity: 0.7; margin-bottom: 6px; }
        .keys { display: flex; flex-wrap: wrap; gap: 5px; max-width: 480px; }
        .key { display: inline-flex; gap: 5px; padding: 4px 8px; border-radius: 999px; background: rgba(255,255,255,0.08); font-size: 0.74rem; }
        .key b { color: var(--accent); }
        .actions { display: flex; gap: 7px; flex-wrap: wrap; align-items: center; }
        button, .btn-open, .mode-link {
            pointer-events: auto; border: 1px solid var(--line);
            background: linear-gradient(180deg, #1c314d, #122338); color: #fff;
            border-radius: 999px; padding: 8px 13px; cursor: pointer; font-weight: 600;
            text-decoration: none; display: inline-flex; align-items: center; font-size: 0.82rem;
        }
        button:hover, .btn-open:hover, .mode-link:hover { filter: brightness(1.12); }
        .btn-open.secondary { background: linear-gradient(135deg, #42a5f5, #1e88e5); border-color: transparent; }
        .mode-link.active { border-color: var(--accent); color: var(--accent); }
        .dots { display: flex; gap: 7px; pointer-events: auto; }
        .dot { width: 9px; height: 9px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.35); background: rgba(255,255,255,0.15); cursor: pointer; }
        .dot.active { background: var(--accent); border-color: var(--accent); transform: scale(1.25); }
        body.drag-nav { cursor: grab; }
        body.drag-nav.dragging { cursor: grabbing; }

        /* Kartu 3D — preview saja, TANPA iframe (tajam & ringan) */
        .module-card3d {
            width: var(--card-w, 380px); height: var(--card-h, 480px);
            pointer-events: auto; position: relative; cursor: pointer;
            transform-style: preserve-3d; backface-visibility: hidden;
        }
        .module-card3d .card-shell {
            position: absolute; inset: 0; border-radius: 16px; overflow: hidden;
            box-shadow: 0 24px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.1);
            display: flex; flex-direction: column;
        }
        .module-card3d .card-top {
            padding: 16px; color: #fff; flex-shrink: 0;
        }
        .module-card3d .card-top .icon { font-size: 2rem; margin-bottom: 6px; }
        .module-card3d .card-top h3 { font-size: 1.15rem; font-weight: 800; }
        .module-card3d .card-top p { font-size: 0.78rem; opacity: 0.9; margin-top: 4px; line-height: 1.4; }
        .module-card3d .card-body {
            flex: 1; padding: 14px; background: rgba(255,255,255,0.95); color: #1f2937;
            display: flex; flex-direction: column; gap: 8px;
        }
        .module-card3d .chip-row { display: flex; flex-wrap: wrap; gap: 5px; }
        .module-card3d .chip {
            font-size: 0.65rem; padding: 4px 8px; border-radius: 999px;
            background: rgba(0,0,0,0.07); font-weight: 700;
        }
        .module-card3d .fake-table { border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; flex: 1; }
        .module-card3d .fake-row {
            display: grid; grid-template-columns: 1fr 1fr; gap: 6px;
            padding: 6px 9px; font-size: 0.65rem; border-bottom: 1px solid #f0f0f0;
        }
        .module-card3d .fake-row.head { background: #f3f4f6; font-weight: 700; }
        .module-card3d .open-btn {
            margin-top: auto; text-align: center; padding: 10px; border-radius: 10px;
            color: #fff; font-weight: 700; font-size: 0.82rem;
        }
        .module-card3d.active-page .card-shell {
            box-shadow: 0 30px 80px rgba(0,0,0,0.6), 0 0 0 3px rgba(255,204,102,0.85), 0 0 30px rgba(255,204,102,0.3);
        }

        /* Panel LIVE — iframe native, responsif, TANPA transform 3D (tajam!) */
        #liveWorkspace {
            position: fixed;
            top: var(--hud-top); left: 12px; right: 12px; bottom: var(--hud-bottom);
            z-index: 15; pointer-events: auto;
            display: flex; flex-direction: column;
            border-radius: 16px; overflow: hidden;
            box-shadow: 0 30px 90px rgba(0,0,0,0.65), 0 0 0 2px rgba(255,204,102,0.7);
            opacity: 0; transform: scale(0.92) translateY(20px);
            transition: opacity 0.35s ease, transform 0.35s ease;
            visibility: hidden;
        }
        #liveWorkspace.show {
            opacity: 1; transform: scale(1) translateY(0); visibility: visible;
        }
        #liveWorkspace .live-topbar {
            flex-shrink: 0; height: 44px; display: flex; align-items: center; gap: 10px;
            padding: 0 14px; color: #fff; font-weight: 700; font-size: 0.9rem;
        }
        #liveWorkspace .live-topbar .live-title { flex: 1; }
        #liveWorkspace .live-topbar button {
            padding: 5px 12px; font-size: 0.78rem; border-radius: 8px;
        }
        #liveWorkspace .live-frame-wrap {
            flex: 1; position: relative; background: #fff; min-height: 0;
        }
        #liveWorkspace iframe {
            position: absolute; inset: 0; width: 100%; height: 100%;
            border: 0; background: #fff;
        }
        #liveWorkspace .live-loading {
            position: absolute; inset: 0; display: grid; place-items: center;
            background: #f4f6f9; color: #374151; font-size: 0.95rem; z-index: 2;
        }
        #liveWorkspace .live-loading.hidden { display: none; }

        .overlay-start {
            position: fixed; inset: 0; z-index: 30; display: grid; place-items: center;
            background: rgba(5,11,20,0.85); backdrop-filter: blur(5px);
            cursor: pointer; transition: opacity 0.35s ease;
        }
        .overlay-start.hide { opacity: 0; pointer-events: none; }
        .start-box { text-align: center; max-width: 580px; padding: 24px; }
        .start-box h1 { font-size: clamp(1.7rem, 4vw, 2.4rem); margin-bottom: 10px; }
        .start-box p { opacity: 0.9; margin-bottom: 18px; line-height: 1.5; }
        .start-cta {
            display: inline-block; padding: 12px 24px; border-radius: 999px;
            background: linear-gradient(135deg, #ffcc66, #ff8a4c); color: #1a1208; font-weight: 800;
        }
        body.live-open #css3d-wrap { opacity: 0.15; }
        body.live-open.drag-nav { cursor: default; }
        @media (max-width: 720px) {
            :root { --hud-top: 90px; --hud-bottom: 120px; }
            .stats, .mode-bar { display: none; }
        }
    </style>
</head>
<body class="drag-nav">
    <div id="canvas-wrap"></div>
    <div id="css3d-wrap"></div>

    <div id="liveWorkspace">
        <div class="live-topbar" id="liveTopbar">
            <span class="live-title" id="liveTitle">Modul</span>
            <button type="button" id="btnLivePrev">← Modul</button>
            <button type="button" id="btnLiveNext">Modul →</button>
            <button type="button" id="btnLiveClose">✕ Tutup</button>
        </div>
        <div class="live-frame-wrap">
            <div class="live-loading" id="liveLoading">Memuat halaman…</div>
            <iframe id="liveIframe" title="Modul ERP Live"></iframe>
        </div>
    </div>

    <div class="overlay-start" id="startOverlay">
        <div class="start-box">
            <h1><?php echo htmlspecialchars($brand_name, ENT_QUOTES, 'UTF-8'); ?> 3D ERP</h1>
            <p><strong>Klik + tahan + geser</strong> untuk putar modul. <strong>Klik kartu</strong> untuk buka halaman live tajam &amp; responsif.
                Tekan <strong>Esc</strong> tutup halaman live.</p>
            <div class="start-cta">Mulai</div>
        </div>
    </div>

    <div class="hud">
        <div class="brand">
            <h1><?php echo htmlspecialchars($brand_name, ENT_QUOTES, 'UTF-8'); ?> — ERP 3D</h1>
            <p>Navigasi 3D + halaman live responsif (tanpa blur/pecah).</p>
        </div>
        <div class="mode-bar">
            <?php foreach ($mode_urls as $key => $url): ?>
                <a class="mode-link<?php echo ($view_mode === $key) ? ' active' : ''; ?>"
                   href="<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars(ucfirst($key), ENT_QUOTES, 'UTF-8'); ?></a>
            <?php endforeach; ?>
        </div>
        <div class="stats">
            <div>Model: <strong id="modeLabel"><?php echo htmlspecialchars($mode_labels[$view_mode], ENT_QUOTES, 'UTF-8'); ?></strong></div>
            <div>Status: <strong id="statusLabel">Navigasi 3D</strong></div>
            <div>Modul: <strong id="moduleLabel">—</strong></div>
        </div>
        <div class="panel">
            <div class="card">
                <h2>Kontrol</h2>
                <div class="keys">
                    <span class="key"><b>Drag</b> ikuti arah mouse</span>
                    <span class="key"><b>Klik kartu</b> buka live</span>
                    <span class="key"><b>Esc</b> tutup live</span>
                    <span class="key"><b>Scroll</b> pindah modul</span>
                </div>
            </div>
            <div class="actions">
                <div class="dots" id="dots"></div>
                <button type="button" id="btnPrev">← Modul</button>
                <button type="button" id="btnNext">Modul →</button>
                <a class="btn-open secondary" id="btnOpen" href="#" target="_blank" rel="noopener">Tab Baru</a>
            </div>
        </div>
    </div>

    <script type="importmap">
    { "imports": {
        "three": "https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js",
        "three/addons/": "https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"
    }}
    </script>
    <script type="module">
        import * as THREE from 'three';
        import { CSS3DRenderer, CSS3DObject } from 'three/addons/renderers/CSS3DRenderer.js';

        const VIEW_MODE = <?php echo json_encode($view_mode); ?>;
        const START_INDEX = <?php echo (int) $start_index; ?>;
        const MODULES = <?php echo json_encode($modules, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        const TOTAL = MODULES.length;
        const ANGLE_STEP = (Math.PI * 2) / TOTAL;

        let CSS_RADIUS = 620;
        let CARD_W = 380;
        let CARD_H = 480;
        let CAM_Z = 900;
        let DRAG_SENS = 0.005;

        const bodyEl = document.body;
        const liveWorkspace = document.getElementById('liveWorkspace');
        const liveIframe = document.getElementById('liveIframe');
        const liveLoading = document.getElementById('liveLoading');
        const liveTitle = document.getElementById('liveTitle');
        const liveTopbar = document.getElementById('liveTopbar');
        const startOverlay = document.getElementById('startOverlay');
        const statusLabel = document.getElementById('statusLabel');
        const moduleLabel = document.getElementById('moduleLabel');
        const btnOpen = document.getElementById('btnOpen');
        const dotsEl = document.getElementById('dots');

        let currentIndex = START_INDEX;
        let targetIndex = START_INDEX;
        let liveOpen = false;
        let liveIndex = null;
        let animProgress = 1;
        let animFrom = START_INDEX;
        let animTo = START_INDEX;
        let animDir = 0;
        let currentAngle = -START_INDEX * ANGLE_STEP;
        let targetAngle = currentAngle;
        let dragging = false;
        let dragStartX = 0;
        let dragStartAngle = 0;
        let velocity = 0;
        let lastDragX = 0;
        let lastDragTime = 0;

        const scene = new THREE.Scene();
        scene.fog = new THREE.FogExp2(0x060b14, 0.00003);

        const camera = new THREE.PerspectiveCamera(42, window.innerWidth / window.innerHeight, 1, 5000);
        camera.position.set(0, 30, CAM_Z);
        camera.lookAt(0, 0, 0);

        const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'high-performance' });
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5));
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.getElementById('canvas-wrap').appendChild(renderer.domElement);

        const cssRenderer = new CSS3DRenderer();
        cssRenderer.setSize(window.innerWidth, window.innerHeight);
        cssRenderer.domElement.style.cssText = 'position:absolute;top:0;left:0;pointer-events:none;';
        document.getElementById('css3d-wrap').appendChild(cssRenderer.domElement);

        const root = new THREE.Group();
        scene.add(root);

        scene.add(new THREE.HemisphereLight(0x9ecfff, 0x1a2438, 0.7));
        const floor = new THREE.Mesh(
            new THREE.CircleGeometry(900, 48),
            new THREE.MeshStandardMaterial({ color: 0x0d1624, roughness: 0.95 })
        );
        floor.rotation.x = -Math.PI / 2;
        floor.position.y = -280;
        scene.add(floor);

        let ring = null;
        function buildRing() {
            if (ring) scene.remove(ring);
            ring = new THREE.Mesh(
                new THREE.RingGeometry(CSS_RADIUS - 18, CSS_RADIUS + 18, 48),
                new THREE.MeshBasicMaterial({ color: 0x3a6aaa, transparent: true, opacity: 0.35, side: THREE.DoubleSide })
            );
            ring.rotation.x = -Math.PI / 2;
            ring.position.y = -275;
            scene.add(ring);
        }
        buildRing();

        const pageObjects = [];

        function updateViewport() {
            const vw = window.innerWidth;
            const vh = window.innerHeight;
            CARD_W = Math.min(400, Math.max(280, vw * 0.28));
            CARD_H = Math.min(520, Math.max(360, vh * 0.52));
            CSS_RADIUS = Math.min(680, Math.max(420, vw * 0.38));
            CAM_Z = Math.min(1100, Math.max(750, vw * 0.95));
            DRAG_SENS = 0.0045;
            document.documentElement.style.setProperty('--card-w', CARD_W + 'px');
            document.documentElement.style.setProperty('--card-h', CARD_H + 'px');
            camera.aspect = vw / vh;
            camera.updateProjectionMatrix();
            renderer.setSize(vw, vh);
            cssRenderer.setSize(vw, vh);
            buildRing();
        }

        function fakeRows(module) {
            return module.items.slice(0, 3).map((it, i) =>
                `<div class="fake-row"><span>${it}</span><span>Aktif</span></div>`
            ).join('');
        }

        function createCard(module, index) {
            const el = document.createElement('div');
            el.className = 'module-card3d';
            el.innerHTML = `
                <div class="card-shell">
                    <div class="card-top" style="background:linear-gradient(135deg,${module.color},${module.accent})">
                        <div class="icon">${module.icon}</div>
                        <h3>${module.title}</h3>
                        <p>${module.subtitle}</p>
                    </div>
                    <div class="card-body">
                        <p style="font-size:0.76rem;line-height:1.4;color:#4b5563">${module.description}</p>
                        <div class="chip-row">${module.items.map(it => `<span class="chip">${it}</span>`).join('')}</div>
                        <div class="fake-table">
                            <div class="fake-row head"><span>Menu</span><span>Status</span></div>
                            ${fakeRows(module)}
                        </div>
                        <div class="open-btn" style="background:linear-gradient(135deg,${module.color},${module.accent})">
                            ▶ Buka ${module.title}
                        </div>
                    </div>
                </div>`;
            el.addEventListener('mousedown', (e) => e.stopPropagation());
            el.addEventListener('click', (e) => { e.stopPropagation(); openLive(index); });
            return el;
        }

        MODULES.forEach((module, i) => {
            const el = createCard(module, i);
            const obj = new CSS3DObject(el);
            const a = i * ANGLE_STEP;
            obj.position.set(Math.sin(a) * CSS_RADIUS, 0, Math.cos(a) * CSS_RADIUS);
            obj.rotation.y = a + Math.PI;
            root.add(obj);
            pageObjects.push({ obj, el, module, baseAngle: a });
        });

        MODULES.forEach((m, i) => {
            const d = document.createElement('button');
            d.type = 'button';
            d.className = 'dot' + (i === START_INDEX ? ' active' : '');
            d.title = m.title;
            d.addEventListener('click', () => liveOpen ? openLive(i) : goTo(i));
            dotsEl.appendChild(d);
        });
        const dotButtons = dotsEl.querySelectorAll('.dot');

        const loadedUrls = {};

        function openLive(index) {
            index = ((index % TOTAL) + TOTAL) % TOTAL;
            liveIndex = index;
            liveOpen = true;
            currentIndex = index;
            targetIndex = index;
            targetAngle = -index * ANGLE_STEP;
            currentAngle = targetAngle;
            const mod = MODULES[index];
            liveTitle.textContent = mod.title + ' — Live';
            liveTopbar.style.background = `linear-gradient(135deg,${mod.color},${mod.accent})`;
            liveLoading.classList.remove('hidden');
            liveWorkspace.classList.add('show');
            bodyEl.classList.add('live-open');
            statusLabel.textContent = 'Live — ' + mod.title;
            if (loadedUrls[mod.url]) {
                liveIframe.src = mod.url;
                liveLoading.classList.add('hidden');
            } else {
                liveIframe.onload = () => { liveLoading.classList.add('hidden'); loadedUrls[mod.url] = true; };
                liveIframe.src = mod.url;
            }
            updateHud();
        }

        function closeLive() {
            liveOpen = false;
            liveIndex = null;
            liveWorkspace.classList.remove('show');
            bodyEl.classList.remove('live-open');
            statusLabel.textContent = 'Navigasi 3D';
            updateHud();
        }

        function normalizeIndex(i) { return ((i % TOTAL) + TOTAL) % TOTAL; }
        function shortestDir(from, to) {
            let d = to - from;
            if (d > TOTAL / 2) d -= TOTAL;
            if (d < -TOTAL / 2) d += TOTAL;
            return d;
        }
        function easeOutCubic(t) { return 1 - Math.pow(1 - t, 3); }

        function goTo(index) {
            if (liveOpen) { openLive(index); return; }
            const next = normalizeIndex(index);
            if (next === currentIndex && animProgress >= 1) return;
            animFrom = currentIndex;
            animTo = next;
            animDir = Math.sign(shortestDir(currentIndex, next)) || 1;
            animProgress = 0;
            targetIndex = next;
            if (VIEW_MODE === 'carousel') {
                targetAngle = -next * ANGLE_STEP;
                currentIndex = next;
            } else {
                currentIndex = next;
            }
            updateHud();
        }

        function updateHud() {
            const idx = liveOpen ? liveIndex : (animProgress < 1 ? targetIndex : currentIndex);
            const mod = MODULES[idx];
            moduleLabel.textContent = mod.title;
            btnOpen.href = mod.url;
            dotButtons.forEach((d, i) => d.classList.toggle('active', i === idx));
            pageObjects.forEach(({ el }, i) => el.classList.toggle('active-page', i === idx && !liveOpen));
        }

        function applyCarouselLayout(angle) {
            root.rotation.y = angle;
            pageObjects.forEach(({ obj, el, baseAngle }, i) => {
                const a = baseAngle;
                obj.position.set(Math.sin(a) * CSS_RADIUS, 0, Math.cos(a) * CSS_RADIUS);
                obj.rotation.y = a + Math.PI;
                obj.rotation.x = 0;
                const dist = Math.abs(((i * ANGLE_STEP + angle) % (Math.PI * 2) + Math.PI * 2) % (Math.PI * 2) - Math.PI);
                const focus = Math.max(0, 1 - dist / (Math.PI * 0.7));
                obj.scale.setScalar(0.75 + focus * 0.25);
                obj.position.y = -30 + focus * 25;
                el.style.opacity = liveOpen ? '0.2' : String(0.4 + focus * 0.6);
            });
        }

        function applyFlipLayout(t, from, to, dir) {
            root.rotation.set(0, 0, 0);
            const e = easeOutCubic(t);
            pageObjects.forEach(({ obj, el }, i) => {
                obj.rotation.x = 0;
                if (i === from && t < 1) {
                    obj.position.set(-CARD_W * 0.55, 0, 0);
                    obj.rotation.y = -e * Math.PI * dir;
                    obj.scale.setScalar(1);
                } else if (i === to) {
                    obj.position.set(0, 0, t < 1 ? 30 * (1 - e) : 0);
                    obj.rotation.y = t < 1 ? (1 - e) * Math.PI * dir : 0;
                    obj.scale.setScalar(0.88 + (t < 1 ? e * 0.12 : 0.12));
                    el.style.opacity = '1';
                    if (t >= 1 && !liveOpen) setTimeout(() => openLive(to), 200);
                } else {
                    obj.position.set(0, 0, -80 * Math.abs(i - to));
                    obj.rotation.y = 0;
                    obj.scale.setScalar(0.65);
                    el.style.opacity = '0.15';
                }
            });
        }

        function applyCoverflowLayout(active) {
            root.rotation.set(0, 0, 0);
            pageObjects.forEach(({ obj, el }, i) => {
                const offset = shortestDir(active, i);
                const abs = Math.abs(offset);
                obj.position.set(offset * (CARD_W * 0.75), -abs * 12, -abs * 100);
                obj.rotation.set(0, -offset * 0.55, 0);
                obj.scale.setScalar(Math.max(0.6, 1 - abs * 0.12));
                el.style.opacity = String(Math.max(0.2, 1 - abs * 0.15));
            });
        }

        function applyStackLayout(active, dragPx) {
            root.rotation.set(0, 0, 0);
            pageObjects.forEach(({ obj, el }, i) => {
                const rel = i - active;
                if (rel < 0) { el.style.visibility = 'hidden'; return; }
                el.style.visibility = 'visible';
                const sp = rel + dragPx * 0.0008;
                obj.position.set(sp * 14 + dragPx * 0.15, sp * 6, -sp * 45);
                obj.rotation.set(0, sp * 0.035, 0);
                obj.scale.setScalar(Math.max(0.65, 1 - sp * 0.06));
                el.style.opacity = String(Math.max(0.3, 1 - sp * 0.08));
            });
        }

        /* Drag: geser mouse kanan → carousel ikut kanan (natural) */
        function onPointerDown(x, target) {
            if (liveOpen || target.closest('.live-frame-wrap, iframe, .actions, .card, .module-card3d, .overlay-start, .mode-bar, #liveWorkspace')) return;
            dragging = true;
            dragStartX = x;
            dragStartAngle = currentAngle;
            velocity = 0;
            lastDragX = x;
            lastDragTime = performance.now();
            bodyEl.classList.add('dragging');
        }
        function onPointerMove(x) {
            if (!dragging || liveOpen) return;
            const now = performance.now();
            velocity = (x - lastDragX) / Math.max(now - lastDragTime, 1);
            lastDragX = x;
            lastDragTime = now;
            const dx = x - dragStartX;
            if (VIEW_MODE === 'carousel') {
                currentAngle = dragStartAngle - dx * DRAG_SENS;
                targetAngle = currentAngle;
            }
        }
        function onPointerUp() {
            if (!dragging) return;
            dragging = false;
            bodyEl.classList.remove('dragging');
            if (liveOpen) return;
            const dx = lastDragX - dragStartX;
            if (VIEW_MODE === 'carousel') {
                currentAngle -= velocity * 80 * DRAG_SENS;
                currentIndex = normalizeIndex(Math.round(-currentAngle / ANGLE_STEP));
                targetAngle = -currentIndex * ANGLE_STEP;
            } else if (Math.abs(dx) > 60 || Math.abs(velocity) > 0.4) {
                goTo(currentIndex + (dx < 0 ? 1 : -1));
            }
            updateHud();
        }

        window.addEventListener('mousedown', (e) => { if (e.button === 0 && startOverlay.classList.contains('hide')) onPointerDown(e.clientX, e.target); });
        window.addEventListener('mousemove', (e) => onPointerMove(e.clientX));
        window.addEventListener('mouseup', onPointerUp);
        window.addEventListener('mouseleave', onPointerUp);
        window.addEventListener('touchstart', (e) => {
            if (!startOverlay.classList.contains('hide')) return;
            onPointerDown(e.touches[0].clientX, e.target);
        }, { passive: true });
        window.addEventListener('touchmove', (e) => onPointerMove(e.touches[0].clientX), { passive: true });
        window.addEventListener('touchend', onPointerUp);

        window.addEventListener('wheel', (e) => {
            if (!startOverlay.classList.contains('hide') || liveOpen) return;
            const delta = Math.abs(e.deltaX) >= Math.abs(e.deltaY) ? e.deltaX : e.deltaY;
            if (Math.abs(delta) < 2) return;
            goTo(currentIndex + (delta > 0 ? 1 : -1));
            e.preventDefault();
        }, { passive: false });

        document.getElementById('btnPrev').addEventListener('click', () => goTo(currentIndex - 1));
        document.getElementById('btnNext').addEventListener('click', () => goTo(currentIndex + 1));
        document.getElementById('btnLivePrev').addEventListener('click', () => openLive(liveIndex - 1));
        document.getElementById('btnLiveNext').addEventListener('click', () => openLive(liveIndex + 1));
        document.getElementById('btnLiveClose').addEventListener('click', closeLive);

        document.addEventListener('keydown', (e) => {
            if (!startOverlay.classList.contains('hide')) return;
            if (e.key === 'Escape') closeLive();
            if (liveOpen) {
                if (e.key === 'ArrowLeft') openLive(liveIndex - 1);
                if (e.key === 'ArrowRight') openLive(liveIndex + 1);
            } else {
                if (e.key === 'ArrowLeft') goTo(currentIndex - 1);
                if (e.key === 'ArrowRight') goTo(currentIndex + 1);
                if (e.key === 'Enter') openLive(currentIndex);
            }
        });
        startOverlay.addEventListener('click', () => startOverlay.classList.add('hide'));
        window.addEventListener('resize', updateViewport);

        const clock = new THREE.Clock();

        function animate() {
            requestAnimationFrame(animate);
            const dt = Math.min(clock.getDelta(), 0.05);

            if (!liveOpen) {
                if (VIEW_MODE === 'carousel') {
                    if (!dragging) {
                        currentAngle += (targetAngle - currentAngle) * (1 - Math.pow(0.001, dt));
                        currentIndex = normalizeIndex(Math.round(-currentAngle / ANGLE_STEP));
                    }
                    applyCarouselLayout(currentAngle);
                } else {
                    if (animProgress < 1) {
                        animProgress = Math.min(1, animProgress + dt * 2.5);
                        if (animProgress >= 1) currentIndex = targetIndex;
                    }
                    if (VIEW_MODE === 'flip') applyFlipLayout(animProgress, animFrom, animTo, animDir);
                    else if (VIEW_MODE === 'coverflow') applyCoverflowLayout(currentIndex);
                    else if (VIEW_MODE === 'stack') applyStackLayout(currentIndex, dragging ? lastDragX - dragStartX : 0);
                }
            } else {
                applyCarouselLayout(-liveIndex * ANGLE_STEP);
            }

            camera.position.z += (CAM_Z - camera.position.z) * (1 - Math.pow(0.001, dt));
            camera.lookAt(0, 0, 0);

            renderer.render(scene, camera);
            cssRenderer.render(scene, camera);
        }

        updateViewport();
        currentIndex = START_INDEX;
        currentAngle = -START_INDEX * ANGLE_STEP;
        targetAngle = currentAngle;
        applyCarouselLayout(currentAngle);
        updateHud();
        animate();
    </script>
</body>
</html>
