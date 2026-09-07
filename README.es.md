<div align="center">

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/mia-avatar-512.png" alt="" width="132" />

# Mía

### Un tema editorial y cálido para Filament

Crema y champán en claro, espresso en oscuro, con una serif de alto contraste<br />
para los titulares. Un panel que se lee como una página impresa.

[![Licencia](https://img.shields.io/badge/licencia-MIT-D9A14E?style=flat-square&labelColor=3C3227)](LICENSE.md)
[![PHP](https://img.shields.io/badge/PHP-8.2%20%E2%80%93%208.4-777BB4?style=flat-square&labelColor=3C3227)](https://www.php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11%20%C2%B7%2012%20%C2%B7%2013-FF2D20?style=flat-square&labelColor=3C3227)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-v5.7%2B-F59E0B?style=flat-square&labelColor=3C3227)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-v4.3-06B6D4?style=flat-square&labelColor=3C3227)](https://tailwindcss.com)

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/cover.jpg" alt="Mía en modo claro y oscuro" width="100%" />

**English version: [README.md](README.md)**

</div>

## Qué es

La mayoría de los temas de administración son un cambio de paleta. Mía intenta
otro *registro*: cálido, silencioso y tipográfico, donde el panel se siente
cuidado en lugar de utilitario. Tres cosas lo sostienen.

**Una voz editorial de verdad.** Los titulares van en una serif de display de
alto contraste y el contenido en una sans humanista geométrica. Los
encabezados de columna, las etiquetas de grupo y los rótulos de las cifras van
pequeños, en mayúsculas y con mucho tracking. El resultado es una jerarquía
tipográfica real, no un mismo peso repetido en tres tamaños.

**Un modo oscuro genuinamente cálido.** No es una inversión de grises fríos:
espresso, taupe y umbra profunda, construidos desde la misma rampa neutra que
el modo claro, para que ambos se lean como un solo diseño.

**La contención como característica.** Bordes capilares de contraste muy bajo,
sombras anchas y difusas teñidas con el neutro en lugar de negro, radios
generosos y un movimiento lento y deliberado. Los estados vacíos llevan una
ilustración dibujada para el tema, no un icono de contorno genérico.

Se distribuye precompilado. No hay que instalar Node, Tailwind ni ningún paso
de compilación.

## Requisitos

| | |
|---|---|
| PHP | 8.2 o superior |
| Laravel | 11.28, 12 o 13 |
| Filament | 5.7 o superior |

## Instalación

```bash
composer require johnrivera7/filament-mia
php artisan filament:assets
```

Registra el plugin en el panel:

```php
use JohnRivera7\FilamentMia\MiaTheme;

->plugin(MiaTheme::make())
```

Y añade a tu `.gitignore` el directorio publicado, que es salida de
compilación:

```gitignore
/public/css/johnrivera7
```

> **Si tu panel llama a `->viteTheme(...)`, quítalo.** Filament le da
> precedencia incondicional sobre `theme`, así que mientras esté presente el
> tema se ignora en silencio, sin error ni advertencia.

## Configuración

Todas las opciones existen de forma fluida en el plugin y como valor por
defecto en un archivo de configuración. La llamada fluida gana.

```php
->plugin(
    MiaTheme::make()
        ->accentColor('#C9A227')
        ->secondaryColor('#E8C4C0')
        ->font('Jost', 'Cormorant Garamond')
        ->roundness('soft')       // sharp | subtle | soft | round
        ->density('comfortable')  // compact | comfortable | spacious
        ->elevation(0.75)         // 0.0 a 2.0; 0 es completamente plano
        ->motion()
        ->darkMode(),
)
```

Para publicar los valores por defecto del proyecto:

```bash
php artisan vendor:publish --tag=filament-mia-config
```

Los colores se resuelven en cada petición como propiedades personalizadas en
OKLCH, y el resto de los ajustes como propiedades `--mia-*` acotadas a
`.fi-panel-{id}`. Nada de esto exige recompilar, y dos paneles de la misma
aplicación pueden configurarse distinto.

Un color se expande en una rampa de once tonos que preserva su matiz *y* su
carácter de saturación, de modo que un color discreto sigue siendo discreto en
vez de saturarse al máximo.

La entrada inválida lanza `InvalidThemeOption` al arrancar. Es deliberado:
Filament convierte colores sin validarlos, así que un valor no interpretable
produciría una paleta negra y ningún error.

### Utilidades de Tailwind en tus propias vistas

Un tema precompilado solo puede contener las clases de utilidad que usa
Filament: no puede conocer las de *tus* vistas Blade, porque esos archivos no
existen cuando se compila el tema. Si escribes utilidades de Tailwind en tus
vistas, compílalas tú y entrega el punto de entrada:

```php
->plugin(
    MiaTheme::make()->viteStylesheets('resources/css/filament/admin/utilities.css'),
)
```

```css
@import 'tailwindcss/theme.css' layer(theme);
@import 'tailwindcss/utilities.css' layer(utilities);

@source '../../../../app/Filament';
@source '../../../../resources/views/filament';
```

Solo se importan las capas de tema y utilidades: importar `tailwindcss`
completo volvería a aplicar Preflight sobre la capa base del tema. La hoja se
emite después del tema y junto a él. No uses `Panel::viteTheme()` para esto,
porque reemplazaría el tema por completo.

## Accesibilidad

El contraste se calcula con la propia aritmética de color de Filament y se
verifica en la suite de tests, así que un cambio en las rampas que rompiera la
accesibilidad falla la build.

Los pares de texto superan el 4.5:1 que WCAG AA exige al cuerpo de texto, y los
controles superan el 3:1 que WCAG 1.4.11 exige a los componentes de interfaz.
Los capilares decorativos quedan por debajo a propósito: no transportan
información, y WCAG 1.4.11 exime explícitamente a los elementos que no lo
hacen. La tabla completa está en el [README en inglés](README.md#accessibility).

Además: el foco siempre es visible (dos capas, para que se lea sobre
superficies claras, oscuras y botones de color), las acciones de fila nunca se
ocultan hasta el hover (ocultarlas las saca de la navegación por teclado y las
vuelve inalcanzables en táctil), `prefers-reduced-motion` se respeta en todo, y
las cifras usan figuras tabulares con cero barrado.

## Vistas de Filament sobreescritas

**Ninguna.** El tema está implementado por completo en CSS y un único render
hook (`PanelsRenderHook::STYLES_AFTER`, para emitir las propiedades
personalizadas en tiempo de ejecución). No se publica ni se reemplaza ninguna
vista Blade, así que una actualización de Filament no puede revertir en
silencio a una copia antigua de una plantilla del framework.

## Licencia

MIT. Ver [LICENSE.md](LICENSE.md).
