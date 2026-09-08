<?php

return [

    /*
     * Texto de las páginas que el tema dibuja fuera de un panel.
     *
     * Escrito para quien se ha topado con el muro, no para quien lo construyó.
     * Dice qué ha pasado, si se ha perdido algo y qué hacer a continuación —en
     * ese orden— y nunca culpa a quien lo lee ni le pide que vuelva a
     * intentarlo sin explicarle por qué eso podría servir.
     */

    'errors' => [

        '403' => [
            'title' => 'Acceso denegado',
            'heading' => 'No tienes acceso a esta página',
            'body' => 'Tu cuenta llega al panel, pero no a esta página en concreto. Si crees que es un error, pide a quien lo administre que revise tus permisos.',
        ],

        '404' => [
            'title' => 'Página no encontrada',
            'heading' => 'No hay nada en esta dirección',
            'body' => 'La página puede haber cambiado de sitio, o puede haberse eliminado el registro al que apuntaba. El resto del panel no está afectado.',
        ],

        '419' => [
            'title' => 'Sesión caducada',
            'heading' => 'Tu sesión ha caducado',
            'body' => 'Has estado ausente el tiempo suficiente para que el panel cerrara la sesión por seguridad. Al entrar de nuevo volverás aquí.',
        ],

        '500' => [
            'title' => 'Algo ha fallado',
            'heading' => 'Algo ha fallado por nuestra parte',
            'body' => 'El panel no pudo terminar lo que estaba haciendo, y el fallo ha quedado registrado. Volver a intentarlo suele bastar; si no, avisa a quien administre el panel.',
        ],

    ],

    'maintenance' => [
        'title' => 'Cerrado por mantenimiento',
        'heading' => 'Volvemos enseguida',
        'body' => 'El panel está cerrado por una actualización programada. No se ha perdido nada: inténtalo de nuevo en unos minutos.',
    ],

    'actions' => [
        'back' => 'Volver al panel',
        'back_to' => 'Volver a :name',
        'sign_in' => 'Entrar de nuevo',
    ],

];
