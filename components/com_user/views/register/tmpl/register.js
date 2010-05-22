
    // <![CDATA[
    window.addEvent('domready', function() {
        Shadowbox.init({
            skipSetup: true,
            text: {
                cancel:     'Cancelar',
                loading:    'Cargando...',
                close:      '<span class="shortcut">C</span>errar',
                next:       '<span class="shortcut">S</span>iguiente',
                prev:       '<span class="shortcut">A</span>nterior',
                errors:     {
                    single: 'Usted debe instalar el plugin <a href="{0}">{1}</a> para poder ver el contenido.',
                    shared: 'Usted debe instalar <a href="{0}">{1}</a> y <a href="{2}">{3}</a> para poder visualizar el contenido.',
                    either: 'Usted debe instalar <a href="{0}">{1}</a> o <a href="{2}">{3}</a> para poder visualizar el contenido.'
                }
            }
        });

Shadowbox.setup($('zonal'), {
            onClose:function() {
                var kris = Cookie.set('username', 'Harald');
            }
        });
    });

    // ]]>

