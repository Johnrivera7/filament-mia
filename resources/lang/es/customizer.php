<?php

return [

    'title' => 'Apariencia',

    'subheading' => 'Ajusta el tema y observa el resultado sobre la marcha. Los cambios afectan a este panel para todo el mundo; no se guarda nada hasta que pulses guardar.',

    'mode' => [
        'label' => 'Previsualizar en',
        'light' => 'Claro',
        'dark' => 'Oscuro',
        'system' => 'Sistema',
        'disabled' => 'Este panel está fijado a un solo modo de color.',
    ],

    'presets' => [
        'heading' => 'Preajustes',
        'description' => 'Un punto de partida completo. Al aplicar uno se rellena el formulario de abajo, que puedes seguir ajustando.',
    ],

    'colour' => [
        'heading' => 'Color',
        'description' => 'Cada color genera una rampa de once tonos que conserva su matiz y su grado de saturación, de modo que un color discreto sigue siendo discreto.',
        'accent' => 'Acento',
        'accent_help' => 'Botones, enlaces, anillos de foco y navegación activa.',
        'secondary' => 'Secundario',
        'secondary_help' => 'Un color de apoyo, usado allí donde un componente lo pide.',
        'custom_neutral' => 'Reemplazar el neutro',
        'custom_neutral_help' => 'Los fondos, superficies, bordes y el texto se construyen a partir del neutro. La rampa incluida es un taupe cálido afinado para crema en modo claro y espresso en oscuro; reemplázala solo si quieres otra temperatura en todo el panel.',
        'neutral' => 'Neutro',
        'neutral_help' => 'Indica un tono medio. La rampa se deriva de él.',
        'danger' => 'Peligro',
        'warning' => 'Advertencia',
        'success' => 'Éxito',
        'info' => 'Información',
    ],

    'type' => [
        'heading' => 'Tipografía',
        'description' => 'Servida desde Bunny Fonts, que no usa cookies ni registra direcciones IP. La lista es corta a propósito: son las familias que aguantan los tamaños y el espaciado que aplica el tema.',
        'sans' => 'Interfaz',
        'serif' => 'Titulares',
        'serif_headings' => 'Componer los titulares en la serif',
        'serif_headings_help' => 'Desactívalo para mantener toda la interfaz en la familia de interfaz, con un aspecto más sobrio.',
    ],

    'shape' => [
        'heading' => 'Forma y densidad',
        'description' => 'Radios de esquina y altura de fila, aplicados a toda la interfaz a la vez.',
        'roundness' => 'Redondez',
        'roundness_options' => [
            'sharp' => 'Recta',
            'subtle' => 'Sutil',
            'soft' => 'Suave',
            'round' => 'Redondeada',
        ],
        'density' => 'Densidad',
        'density_options' => [
            'compact' => 'Compacta',
            'comfortable' => 'Cómoda',
            'spacious' => 'Amplia',
        ],
    ],

    'depth' => [
        'heading' => 'Profundidad y movimiento',
        'description' => 'Cuánto se despega la interfaz de la página y cómo se mueve.',
        'elevation' => 'Elevación',
        'elevation_help' => 'Escala todas las sombras. 0 deja la interfaz completamente plana. Las sombras se tiñen con el neutro y nunca con negro puro.',
        'motion' => 'Movimiento',
        'motion_help' => 'Animaciones de entrada y transiciones al pasar el cursor. Es independiente de la preferencia de movimiento reducido, que siempre se respeta.',
    ],

    'specimen' => [
        'heading' => 'Muestra',
        'description' => 'Los componentes a los que más afectan estos ajustes.',
        'sample_heading' => 'Un titular, compuesto en la familia de display',
        'sample_body' => 'Texto de contenido en la familia de interfaz, al tamaño y con la medida que el tema usa para el cuerpo. Lo bastante largo como para juzgar el emparejamiento, el color del texto sobre la página y cuánto aire deja la densidad a su alrededor.',
        'primary_action' => 'Acción principal',
        'secondary_action' => 'Secundaria',
        'badge_success' => 'Resuelto',
        'badge_warning' => 'Pendiente',
        'badge_danger' => 'Vencido',
        'badge_info' => 'Borrador',
    ],

    'actions' => [
        'save' => 'Guardar',
        'reset' => 'Restablecer',
        'reset_heading' => '¿Restablecer la apariencia?',
        'reset_description' => 'Se descartan los ajustes guardados y el panel vuelve a los valores de tu archivo de configuración y de tu código. Esto afecta a todo el mundo que use este panel.',
    ],

    'notifications' => [
        'saved' => 'Apariencia guardada',
        'reset' => 'Apariencia restablecida',
        'invalid' => 'No se han podido guardar esos ajustes',
    ],

];
