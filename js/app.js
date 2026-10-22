// Interacciones del sitio CMC: menu, animaciones, contadores, slides del hero, carruseles y formularios.
(function () {
  'use strict';

  // Menu solido al hacer scroll
  var nav = document.getElementById('nav');
  if (nav) {
    var alScroll = function () { nav.classList.toggle('solida', window.scrollY > 40); };
    alScroll();
    window.addEventListener('scroll', alScroll, { passive: true });
    // Menu movil (sin el JS de Bootstrap)
    var boton = nav.querySelector('.navbar-toggler');
    var menu = document.getElementById('menu');
    var abrirMenu = function (abrir) {
      menu.classList.toggle('show', abrir);
      boton.setAttribute('aria-expanded', abrir);
    };
    if (boton && menu) {
      boton.addEventListener('click', function () { abrirMenu(!menu.classList.contains('show')); });
      // Cerrar al elegir una opcion
      nav.querySelectorAll('.nav-link:not(.dropdown-toggle), .dropdown-item, .btn').forEach(function (a) {
        a.addEventListener('click', function () { abrirMenu(false); });
      });
    }

    // Desplegables del menu
    var cerrarDesplegables = function (excepto) {
      nav.querySelectorAll('.dropdown-toggle').forEach(function (t) {
        if (t === excepto) return;
        t.setAttribute('aria-expanded', 'false');
        t.nextElementSibling.classList.remove('show');
      });
    };
    nav.querySelectorAll('.dropdown-toggle').forEach(function (t) {
      t.addEventListener('click', function (ev) {
        ev.preventDefault();
        var abierto = t.getAttribute('aria-expanded') === 'true';
        cerrarDesplegables(t);
        t.setAttribute('aria-expanded', !abierto);
        t.nextElementSibling.classList.toggle('show', !abierto);
      });
    });
    document.addEventListener('click', function (ev) { if (!ev.target.closest('.dropdown')) cerrarDesplegables(); });
    document.addEventListener('keydown', function (ev) { if (ev.key === 'Escape') cerrarDesplegables(); });
  }

  // Aparecer al hacer scroll + contadores (sin IntersectionObserver no se oculta nada)
  if ('IntersectionObserver' in window) document.documentElement.classList.add('rv');
  var animarContador = function (el) {
    var fin = parseInt(el.getAttribute('data-hasta'), 10) || 0, ini = null, dur = 1600;
    var paso = function (t) {
      if (!ini) ini = t;
      var p = Math.min((t - ini) / dur, 1);
      el.textContent = Math.round(fin * (1 - Math.pow(1 - p, 3))).toLocaleString('es-MX');
      if (p < 1) requestAnimationFrame(paso);
    };
    requestAnimationFrame(paso);
  };
  var elementos = document.querySelectorAll('.revelar, [data-hasta]');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entradas) {
      entradas.forEach(function (en) {
        if (!en.isIntersecting) return;
        if (en.target.hasAttribute('data-hasta')) animarContador(en.target);
        en.target.classList.add('visible');
        io.unobserve(en.target);
      });
    }, { threshold: 0.15 });
    elementos.forEach(function (el) { io.observe(el); });
  } else {
    elementos.forEach(function (el) { el.classList.add('visible'); });
  }

  // Slides del hero
  var slides = document.querySelectorAll('.hero-slide');
  var puntos = document.querySelector('.hero-puntos');
  if (slides.length > 1 && puntos) {
    var actual = 0, timer;
    var ir = function (i) {
      slides[actual].classList.remove('activo');
      puntos.children[actual].classList.remove('activo');
      actual = (i + slides.length) % slides.length;
      slides[actual].classList.add('activo');
      puntos.children[actual].classList.add('activo');
    };
    var iniciar = function () { clearTimeout(timer); clearInterval(timer); timer = setInterval(function () { ir(actual + 1); }, 6000); };
    slides.forEach(function (_, i) {
      var b = document.createElement('button');
      b.type = 'button';
      b.setAttribute('aria-label', 'Ver mensaje ' + (i + 1));
      if (i === 0) b.className = 'activo';
      b.addEventListener('click', function () { ir(i); iniciar(); });
      puntos.appendChild(b);
    });
    // El primer mensaje se queda mas tiempo: es el contenido principal mientras carga la pagina
    timer = setTimeout(function () { ir(1); iniciar(); }, 10000);
  }

  // Carruseles: la pista se desliza con scroll-snap; flechas, puntos y avance automatico
  var sinMovimiento = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  document.querySelectorAll('[data-carrusel]').forEach(function (car) {
    var pista = car.querySelector('.carrusel-pista');
    var puntosCar = car.querySelector('.carrusel-puntos');
    var visibles = function () {
      return Array.prototype.filter.call(pista.children, function (it) { return !it.hidden; });
    };
    var paso = function () {
      var it = visibles()[0];
      return it ? it.getBoundingClientRect().width + parseFloat(getComputedStyle(pista).columnGap || 0) : pista.clientWidth;
    };
    var paginas = function () { return Math.max(1, Math.ceil((pista.scrollWidth - pista.clientWidth) / paso() - 0.01) + 1); };
    var pagina = function () { return Math.round(pista.scrollLeft / paso()); };
    var ir = function (i) {
      var total = paginas();
      if (i >= total) i = 0;
      if (i < 0) i = total - 1;
      pista.scrollTo({ left: i * paso(), behavior: sinMovimiento ? 'auto' : 'smooth' });
    };
    var pintarPuntos = function () {
      puntosCar.innerHTML = '';
      var total = paginas();
      car.classList.toggle('sin-controles', total < 2);
      // Con muchas paginas, un contador "3 / 19" en lugar de puntos
      if (total > 10) {
        puntosCar.appendChild(document.createElement('span')).className = 'carrusel-contador';
        puntosCar.total = total;
        marcar();
        return;
      }
      puntosCar.total = 0;
      for (var i = 0; i < total; i++) {
        var b = document.createElement('button');
        b.type = 'button';
        b.setAttribute('aria-label', 'Ir a ' + (i + 1));
        b.addEventListener('click', ir.bind(null, i));
        puntosCar.appendChild(b);
      }
      marcar();
    };
    var marcar = function () {
      var p = pagina();
      if (puntosCar.total) { puntosCar.firstChild.textContent = Math.min(p + 1, puntosCar.total) + ' / ' + puntosCar.total; return; }
      Array.prototype.forEach.call(puntosCar.children, function (b, i) { b.classList.toggle('activo', i === p); });
    };
    car.querySelectorAll('.carrusel-btn').forEach(function (b) {
      b.addEventListener('click', function () { ir(pagina() + parseInt(b.getAttribute('data-dir'), 10)); });
    });
    var espera;
    pista.addEventListener('scroll', function () { clearTimeout(espera); espera = setTimeout(marcar, 80); }, { passive: true });
    window.addEventListener('resize', function () { clearTimeout(espera); espera = setTimeout(pintarPuntos, 150); });
    car.refrescar = function () { pista.scrollLeft = 0; pintarPuntos(); };
    pintarPuntos();

    // Avance automatico: se pausa al pasar el mouse, al tocar o con el foco dentro
    var ms = parseInt(car.getAttribute('data-auto'), 10);
    if (ms && !sinMovimiento) {
      var pausa = false, reloj = setInterval(function () { if (!pausa && !document.hidden) ir(pagina() + 1); }, ms);
      ['mouseenter', 'focusin', 'touchstart'].forEach(function (ev) { car.addEventListener(ev, function () { pausa = true; }, { passive: true }); });
      ['mouseleave', 'focusout'].forEach(function (ev) { car.addEventListener(ev, function () { pausa = false; }); });
    }
  });

  // Filtros por pilar sobre un carrusel (botones con data-filtro y data-objetivo)
  document.querySelectorAll('[data-filtros]').forEach(function (grupo) {
    var car = document.querySelector(grupo.getAttribute('data-filtros'));
    if (!car) return;
    grupo.querySelectorAll('[data-filtro]').forEach(function (b) {
      b.addEventListener('click', function () {
        var f = b.getAttribute('data-filtro');
        grupo.querySelectorAll('[data-filtro]').forEach(function (x) { x.classList.toggle('activo', x === b); x.setAttribute('aria-pressed', x === b); });
        car.querySelectorAll('.carrusel-item').forEach(function (it) {
          var el = it.querySelector('[data-pilar]');
          it.hidden = f !== 'todos' && (!el || el.getAttribute('data-pilar') !== f);
        });
        if (car.refrescar) car.refrescar();
      });
    });
  });

  // Mapa: el iframe de Google (~500 KB) se carga solo al pedirlo
  document.querySelectorAll('[data-mapa]').forEach(function (caja) {
    var b = caja.querySelector('button');
    if (!b) return;
    b.addEventListener('click', function () {
      var f = document.createElement('iframe');
      f.src = caja.getAttribute('data-mapa');
      f.title = 'Ubicación de CMC';
      f.referrerPolicy = 'no-referrer-when-downgrade';
      f.allowFullscreen = true;
      caja.innerHTML = '';
      caja.appendChild(f);
    });
  });

  // Formularios de prospecto (web y tarjeta)
  document.querySelectorAll('.js-form-prospecto').forEach(function (form) {
    var estado = form.querySelector('.form-estado');
    var boton = form.querySelector('[type=submit]');
    var textoBoton = boton.querySelector('.js-texto') || boton;
    var original = textoBoton.textContent;

    var limpiarErrores = function () {
      form.querySelectorAll('.is-invalid').forEach(function (c) { c.classList.remove('is-invalid'); });
      form.querySelectorAll('.campo-error').forEach(function (c) { c.remove(); });
    };
    var marcar = function (nombre, msg) {
      var campo = form.querySelector('[name="' + nombre + '"]');
      if (!campo) return;
      campo.classList.add('is-invalid');
      var div = document.createElement('div');
      div.className = 'campo-error';
      div.textContent = msg;
      campo.insertAdjacentElement('afterend', div);
    };
    var mostrar = function (tipo, msg) { estado.className = 'form-estado ' + tipo; estado.textContent = msg; };

    form.addEventListener('submit', function (ev) {
      ev.preventDefault();
      limpiarErrores();
      estado.className = 'form-estado';
      estado.textContent = '';

      // Validacion basica en el navegador
      var faltan = false;
      form.querySelectorAll('[required]').forEach(function (c) {
        if (!c.value.trim()) { marcar(c.name, 'Este campo es obligatorio.'); faltan = true; }
        else if (c.type === 'email' && !c.checkValidity()) { marcar(c.name, 'El correo no es válido.'); faltan = true; }
      });
      if (faltan) { mostrar('error', 'Revisa los campos marcados.'); return; }

      boton.disabled = true;
      textoBoton.textContent = 'Enviando…';
      fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'Accept': 'application/json' } })
        .then(function (r) { return r.json().catch(function () { return { ok: false }; }); })
        .then(function (res) {
          if (res.ok) {
            form.reset();
            mostrar('ok', res.mensaje || '¡Gracias! Te contactaremos pronto.');
            form.dispatchEvent(new CustomEvent('prospecto:enviado', { detail: res }));
          } else {
            if (res.campos) Object.keys(res.campos).forEach(function (k) { marcar(k, res.campos[k]); });
            mostrar('error', res.error || 'No pudimos enviar tu mensaje. Intenta de nuevo.');
          }
        })
        .catch(function () { mostrar('error', 'Sin conexión. Revisa tu internet e inténtalo de nuevo.'); })
        .then(function () { boton.disabled = false; textoBoton.textContent = original; });
    });
  });
})();
