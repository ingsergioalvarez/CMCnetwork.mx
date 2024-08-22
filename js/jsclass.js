document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#contact-form');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData(form);
        const name = formData.get('name');
        const email = formData.get('email');
        const telefono = formData.get('telefono');
        const message = formData.get('message');

        const mailtoLink = `mailto:atc_clientes@cmcnetwork.mx?subject=solicitude de información de ${name}&body=Nombre: ${name}%0D%0AEmail: ${email}%0D%0ATelefono: ${telefono}%0D%0AMensaje: ${message}`;
        
        // Open the mail client
        window.location.href = mailtoLink;
    });
});
