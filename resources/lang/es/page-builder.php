<?php

return [

    'title' => 'Página pública',

    'navigation' => 'Página pública',

    'subheading' => 'Agrega secciones, arrástralas para reordenarlas y edita su contenido. Todo se guarda como borrador hasta que publiques.',

    'sections' => 'Secciones',

    'add_section' => 'Agregar una sección',

    'unpublished_hint' => 'Hay cambios que los visitantes todavía no ven.',

    'actions' => [
        'save' => 'Guardar borrador',
        'publish' => 'Publicar cambios',
        'unpublished' => 'sin publicar',
        'open_draft' => 'Abrir el borrador',
        'reset' => 'Restaurar la página inicial',
        'reset_description' => 'Se reemplazan las secciones del borrador por las que trae el tema. Lo publicado no cambia hasta que publiques de nuevo.',
        'reset_confirm' => 'Restaurar',
    ],

    'notifications' => [
        'saved' => 'Borrador guardado',
        'saved_body' => 'Publica cuando quieras que lo vean los visitantes.',
        'published' => 'Página publicada',
        'published_body' => 'Los visitantes ya ven esta versión.',
    ],

    'preview' => [
        'heading' => 'Previsualización',
        'description' => 'Muestra el borrador. Los visitantes siguen viendo lo último que publicaste.',
        'refresh' => 'Refrescar',
        'live' => 'En vivo',
        'live_hint' => 'Al salir de cada campo se guarda el borrador y la previsualización se actualiza.',
        'widths' => [
            'mobile' => 'Móvil',
            'tablet' => 'Tablet',
            'desktop' => 'Escritorio',
        ],
    ],

    'page' => [
        'skip' => 'Saltar al contenido',
        'sections' => 'Secciones',
        'menu' => 'Abrir el menú',
        'footer_links' => 'Enlaces del pie',
        'draft_notice' => 'Estás viendo el borrador. Los visitantes siguen viendo la versión publicada.',
        'empty_heading' => 'Esta página aún no tiene contenido',
        'empty_body' => 'Agrega y publica secciones desde el constructor y aparecerán aquí.',
        'switch_to_light' => 'Cambiar a modo claro',
        'switch_to_dark' => 'Cambiar a modo oscuro',
    ],

    'surfaces' => [
        'canvas' => 'Lienzo — el fondo base',
        'warm' => 'Cálido — un tono más cálido, para alternar',
        'raised' => 'Elevado — la superficie clara de tarjeta',
        'deep' => 'Profundo — banda oscura con texto claro',
    ],

    'shared' => [
        'heading' => 'Ajustes de la sección',
        'visible' => 'Visible en la página',
        'visible_help' => 'Al desactivarlo la sección deja de publicarse, pero conserva su contenido.',
        'anchor' => 'Ancla',
        'anchor_help' => 'Identificador para enlazar desde el menú, sin «#». Por ejemplo: como-funciona.',
        'anchor_format' => 'Usa solo minúsculas, números y guiones.',
        'surface' => 'Fondo',
        'actions' => 'Botones',
        'action_label' => 'Texto',
        'action_url' => 'Destino',
        'action_url_help' => 'Una URL completa o un ancla como #precios.',
        'action_style' => 'Estilo',
        'styles' => [
            'solid' => 'Sólido — la acción principal',
            'outline' => 'Contorno — una acción secundaria',
            'text' => 'Solo texto',
        ],
        'links' => 'Enlaces',
        'eyebrow' => 'Antetítulo',
        'eyebrow_help' => 'La línea corta en versalitas sobre el título.',
        'heading_text' => 'Título',
        'heading_quiet' => 'Continuación del título, en tono apagado',
        'heading_quiet_help' => 'Se imprime a continuación del título, en la misma línea.',
        'lead' => 'Bajada',
        'title' => 'Título',
        'subtitle' => 'Subtítulo',
        'description' => 'Descripción',
        'icon' => 'Icono',
    ],

    'blocks' => [

        'navigation' => [
            'label' => 'Barra de navegación',
            'brand' => 'Nombre',
            'brand_help' => 'Déjalo vacío para usar el nombre de la aplicación.',
            'logo' => 'Logotipo',
            'logo_help' => 'Opcional. Se muestra antes del nombre.',
            'sticky' => 'Fijar arriba al hacer scroll',
            'scheme_toggle' => 'Ofrecer un cambio de modo claro/oscuro',
            'scheme_toggle_help' => 'Sigue la preferencia del sistema del visitante hasta que elija.',
            'actions' => 'Acción de la barra',
        ],

        'hero' => [
            'label' => 'Portada',
            'heading' => 'Titular',
            'heading_accent' => 'Cierre del titular, en color de acento',
            'heading_accent_help' => 'Se imprime a continuación del titular. Déjalo vacío si no quieres destacar nada.',
            'image' => 'Imagen',
            'image_help' => 'Opcional. Se muestra junto al titular. Máximo 2 MB.',
            'image_alt' => 'Texto alternativo de la imagen',
            'image_alt_help' => 'Descríbela para quien no puede verla. Déjalo vacío solo si es puramente decorativa.',
            'card' => 'Tarjeta de muestra',
            'card_visible' => 'Mostrar la tarjeta',
            'card_eyebrow' => 'Antetítulo',
            'card_note' => 'Nota',
            'card_title' => 'Título',
            'card_subtitle' => 'Subtítulo',
            'card_rows' => 'Filas',
            'card_row_label' => 'Etiqueta',
            'card_row_title' => 'Texto',
            'card_row_note' => 'Detalle',
        ],

        'features' => [
            'label' => 'Características',
            'columns' => 'Columnas en escritorio',
            'two' => 'Dos',
            'three' => 'Tres',
            'items' => 'Características',
        ],

        'steps' => [
            'label' => 'Cómo funciona',
            'items' => 'Pasos',
            'title' => 'Título del paso',
        ],

        'comparison' => [
            'label' => 'Comparación',
            'columns' => 'Cabeceras de las columnas',
            'primary_title' => 'Columna destacada',
            'secondary_title' => 'Columna de contraste',
            'items' => 'Filas',
            'criterion' => 'Criterio',
            'primary_value' => 'Valor destacado',
            'secondary_value' => 'Valor de contraste',
        ],

        'metrics' => [
            'label' => 'Cifras',
            'items' => 'Cifras',
            'value' => 'Cifra',
            'label_field' => 'Qué mide',
        ],

        'testimonials' => [
            'label' => 'Testimonios',
            'items' => 'Testimonios',
            'quote' => 'Cita',
            'author' => 'Quién lo dice',
            'role' => 'Cargo u organización',
            'avatar' => 'Retrato',
        ],

        'pricing' => [
            'label' => 'Precios',
            'items' => 'Planes',
            'name' => 'Nombre del plan',
            'featured' => 'Destacar este plan',
            'price' => 'Precio',
            'price_help' => 'Escríbelo tal cual quieres que se lea, por ejemplo: 49 al mes, o A convenir.',
            'period' => 'Periodo',
            'description' => 'Para quién es',
            'features' => 'Qué incluye',
            'features_help' => 'Una línea por punto.',
            'action_label' => 'Texto del botón',
            'action_url' => 'Destino del botón',
            'note' => 'Nota al pie de la sección',
        ],

        'faq' => [
            'label' => 'Preguntas frecuentes',
            'items' => 'Preguntas',
            'question' => 'Pregunta',
            'answer' => 'Respuesta',
        ],

        'call_to_action' => [
            'label' => 'Llamada a la acción',
        ],

        'footer' => [
            'label' => 'Pie de página',
            'description' => 'Descripción breve',
            'legal' => 'Nota legal',
        ],

    ],

    /*
     * La página con la que empieza una instalación nueva. Texto de muestra que
     * explica para qué sirve cada sección, en vez de afirmaciones inventadas
     * sobre un producto que el tema no conoce.
     */
    'starter' => [

        'nav' => [
            'features' => 'Características',
            'steps' => 'Cómo funciona',
            'faq' => 'Preguntas',
            'action' => 'Escríbenos',
        ],

        'hero' => [
            'eyebrow' => 'Reemplaza esta línea',
            'heading' => 'Un titular que diga a qué te dedicas,',
            'heading_accent' => 'de una sola vez.',
            'lead' => 'Una o dos frases bajo el titular, para lo que el titular tuvo que dejar fuera. Limítate a lo que alguien necesita saber antes de decidir si sigue leyendo.',
            'primary' => 'Acción principal',
            'secondary' => 'Cómo funciona',
            'card_eyebrow' => 'Muestra',
            'card_note' => 'Editable',
            'card_title' => 'Una muestra del producto',
            'card_subtitle' => 'Es marcado, no una captura, así que se lee bien a cualquier ancho',
            'rows' => [
                'one' => [
                    'label' => 'Fila',
                    'title' => 'Algo que el producto muestra',
                    'note' => 'Con un detalle al lado',
                ],
                'two' => [
                    'label' => 'Fila',
                    'title' => 'Otra más, para darle cuerpo a la tarjeta',
                    'note' => 'Hasta cuatro en total',
                ],
                'three' => [
                    'label' => 'Fila',
                    'title' => 'Apaga la tarjeta si prefieres no usarla',
                    'note' => 'O reemplázala por una imagen',
                ],
            ],
        ],

        'metrics' => [
            'eyebrow' => 'En cifras',
            'one' => ['value' => '3', 'label' => 'cifras caben cómodamente en una línea'],
            'two' => ['value' => '4', 'label' => 'es el máximo que muestra esta sección'],
            'three' => ['value' => '1', 'label' => 'afirmación por cifra, y que sea una que puedas sostener'],
        ],

        'features' => [
            'eyebrow' => 'Qué hace',
            'heading' => 'Lo que vale la pena decir,',
            'heading_quiet' => 'y nada más.',
            'lead' => 'Dos o tres columnas de entradas cortas. El icono de cada una es opcional, y una sección de seis se lee mejor que una de doce.',
            'one' => ['title' => 'Lo primero', 'text' => 'Una o dos frases sobre qué es y por qué importa. Lo concreto gana a lo impresionante.'],
            'two' => ['title' => 'Lo segundo', 'text' => 'Di qué ocurre, no qué permite. Quien lee saca el resto solo.'],
            'three' => ['title' => 'Lo tercero', 'text' => 'Si una característica necesita un párrafo, seguramente quiere una sección propia.'],
            'four' => ['title' => 'Lo cuarto', 'text' => 'Las entradas se reordenan arrastrándolas, y se quitan cuando dejan de ser ciertas.'],
            'five' => ['title' => 'Lo quinto', 'text' => 'Los iconos salen de una lista corta y elegida, así que una página no acaba con doce estilos distintos.'],
            'six' => ['title' => 'Lo sexto', 'text' => 'Seis completan la retícula a tres columnas. Para aquí, salvo que tengas un motivo para seguir.'],
        ],

        'steps' => [
            'eyebrow' => 'Cómo funciona',
            'heading' => 'Tres pasos, en orden.',
            'lead' => 'El orden es el mensaje, así que esta sección se numera sola.',
            'one' => ['title' => 'El primer paso', 'text' => 'Lo que hace quien lee para empezar. Escrito como una instrucción, no como una descripción.'],
            'two' => ['title' => 'El segundo paso', 'text' => 'Qué ocurre después, y quién lo hace: esa persona o tú.'],
            'three' => ['title' => 'El tercer paso', 'text' => 'Con qué se queda al final. Este es el paso en el que conviene ser concreto.'],
        ],

        'faq' => [
            'eyebrow' => 'Preguntas frecuentes',
            'heading' => 'Lo que suelen preguntar antes de empezar.',
            'one' => [
                'question' => '¿Qué va en esta sección?',
                'answer' => 'Las preguntas que aparecen antes de decidirse, respondidas sin rodeos. Si una respuesta da vergüenza escribirla, suele ser la más útil de la página.',
            ],
            'two' => [
                'question' => '¿Cuántas preguntas conviene poner?',
                'answer' => 'Las que de verdad te hacen. Tres preguntas reales valen más que diez escritas para rellenar la sección.',
            ],
            'three' => [
                'question' => '¿Se puede quitar esta sección?',
                'answer' => 'Sí. Cualquier sección se puede ocultar sin perder su contenido, o borrar del todo y volver a agregarla desde el selector.',
            ],
        ],

        'call_to_action' => [
            'eyebrow' => 'Para empezar',
            'heading' => 'Un solo paso siguiente',
            'lead' => 'Cierra la página con lo único que quieres que haga quien la lee, y nada más.',
            'primary' => 'Empezar',
        ],

        'footer' => [
            'description' => 'Una línea sobre qué es esto, para quien llegó primero al final de la página.',
            'legal' => '© :year :name. Todos los derechos reservados.',
        ],

    ],

];
