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
  // Avance automatico: se pausa al pasar el mouse, al tocar o con el foco dentro
  var autoAvance = function (el, avanzar, ms) {
    if (!ms || sinMovimiento) return;
    var pausa = false;
    setInterval(function () { if (!pausa && !document.hidden) avanzar(); }, ms);
    ['mouseenter', 'focusin', 'touchstart'].forEach(function (ev) { el.addEventListener(ev, function () { pausa = true; }, { passive: true }); });
    ['mouseleave', 'focusout', 'touchend'].forEach(function (ev) { el.addEventListener(ev, function () { pausa = false; }); });
  };
  document.querySelectorAll('[data-carrusel]').forEach(function (car) {
    var pista = car.querySelector('.carrusel-pista');
    var puntosCar = car.querySelector('.carrusel-puntos');
    // Tarjetas reales visibles (sin las copias ni las ocultas por un filtro)
    var reales = function () {
      return Array.prototype.filter.call(pista.children, function (it) { return !it.hidden && !it.classList.contains('carrusel-clon'); });
    };
    var hueco = function () { return parseFloat(getComputedStyle(pista).columnGap || 0); };
    var paso = function () {
      var it = reales()[0];
      return it ? it.getBoundingClientRect().width + hueco() : pista.clientWidth;
    };
    var saltar = function (left) { // sin animacion, aunque la pista tenga scroll-behavior: smooth
      pista.style.scrollBehavior = 'auto';
      pista.scrollLeft = left;
      pista.style.scrollBehavior = '';
    };
    // Copias de las tarjetas al final: del ultimo se pasa al primero sin regresar todo el carrusel
    var ciclico = false;
    var clonar = function () {
      Array.prototype.slice.call(pista.querySelectorAll('.carrusel-clon')).forEach(function (c) { pista.removeChild(c); });
      var lista = reales();
      ciclico = lista.length > Math.round((pista.clientWidth + hueco()) / paso());
      if (!ciclico) return;
      lista.forEach(function (it) {
        var c = it.cloneNode(true);
        c.classList.add('carrusel-clon');
        c.setAttribute('aria-hidden', 'true');
        if ('inert' in c) c.inert = true;
        c.querySelectorAll('a, button, [tabindex]').forEach(function (el) { el.setAttribute('tabindex', '-1'); });
        pista.appendChild(c);
      });
    };
    var paginas = function () {
      return ciclico ? reales().length : Math.max(1, Math.ceil((pista.scrollWidth - pista.clientWidth) / paso() - 0.01) + 1);
    };
    var pagina = function () {
      var p = Math.round(pista.scrollLeft / paso());
      return ciclico ? p % reales().length : p;
    };
    // Al quedar sobre una copia, salta en silencio a la tarjeta real equivalente
    var normalizar = function () {
      if (!ciclico) return;
      var n = reales().length, p = Math.round(pista.scrollLeft / paso());
      if (p >= n) saltar((p - n) * paso());
    };
    var ir = function (i) {
      var total = paginas();
      if (ciclico) {
        normalizar(); // si quedo sobre una copia, primero vuelve a la real para no recorrer todo hacia atras
        // Hacia atras desde la primera: se para en su copia y retrocede desde ahi
        if (i < 0) { saltar(total * paso()); i += total; }
      } else {
        if (i >= total) i = 0;
        if (i < 0) i = total - 1;
      }
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
    pista.addEventListener('scroll', function () { clearTimeout(espera); espera = setTimeout(function () { normalizar(); marcar(); }, 120); }, { passive: true });
    window.addEventListener('resize', function () { clearTimeout(espera); espera = setTimeout(function () { clonar(); normalizar(); pintarPuntos(); }, 150); });
    car.refrescar = function () { saltar(0); clonar(); pintarPuntos(); };
    clonar();
    pintarPuntos();
    autoAvance(car, function () { ir(pagina() + 1); }, parseInt(car.getAttribute('data-auto'), 10));
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

  // Coverflow: la tarjeta activa al centro y al frente; las demas detras, atenuadas e inertes
  document.querySelectorAll('[data-coverflow]').forEach(function (cf) {
    var tarjetas = Array.prototype.slice.call(cf.querySelectorAll('.tec'));
    var puntosCf = cf.querySelector('.coverflow-puntos');
    var n = tarjetas.length, actual = 0;
    var pintar = function () {
      tarjetas.forEach(function (t, i) {
        var k = i - actual;
        if (k > n / 2) k -= n;              // camino mas corto (circular)
        if (k < -n / 2) k += n;
        var abs = Math.abs(k);
        t.style.setProperty('--k', k);
        t.style.setProperty('--abs', Math.min(abs, 2));
        t.style.setProperty('--lado', k < 0 ? '270deg' : '90deg');
        t.style.zIndex = 10 - abs;
        t.style.opacity = abs > 1 ? 0 : 1;
        t.classList.toggle('activo', k === 0);
        if ('inert' in t) t.inert = k !== 0; // solo la del frente recibe foco y clics internos
        t.setAttribute('aria-hidden', k !== 0);
      });
      Array.prototype.forEach.call(puntosCf.children, function (b, i) { b.classList.toggle('activo', i === actual); });
    };
    var ir = function (i) { actual = (i + n) % n; pintar(); };
    tarjetas.forEach(function (t, i) {
      var b = document.createElement('button');
      b.type = 'button';
      b.setAttribute('aria-label', 'Ver ' + t.querySelector('h3').textContent);
      b.addEventListener('click', function () { ir(i); });
      puntosCf.appendChild(b);
      // clic en una tarjeta lateral la trae al frente (inert bloquea sus enlaces, no el clic del contenedor)
      t.parentNode.addEventListener('click', function (ev) {
        if (ev.target.closest('.tec') === t && !t.classList.contains('activo')) ir(i);
      });
    });
    cf.querySelectorAll('.coverflow-btn').forEach(function (b) {
      b.addEventListener('click', function () { ir(actual + parseInt(b.getAttribute('data-dir'), 10)); });
    });
    cf.addEventListener('keydown', function (ev) {
      if (ev.key === 'ArrowLeft') ir(actual - 1);
      if (ev.key === 'ArrowRight') ir(actual + 1);
    });
    var x0 = null;
    cf.addEventListener('touchstart', function (ev) { x0 = ev.touches[0].clientX; }, { passive: true });
    cf.addEventListener('touchend', function (ev) {
      if (x0 === null) return;
      var dx = ev.changedTouches[0].clientX - x0;
      if (Math.abs(dx) > 40) ir(actual + (dx < 0 ? 1 : -1));
      x0 = null;
    });
    // Enlaces externos (tarjetas de la portada) que abren una tecnologia
    document.querySelectorAll('[data-ir-tecnologia]').forEach(function (a) {
      a.addEventListener('click', function () {
        var i = tarjetas.findIndex(function (t) { return t.getAttribute('data-clave') === a.getAttribute('data-ir-tecnologia'); });
        if (i >= 0) ir(i);
      });
    });
    pintar();
    autoAvance(cf, function () { ir(actual + 1); }, 6000);
  });

  // "Cotizar" de una tecnologia: prellena el formulario de contacto
  document.querySelectorAll('[data-cotizar]').forEach(function (b) {
    b.addEventListener('click', function () {
      var interes = document.getElementById('c-interes');
      var msj = document.getElementById('c-mensaje');
      if (interes && interes.querySelector('option[value="corenet"]')) interes.value = 'corenet';
      if (msj) msj.value = 'Quiero cotizar: ' + b.getAttribute('data-cotizar') + '.';
    });
  });

  // Cobertura: mapa por capas con recorrido automatico, filtros, buscador y panel
  var datosCob = document.getElementById('cobertura-datos');
  var cobmap = document.querySelector('[data-cobmap]');
  if (datosCob && cobmap) {
    var cob = JSON.parse(datosCob.textContent);
    var panelCob = cobmap.querySelector('[data-panel]');
    var selEstado = cobmap.querySelector('[data-buscar-estado]');
    var botonAuto = cobmap.querySelector('[data-recorrido]');
    var recorrido = ['propia', 'extendida', 'interconexion', 'lte', 'todas'];
    var vista = 'todas', estadoSel = null, auto = !sinMovimiento, relojCob;
    var nodo = function (etiqueta, clase, texto) {
      var n = document.createElement(etiqueta);
      if (clase) n.className = clase;
      if (texto !== undefined) n.textContent = texto;
      return n;
    };
    var contar = function (v) {
      return v === 'todas' || v === 'lte' ? cob.estados.length : cob.estados.filter(function (e) { return e[1] === v; }).length;
    };
    var insignia = function (texto, color) { var s = nodo('span', 'cobmap-insignia', texto); s.style.setProperty('--chip', color); return s; };
    var botonVista = function (v, color, contenido) {
      var b = nodo('button', 'cobmap-opcion'); b.type = 'button';
      var p = nodo('i', 'punto'); p.style.background = color; b.appendChild(p);
      b.appendChild(document.createTextNode(' ' + contenido));
      b.addEventListener('click', function () { elegirVista(v); });
      return b;
    };
    var pintarPanel = function () {
      panelCob.innerHTML = '';
      if (estadoSel !== null) {
        var est = cob.estados[estadoSel], niv = cob.niveles[est[1]];
        panelCob.appendChild(insignia(niv.nombre, niv.color));
        panelCob.appendChild(nodo('h3', '', est[0]));
        var dl = nodo('dl', 'cobmap-datos');
        var dato = function (t, d) { var div = nodo('div'); div.appendChild(nodo('dt', '', t)); div.appendChild(nodo('dd', '', d)); dl.appendChild(div); };
        dato('Tecnología', niv.tecnologia);
        if (est[2]) dato('Cobertura en', est[2].join(', ') + '. Otras zonas del estado, por factibilidad.');
        dato('También disponible', 'LTE y satelital (zonas offnet y respaldo)');
        panelCob.appendChild(dl);
        panelCob.appendChild(nodo('p', 'cobmap-texto', niv.texto));
      } else if (vista === 'todas') {
        panelCob.appendChild(insignia('32 / 32 estados', cob.lte.color));
        panelCob.appendChild(nodo('h3', '', 'Cobertura en todo México'));
        var ul = nodo('ul', 'cobmap-resumen');
        Object.keys(cob.niveles).forEach(function (k) {
          var li = nodo('li'); li.appendChild(botonVista(k, cob.niveles[k].color, contar(k) + ' · ' + cob.niveles[k].nombre)); ul.appendChild(li);
        });
        var li = nodo('li'); li.appendChild(botonVista('lte', cob.lte.color, '32 · ' + cob.lte.nombre)); ul.appendChild(li);
        panelCob.appendChild(ul);
        panelCob.appendChild(nodo('p', 'cobmap-texto', 'Elige tu estado para ver con qué tecnología te conectamos.'));
      } else {
        var info = vista === 'lte' ? cob.lte : cob.niveles[vista];
        panelCob.appendChild(insignia(contar(vista) + ' estados', info.color));
        panelCob.appendChild(nodo('h3', '', info.nombre));
        panelCob.appendChild(nodo('p', 'cobmap-texto', info.texto));
        if (vista !== 'lte') {
          var lista = nodo('ul', 'cobmap-estados');
          cob.estados.forEach(function (e, i) {
            if (e[1] !== vista) return;
            var b = nodo('button', '', e[0]); b.type = 'button';
            b.addEventListener('click', function () { elegirEstado(i); });
            var li2 = nodo('li'); li2.appendChild(b); lista.appendChild(li2);
          });
          panelCob.appendChild(lista);
        }
      }
    };
    var mostrar = function (v) {
      vista = v;
      cobmap.querySelectorAll('[data-vista]').forEach(function (c) { c.classList.toggle('activo', c.getAttribute('data-vista') === v); });
      cobmap.querySelectorAll('[data-capa]').forEach(function (img) {
        var c = img.getAttribute('data-capa');
        img.classList.toggle('activo', v === 'todas' ? c !== 'lte' : c === v);
      });
      pintarPanel();
    };
    var iconoAuto = function (nombre, etiqueta) {
      botonAuto.innerHTML = '<svg class="bi" aria-hidden="true"><use href="#bi-' + nombre + '"/></svg>';
      botonAuto.setAttribute('aria-label', etiqueta);
    };
    var detener = function () { auto = false; clearInterval(relojCob); iconoAuto('play-fill', 'Reproducir recorrido'); };
    var arrancar = function () {
      auto = true; estadoSel = null; selEstado.value = '';
      iconoAuto('pause-fill', 'Pausar recorrido');
      clearInterval(relojCob);
      relojCob = setInterval(function () { if (!document.hidden) mostrar(recorrido[(recorrido.indexOf(vista) + 1) % recorrido.length]); }, 3200);
    };
    var elegirVista = function (v) { detener(); estadoSel = null; selEstado.value = ''; mostrar(v); };
    var elegirEstado = function (i) { detener(); estadoSel = i; selEstado.value = String(i); mostrar(cob.estados[i][1]); };
    cobmap.querySelectorAll('[data-vista]').forEach(function (c) {
      c.addEventListener('click', function () { elegirVista(c.getAttribute('data-vista')); });
    });
    selEstado.addEventListener('change', function () { if (selEstado.value !== '') elegirEstado(parseInt(selEstado.value, 10)); });
    botonAuto.addEventListener('click', function () { if (auto) detener(); else arrancar(); });
    mostrar('todas');
    if (auto && 'IntersectionObserver' in window) {
      // el recorrido empieza cuando el mapa entra en pantalla
      var ioCob = new IntersectionObserver(function (en) {
        if (en[0].isIntersecting) { ioCob.disconnect(); if (auto) arrancar(); }
      });
      ioCob.observe(cobmap);
    } else if (auto) {
      arrancar();
    } else {
      detener();
    }
    // "Validar cobertura" / "Cotizar enlace": prellena el formulario de contacto
    document.querySelectorAll('[data-validar-cobertura]').forEach(function (b) {
      b.addEventListener('click', function () {
        var interes = document.getElementById('c-interes');
        var msj = document.getElementById('c-mensaje');
        if (interes && interes.querySelector('option[value="corenet"]')) interes.value = 'corenet';
        if (msj && !msj.value) msj.value = 'Quiero validar cobertura de enlace dedicado' + (estadoSel !== null ? ' en ' + cob.estados[estadoSel][0] : '') + '.';
      });
    });
  }

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
