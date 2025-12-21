<?php
require_once __DIR__ . '/../../BD/conexion.php';
require_once __DIR__ . '/../../TEMPLATE/header.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['idUsuario'])) {
    header('Location: ' . BASE_URL . '/AUTH/login.php');
    exit;
}

$idUsuario = (int) $_SESSION['idUsuario'];

// Obtener info de usuario (incluye nombre del rol)
$stmt = $conexion->prepare("SELECT u.idUsuario, u.Correo, r.Descripcion AS rol FROM usuario u LEFT JOIN rol r ON u.idRol = r.idRol WHERE u.idUsuario = :id");
$stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener suscripción actual
$stmt = $conexion->prepare("SELECT s.idSuscripcion, p.idPlan, p.Nombre, p.MaxSesiones, p.Precio, s.FechaInicio, s.FechaFin FROM suscripcion s JOIN plan p ON s.idPlan = p.idPlan WHERE s.idUsuario = :id AND s.Estado = 1 ORDER BY s.FechaInicio DESC LIMIT 1");
$stmt->bindParam(':id', $idUsuario, PDO::PARAM_INT);
$stmt->execute();
$sus = $stmt->fetch(PDO::FETCH_ASSOC);

// Cargar todos los planes activos
$planesStmt = $conexion->query("SELECT idPlan, Nombre, MaxSesiones, Precio FROM plan WHERE Estado = 1 ORDER BY idPlan");
$planes = $planesStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Perfil</span>
      <small class="text-muted">Usuario: <?= htmlspecialchars($usuario['Correo']) ?></small>
    </div>
    <div class="card-body">
      <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
      <?php endif; ?>
      <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>

      <h5>Información</h5>
      <ul>
        <li><strong>Correo:</strong> <?= htmlspecialchars($usuario['Correo']) ?></li>
        <li><strong>Rol:</strong> <?= htmlspecialchars($usuario['Rol'] ?? 'Usuario') ?></li>
        <li><strong>Suscripción actual:</strong> <?= htmlspecialchars($sus['Nombre'] ?? 'Sin suscripción') ?></li>
      </ul>

      <hr>

      <h5>Suscripción</h5>
      <p>Arrastra para ver los planes (se muestra parcialmente el anterior/siguiente ~30%).</p>
      <button class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#suscripcionArea" aria-expanded="false">Ver planes</button>

      <div class="collapse mt-3" id="suscripcionArea">
        <!-- Carrusel de planes arrastrable (muestra multiples) -->
        <div class="plan-viewport mt-3" id="planViewport">
          <div class="plan-track" id="planTrack">
            <?php foreach ($planes as $index => $planItem): ?>
              <div class="plan-card" data-index="<?= $index ?>" data-idplan="<?= (int)$planItem['idPlan'] ?>">
                <div class="card h-100 plan-inner">
                  <div class="card-body">
                    <h4 class="card-title"><?= htmlspecialchars($planItem['Nombre']) ?></h4>
                    <p class="card-text">Precio: <?= htmlspecialchars($planItem['Precio']) ?> — Max sesiones: <?= htmlspecialchars($planItem['MaxSesiones']) ?></p>

                    <!-- AQUI PUEDE AGREGAR LA DESCRIPCION DEL PLAN EN HTML O TEXT -->
                    <!-- EJEMPLO: <p>Descripción del plan ...</p> -->

                    <form method="POST" action="cambiar_plan.php" class="mt-3">
                      <input type="hidden" name="idPlan" value="<?= (int)$planItem['idPlan'] ?>">
                      <button class="btn btn-primary" type="submit">Seleccionar plan</button>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="d-flex justify-content-between mt-2">
          <button id="prevBtn" class="btn btn-outline-secondary">&larr;</button>
          <div class="text-center align-self-center"><small class="text-muted">Arrastra para navegar</small></div>
          <button id="nextBtn" class="btn btn-outline-secondary">&rarr;</button>
        </div>

        <style>
          .plan-viewport { overflow: hidden; width: 100%; max-width: 1000px; margin: 0 auto; }
          .plan-track { display: flex; gap: 20px; transition: transform 400ms cubic-bezier(.22,.98,.32,1); -webkit-user-select: none; user-select: none; cursor: grab; }
          .plan-track.grabbing { cursor: grabbing; }
          .plan-card { flex: 0 0 30%; max-width: 30%; }
          .plan-card .card { height: 100%; transition: transform 350ms ease, box-shadow 350ms ease, opacity 350ms ease; border-radius: 10px; overflow: hidden; }
          .plan-card .card img { width:100%; height:150px; object-fit:cover; }
          .plan-card.active .card { transform: scale(1.06); box-shadow: 0 18px 40px rgba(0,0,0,0.25); }
          .plan-card.dimmed .card { transform: scale(0.98); opacity: 0.9; }
          .plan-card .card-body { min-height: 160px; }
          @media (max-width: 768px) {
            .plan-card { flex-basis: 80%; max-width: 80%; }
          }
        </style>

        <script>
        (function(){
          const track = document.getElementById('planTrack');
          const viewport = document.getElementById('planViewport');
          const cards = Array.from(document.querySelectorAll('.plan-card'));
          const prevBtn = document.getElementById('prevBtn');
          const nextBtn = document.getElementById('nextBtn');

          let currentIndex = 0;
          const currentPlanId = <?= (int)($sus['idPlan'] ?? 0) ?>;
          if (currentPlanId) {
            const found = cards.find(c => parseInt(c.dataset.idplan) === currentPlanId);
            if (found) currentIndex = parseInt(found.dataset.index);
          }

          let containerWidth, cardWidth, gap = 20;

          function resize() {
            containerWidth = viewport.clientWidth;
            // for desktop show 3 cards (30% each), for mobile show 1 large card
            if (window.innerWidth <= 768) {
              cardWidth = Math.round(containerWidth * 0.8);
            } else {
              cardWidth = Math.round(containerWidth * 0.30) - Math.round(gap * 0.66);
            }
            cards.forEach(c => { c.style.width = cardWidth + 'px'; });
            updateActiveClasses();
            goToIndex(currentIndex, false);
          }

          function getCardCenterOffset(index) {
            const offset = index * (cardWidth + gap);
            const centerOffset = (containerWidth - cardWidth) / 2;
            return -offset + centerOffset;
          }

          function goToIndex(index, smooth = true) {
            index = Math.max(0, Math.min(cards.length - 1, index));
            currentIndex = index;
            const translateX = getCardCenterOffset(index);
            track.style.transition = smooth ? 'transform 400ms cubic-bezier(.22,.98,.32,1)' : 'none';
            track.style.transform = `translateX(${translateX}px)`;
            updateActiveClasses();
          }

          function updateActiveClasses() {
            cards.forEach((c,i) => {
              c.classList.remove('active'); c.classList.remove('dimmed');
              if (i === currentIndex) c.classList.add('active');
              else if (i === currentIndex -1 || i === currentIndex +1) c.classList.add('dimmed');
            });
          }

          // Drag handlers
          let isDown=false, startX=0, prevTranslate=0;

          function pointerDown(e) {
            isDown = true;
            startX = (e.touches ? e.touches[0].clientX : e.clientX);
            prevTranslate = getTranslateX();
            track.classList.add('grabbing');
          }
          function pointerMove(e) {
            if (!isDown) return;
            const x = (e.touches ? e.touches[0].clientX : e.clientX);
            const dx = x - startX;
            track.style.transition = 'none';
            track.style.transform = `translateX(${prevTranslate + dx}px)`;
          }
          function pointerUp(e) {
            if (!isDown) return;
            isDown = false;
            track.classList.remove('grabbing');
            const endX = (e.changedTouches ? e.changedTouches[0].clientX : e.clientX);
            const moved = endX - startX;
            const threshold = Math.max(50, cardWidth * 0.12);
            if (moved < -threshold && currentIndex < cards.length -1) {
              goToIndex(currentIndex + 1);
            } else if (moved > threshold && currentIndex > 0) {
              goToIndex(currentIndex - 1);
            } else {
              // snap to nearest based on current translate
              const translate = Math.abs(getTranslateX());
              const nearIndex = Math.round( translate / (cardWidth + gap) );
              goToIndex(nearIndex);
            }
          }

          function getTranslateX() {
            const st = window.getComputedStyle(track);
            const tr = st.transform || st.webkitTransform;
            if (tr && tr !== 'none') {
              const match = tr.match(/matrix\((.+)\)/);
              if (match) {
                const values = match[1].split(', ');
                return parseFloat(values[4]);
              }
            }
            return 0;
          }

          // events
          track.addEventListener('mousedown', pointerDown);
          track.addEventListener('touchstart', pointerDown, {passive:true});
          window.addEventListener('mousemove', pointerMove);
          window.addEventListener('touchmove', pointerMove, {passive:true});
          window.addEventListener('mouseup', pointerUp);
          window.addEventListener('touchend', pointerUp);

          prevBtn.addEventListener('click', e=> goToIndex(currentIndex -1));
          nextBtn.addEventListener('click', e=> goToIndex(currentIndex +1));

          window.addEventListener('resize', resize);
          resize();
        })();
        </script>
  </div>
</div>

<?php require_once __DIR__ . '/../../TEMPLATE/footer.php'; ?>