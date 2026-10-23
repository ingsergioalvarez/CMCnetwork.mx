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

  // Carruseles: flechas, puntos y avance continuo
  var sinMovimiento = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  // Rotacion continua y lenta (tipo marquesina) con copias al final para dar la vuelta sin saltos.
  // Se pausa con el mouse, el dedo o el foco; las flechas y los puntos siguen funcionando.
  var VELOCIDAD = 26; // px por segundo
  document.querySelectorAll('[data-carrusel]').forEach(function (car) {
    var pista = car.querySelector('.carrusel-pista');
    var puntosCar = car.querySelector('.carrusel-puntos');
    var mover = !!parseInt(car.getAttribute('data-auto'), 10) && !sinMovimiento;
    var pos = 0, ciclico = false, pausa = false, enPantalla = false, animando = false, ultimo = 0;
    // Tarjetas reales visibles (sin las copias ni las ocultas por un filtro)
    var reales = function () {
      return Array.prototype.filter.call(pista.children, function (it) { return !it.hidden && !it.classList.contains('carrusel-clon'); });
    };
    var hueco = function () { return parseFloat(getComputedStyle(pista).columnGap || 0); };
    var paso = function () {
      var it = reales()[0];
      return it ? it.getBoundingClientRect().width + hueco() : pista.clientWidth;
    };
    var caben = function () { return Math.max(1, Math.round((pista.clientWidth + hueco()) / paso())); };
    var vuelta = function () { return reales().length * paso(); }; // ancho de un ciclo completo
    var poner = function (x) { pos = x; pista.scrollLeft = x; };
    // Copias de las tarjetas reales al final, solo si hay mas tarjetas de las que caben
    var clonar = function () {
      Array.prototype.slice.call(pista.querySelectorAll('.carrusel-clon')).forEach(function (c) { pista.removeChild(c); });
      var lista = reales();
      ciclico = lista.length > caben();
      car.classList.toggle('sin-controles', !ciclico);
      car.classList.toggle('en-marcha', ciclico && mover);
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
    var pagina = function () { return ciclico ? Math.round(pos / paso()) % reales().length : 0; };
    var marcar = function () {
      var p = pagina();
      if (puntosCar.total) { puntosCar.firstChild.textContent = (p + 1) + ' / ' + puntosCar.total; return; }
      Array.prototype.forEach.call(puntosCar.children, function (b, i) { b.classList.toggle('activo', i === p); });
    };
    // Desplazamiento animado hasta una posicion (flechas y puntos); pasa por las copias y regresa sin salto
    var animarA = function (destino) {
      var desde = pos, t0 = null, dur = sinMovimiento ? 0 : 550;
      animando = true;
      var f = function (t) {
        if (!t0) t0 = t;
        var k = dur ? Math.min((t - t0) / dur, 1) : 1, v = vuelta();
        var x = desde + (destino - desde) * (1 - Math.pow(1 - k, 3));
        poner(x >= v ? x - v : x);
        marcar();
        if (k < 1) requestAnimationFrame(f); else { animando = false; ultimo = 0; }
      };
      requestAnimationFrame(f);
    };
    var ir = function (i) { if (ciclico) animarA(i * paso()); };
    var irRelativo = function (dir) {
      if (!ciclico) return;
      var idx = Math.round(pos / paso());
      if (dir < 0 && idx === 0) { poner(pos + vuelta()); idx = reales().length; } // desde la primera: parte de su copia
      animarA((idx + dir) * paso());
    };
    var pintarPuntos = function () {
      puntosCar.innerHTML = '';
      puntosCar.total = 0;
      var n = ciclico ? reales().length : 1;
      if (n < 2) return;
      if (n > 10) { // con muchas tarjetas, un contador "3 / 18"
        puntosCar.appendChild(document.createElement('span')).className = 'carrusel-contador';
        puntosCar.total = n;
      } else {
        for (var i = 0; i < n; i++) {
          var b = document.createElement('button');
          b.type = 'button';
          b.setAttribute('aria-label', 'Ir a ' + (i + 1));
          b.addEventListener('click', ir.bind(null, i));
          puntosCar.appendChild(b);
        }
      }
      marcar();
    };
    car.querySelectorAll('.carrusel-btn').forEach(function (b) {
      b.addEventListener('click', function () { irRelativo(parseInt(b.getAttribute('data-dir'), 10)); });
    });
    // Avance continuo cuadro por cuadro (solo con el carrusel en pantalla y la pestana visible)
    var cuadro = function (t) {
      if (mover && ciclico && enPantalla && !pausa && !animando && !document.hidden) {
        var dt = ultimo ? Math.min(t - ultimo, 64) / 1000 : 0;
        var x = pos + VELOCIDAD * dt, v = vuelta();
        poner(x >= v ? x - v : x);
        marcar();
      }
      ultimo = t;
      requestAnimationFrame(cuadro);
    };
    // Pausa al interactuar; al soltar retoma desde donde el usuario dejo la pista
    var detener = function () { pausa = true; };
    var reanudar = function () { pausa = false; pos = pista.scrollLeft; ultimo = 0; };
    ['mouseenter', 'focusin', 'touchstart'].forEach(function (ev) { car.addEventListener(ev, detener, { passive: true }); });
    ['mouseleave', 'focusout'].forEach(function (ev) { car.addEventListener(ev, reanudar); });
    car.addEventListener('touchend', function () { setTimeout(reanudar, 1500); });
    pista.addEventListener('scroll', function () {
      if (!pausa || animando) return; // el usuario la mueve a mano
      pos = pista.scrollLeft;
      if (ciclico && pos >= vuelta()) poner(pos - vuelta());
      marcar();
    }, { passive: true });
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (en) { enPantalla = en[0].isIntersecting; }).observe(car);
    } else {
      enPantalla = true;
    }
    var espera;
    window.addEventListener('resize', function () { clearTimeout(espera); espera = setTimeout(car.refrescar, 150); });
    car.refrescar = function () { clonar(); poner(0); pintarPuntos(); };
    car.refrescar();
    requestAnimationFrame(cuadro);
  });

  // Filtros por pilar sobre un carrusel (botones con data-filtro y data-objetivo)
  document.querySelectorAll('[data-filtros]').forEach(function (grupo) {
    // data-filtros apunta al contenedor; el carrusel (con refrescar) puede estar dentro
    var car = document.querySelector(grupo.getAttribute('data-filtros'));
    if (car && !car.matches('[data-carrusel]')) car = car.querySelector('[data-carrusel]');
    if (!car) return;
    grupo.querySelectorAll('[data-filtro]').forEach(function (b) {
      b.addEventListener('click', function () {
        var f = b.getAttribute('data-filtro');
        grupo.querySelectorAll('[data-filtro]').forEach(function (x) { x.classList.toggle('activo', x === b); x.setAttribute('aria-pressed', x === b); });
        car.querySelectorAll('.carrusel-item:not(.carrusel-clon)').forEach(function (it) {
          var el = it.querySelector('[data-pilar]');
          it.hidden = f !== 'todos' && (!el || el.getAttribute('data-pilar') !== f);
        });
        if (car.refrescar) car.refrescar();
      });
    });
  });

  // Coverflow: las tarjetas giran de forma continua y lenta; la del frente queda nitida y las demas detras,
  // atenuadas e inertes. Se pausa con el mouse, el dedo o el foco; flechas, puntos y clics la llevan a una tarjeta.
  document.querySelectorAll('[data-coverflow]').forEach(function (cf) {
    var tarjetas = Array.prototype.slice.call(cf.querySelectorAll('.tec'));
    var puntosCf = cf.querySelector('.coverflow-puntos');
    var movil = window.matchMedia('(max-width: 767.98px)');
    var n = tarjetas.length, pos = 0, actual = -1, pausa = false, enPantalla = false, animando = false, ultimo = 0;
    var mover = !sinMovimiento;
    cf.classList.toggle('en-marcha', mover);
    var pintar = function () {
      tarjetas.forEach(function (t, i) {
        var k = i - pos;
        k -= Math.round(k / n) * n;         // camino mas corto (circular)
        var abs = Math.abs(k);
        t.style.setProperty('--k', k.toFixed(4));
        t.style.setProperty('--abs', Math.min(abs, 2).toFixed(4));
        t.style.zIndex = Math.round(100 - abs * 10);
        t.style.opacity = abs <= 1.5 ? 1 : Math.max(0, 1 - (abs - 1.5) * 2); // se desvanece ya fuera de la orilla
      });
      var frente = ((Math.round(pos) % n) + n) % n;
      if (frente === actual) return;
      actual = frente;
      tarjetas.forEach(function (t, i) {
        t.classList.toggle('activo', i === actual);
        if ('inert' in t) t.inert = i !== actual; // solo la del frente recibe foco y clics internos
        t.setAttribute('aria-hidden', i !== actual);
      });
      Array.prototype.forEach.call(puntosCf.children, function (b, i) { b.classList.toggle('activo', i === actual); });
    };
    // Lleva una tarjeta al frente por el camino mas corto, con animacion
    var ir = function (i) {
      var d = i - pos;
      d -= Math.round(d / n) * n;
      var desde = pos, t0 = null, dur = sinMovimiento ? 0 : 650;
      animando = true;
      var f = function (t) {
        if (!t0) t0 = t;
        var k = dur ? Math.min((t - t0) / dur, 1) : 1;
        pos = desde + d * (1 - Math.pow(1 - k, 3));
        pintar();
        if (k < 1) requestAnimationFrame(f); else { pos = ((Math.round(pos) % n) + n) % n; animando = false; ultimo = 0; }
      };
      requestAnimationFrame(f);
    };
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
      b.addEventListener('click', function () { ir(Math.round(pos) + parseInt(b.getAttribute('data-dir'), 10)); });
    });
    cf.addEventListener('keydown', function (ev) {
      if (ev.key === 'ArrowLeft') ir(Math.round(pos) - 1);
      if (ev.key === 'ArrowRight') ir(Math.round(pos) + 1);
    });
    var x0 = null;
    cf.addEventListener('touchstart', function (ev) { x0 = ev.touches[0].clientX; }, { passive: true });
    cf.addEventListener('touchend', function (ev) {
      if (x0 === null) return;
      var dx = ev.changedTouches[0].clientX - x0;
      if (Math.abs(dx) > 40) ir(Math.round(pos) + (dx < 0 ? 1 : -1));
      x0 = null;
    });
    // Enlaces externos (tarjetas de la portada) que abren una tecnologia
    document.querySelectorAll('[data-ir-tecnologia]').forEach(function (a) {
      a.addEventListener('click', function () {
        var i = tarjetas.findIndex(function (t) { return t.getAttribute('data-clave') === a.getAttribute('data-ir-tecnologia'); });
        if (i >= 0) ir(i);
      });
    });
    // Giro continuo a la misma velocidad que los carruseles (px/s del desplazamiento entre tarjetas)
    var cuadro = function (t) {
      if (mover && enPantalla && !pausa && !animando && !document.hidden) {
        var dt = ultimo ? Math.min(t - ultimo, 64) / 1000 : 0;
        var tramo = movil.matches ? tarjetas[0].offsetWidth * 1.06 : tarjetas[0].offsetWidth * 0.93 + 56; // igual que translateX en .tec
        pos = (pos + VELOCIDAD * dt / tramo) % n;
        pintar();
      }
      ultimo = t;
      requestAnimationFrame(cuadro);
    };
    ['mouseenter', 'focusin', 'touchstart'].forEach(function (ev) { cf.addEventListener(ev, function () { pausa = true; }, { passive: true }); });
    ['mouseleave', 'focusout'].forEach(function (ev) { cf.addEventListener(ev, function () { pausa = false; ultimo = 0; }); });
    cf.addEventListener('touchend', function () { setTimeout(function () { pausa = false; ultimo = 0; }, 1500); });
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (en) { enPantalla = en[0].isIntersecting; }).observe(cf);
    } else {
      enPantalla = true;
    }
    pintar();
    requestAnimationFrame(cuadro);
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
    // ?estado=N (desde el recuadro de cobertura): abre ese estado
    var estadoUrl = parseInt(new URLSearchParams(location.search).get('estado'), 10);
    if (estadoUrl >= 0 && estadoUrl < cob.estados.length) { elegirEstado(estadoUrl); cobmap.scrollIntoView(); }
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
    // "Validar cobertura" / "Cotizar enlace": lleva a contacto con el estado elegido en el mensaje
    document.querySelectorAll('[data-validar-cobertura]').forEach(function (b) {
      b.addEventListener('click', function () {
        if (estadoSel === null) return;
        var u = new URL(b.href);
        u.searchParams.set('mensaje', 'Quiero validar cobertura de enlace dedicado en ' + cob.estados[estadoSel][0] + '.');
        b.href = u.toString();
      });
    });
  }

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
