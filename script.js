function cargarMapa(mapaId, src, textoOverlay, sublistId) {
    var iframes = document.querySelectorAll('iframe');
    iframes.forEach(function (iframe) {
        iframe.style.display = 'none';
    });

    var mapa = document.getElementById(mapaId);
    mapa.src = src;
    mapa.style.display = 'block';
    
    // Aplicar el filtro de tono azul y gris
    //mapa.style.filter = 'hue-rotate(-285deg) saturate(50%) ';


    var contentText = document.getElementById('textcontent');
    contentText.textContent = textoOverlay;

    var overlayInstrucciones = document.querySelector('.overlayInstrucciones');
    overlayInstrucciones.style.display = 'none';
    var overlaydispoinibilidad = document.querySelector('.overlay-tipos-red');
    overlaydispoinibilidad.style.display = 'block';
    var notas = document.querySelector('.notas')
    notas.style.display = 'block';

    // Verificar qué opción se ha seleccionado
    if (mapaId === 'LTE' || mapaId === 'mapa5g' || mapaId === 'mapa3g' || mapaId === 'mapa4g') {
        // Cambiar la imagen del estado de cobertura a verde si se selecciona LTE
        document.getElementById('estadoCobertura').src = 'imagenes/HEXAGONO VERDE.png';
    } else {
        // Usar la imagen predeterminada si no se selecciona LTE
        document.getElementById('estadoCobertura').src = 'imagenes/HEXAGONO AZUL.png';
    }
    var overlaydispoinibilidad = document.querySelector('.overlay-tipos-red');

}



function mostrarMapa(mapaId, textoOverlay, sublistId) {
    var iframes = document.querySelectorAll('iframe');
    iframes.forEach(function (iframe) {
        iframe.style.display = 'none';
    });

    var mapa = document.getElementById(mapaId);
    mapa.style.display = 'block';

    var contentText = document.getElementById('textcontent');
    contentText.textContent = textoOverlay;

    // Muestra la sublista correspondiente si se proporciona un sublistId
    if (sublistId) {
        toggleSublist(sublistId);
    }
}

function toggleSublist(sublistId) {
    var sublist = document.getElementById(sublistId);
    sublist.style.display = sublist.style.display === 'none' ? 'block' : 'none';

    var allSublists = document.querySelectorAll('.sub-menu');
    allSublists.forEach(function (otherSublist) {
        if (otherSublist.id !== sublistId) {
            otherSublist.style.display = 'none';
        }
    });
}

function mostrarSubMenu(menuId, subMenuId) {
    var subMenu = document.getElementById(subMenuId);
    subMenu.style.display = 'block';

    // Oculta el submenú correspondiente si existe
    var allSubMenus = document.querySelectorAll('.sub-menu');
    allSubMenus.forEach(function (otherSubMenu) {
        if (otherSubMenu.id !== subMenuId) {
            otherSubMenu.style.display = 'none';
        }
    });
}

function toggleSublist1(sublist1Id) {
    var sublist1 = document.getElementById(sublist1Id);
    sublist1.style.display = sublist1.style.display === 'none' ? 'block' : 'none';

    var allSublists1 = document.querySelectorAll('.sublist1');
    allSublists1.forEach(function (otherSublist1) {
        if (otherSublist1.id !== sublist1Id) {
            otherSublist1.style.display = 'none';
        }
    });
}


//minimizador menu hamburguesa
function toggleContenidoMenu() {
    var anchoVentana = window.innerWidth;

    // Verifica si la pantalla es menor a 768px usando la media query
    if (anchoVentana <= 768) {
        var contenidoMenu = document.getElementById('menu');
        if (contenidoMenu.style.display === 'none' || contenidoMenu.style.display === '') {
            contenidoMenu.style.display = 'block';
        } else {
            contenidoMenu.style.display = 'none';
        }
    }
}

// Agrega un evento de cambio de tamaño de ventana para ajustar el comportamiento
window.addEventListener('resize', function () {
    var anchoVentana = window.innerWidth;

    // Verifica si la pantalla es menor a 760px usando la media query
    if (anchoVentana > 768) {
        // Si la pantalla es mayor o igual a 760px, asegúrate de que el menú sea visible
        var contenidoMenu = document.getElementById('menu');
        contenidoMenu.style.display = 'block';
    }
});


/*mapaheptagonos*/
// Inicializar el mapa
var map = L.map('map').setView([20, -100], 5); // Coordenadas y nivel de zoom

// Agregar una capa base de OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
function buscarDireccion() {
    var direccion = document.getElementById('addressInput').value;
    var proxyUrl = 'https://cors-anywhere.herokuapp.com/'; // Proxy CORS Anywhere

    // Realizar una solicitud al servicio de geocodificación de Nominatim a través del proxy
    fetch(proxyUrl + 'https://nominatim.openstreetmap.org/search?format=json&q=' + direccion)
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            // Verificar si se encontraron resultados
            if (data.length > 0) {
                var latitud = parseFloat(data[0].lat);
                var longitud = parseFloat(data[0].lon);

                // Centrar el mapa en la ubicación encontrada
                map.setView([latitud, longitud], 16);
            } else {
                alert('No se encontraron resultados para la dirección ingresada.');
            }
        })
        .catch(function (error) {
            console.log('Error al buscar la dirección:', error);
            alert('Ocurrió un error al buscar la dirección.');
        });
}