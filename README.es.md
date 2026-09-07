<div align="center">

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/mia-avatar-512.png" alt="" width="132" />

# Mía

### Un tema editorial y cálido para Filament v5

Para paneles que forman parte del producto y no un añadido detrás de un<br />
login. Crema y champán en claro, espresso en oscuro, titulares en serif.

[![Estado](https://img.shields.io/badge/estado-v0.x%20%C2%B7%20desarrollo%20activo-D9A14E?style=flat-square&labelColor=3C3227)](#estado-del-proyecto)
[![Licencia](https://img.shields.io/badge/licencia-MIT-D9A14E?style=flat-square&labelColor=3C3227)](LICENSE.md)
[![PHP](https://img.shields.io/badge/PHP-8.2%20%E2%80%93%208.5-777BB4?style=flat-square&labelColor=3C3227)](https://www.php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11%20%C2%B7%2012%20%C2%B7%2013-FF2D20?style=flat-square&labelColor=3C3227)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-v5.7%2B-F59E0B?style=flat-square&labelColor=3C3227)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-v4.3-06B6D4?style=flat-square&labelColor=3C3227)](https://tailwindcss.com)

**English version: [README.md](README.md)**

</div>

## Qué es

Mía no es un cambio de paleta. Pasarle un color de acento a los estilos por
defecto de Filament cambia el tono y deja intacto el resto del diseño. Aquí
cada superficie está ajustada a mano —escala tipográfica, espaciado, jerarquía,
bordes, sombras, movimiento, anillos de foco, estados vacíos— y la hoja de
estilos reescribe la capa de componentes en lugar de teñirla. Tres cosas
sostienen el resultado.

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

## Estado del proyecto

Mía es un proyecto joven en desarrollo activo, hoy en la serie `0.x`.

Qué significa eso en la práctica. El tema está completo y es utilizable: los
modos claro y oscuro están terminados, la API de configuración es lo bastante
estable como para construir sobre ella, la hoja de estilos se distribuye
precompilada y la batería de pruebas corre contra PHP 8.2 a 8.5. Lo que no
significa es que la superficie esté congelada. Hasta la `1.0`, los nombres de
las opciones, las propiedades CSS y el conjunto de componentes reestilizados
pueden cambiar, y una versión menor puede traer cambios incompatibles. Cada uno
queda anotado en el [registro de cambios](CHANGELOG.md).

La iteración es frecuente y la retroalimentación la orienta. Si un componente
se ve mal en tu panel, o falta una variable que necesitas, abre una incidencia:
es la vía más rápida para influir en lo que entra a continuación.

## Requisitos

| | |
|---|---|
| PHP | 8.2 – 8.5 |
| Laravel | 11.28, 12 o 13 |
| Filament | 5.7 o superior |

PHP 8.5 es compatible, no obligatorio. La restricción es `^8.2`, el mínimo que
acepta Filament v5, para que el paquete instale en el PHP que todavía usan la
mayoría de los proyectos Laravel. Ten en cuenta que Laravel 13 exige PHP 8.3 o
superior por su cuenta.

La compatibilidad con 8.5 está verificada, no supuesta: la suite completa y el
renderizado de un panel real se ejercitaron bajo PHP 8.5.8 con
`error_reporting=-1`, fallando ante cualquier deprecación, aviso o advertencia
originada en el paquete. Las deprecaciones de Laravel o Filament se ignoran,
porque no dicen nada sobre este paquete.

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

## La página de apariencia

Una página opcional dentro del panel para editar el tema y guardar el
resultado, pensada para cuando quien decide cómo se ve el panel no es quien lo
despliega.

Ajusta los colores de acento, secundario y de estado; las familias de interfaz
y de titulares, desde una lista comprobada de Bunny Fonts; la redondez, la
densidad y la elevación; e incluye cinco preajustes, entre ellos el tema tal
como se distribuye. Debajo del formulario hay una muestra de los componentes a
los que más afectan los ajustes.

Está desactivada por defecto, porque reescribe el panel para todo el mundo que
lo usa:

```php
->plugin(
    MiaTheme::make()
        ->customizer()
        ->customizerAuthorization(fn (): bool => auth()->user()?->isAdmin() ?? false)
        ->customizerNavigation(group: 'Ajustes'),
)
```

**La previsualización es el resultado.** Cada control escribe una propiedad
personalizada que la hoja de estilos compilada ya lee, y el mismo código pinta
la previsualización y el panel guardado, así que lo que se ve antes de guardar
es en lo que se convierte el panel después. Nada en la página puede generar una
clase de Tailwind: esa es la restricción que hace configurable un tema
precompilado.

**Dónde se guarda, y para quién.** Por panel, compartido por todos los que lo
usan. Cómo se ve un panel es una propiedad del panel, igual que su logotipo, no
una preferencia de cada persona. La excepción es el modo claro y oscuro, que
Filament ya guarda por navegador y que la página solo ofrece para previsualizar
ambos.

Los registros se escriben como JSON en `storage/app/filament-mia/`, uno por
panel, para que el paquete se instale en un proyecto existente sin migraciones.
Si necesitas base de datos, caché compartida o almacenamiento por usuario,
enlaza tu propia implementación del contrato `SettingsRepository`, que son tres
métodos.

**Precedencia.** Un registro guardado gana sobre el archivo de configuración y
sobre la API fluida: es la decisión deliberada más reciente. La acción de
restablecer lo descarta y devuelve el panel a tu código. Un registro guardado
se aplica esté o no activada la página, así que desactivarla congela la
apariencia en lugar de revertirla. Un registro editado a mano hasta quedar
inválido se ignora en vez de lanzar una excepción, para que un valor erróneo no
pueda dejarte fuera de la página que lo arreglaría.

## Hacia dónde va

Mía empieza como tema. La dirección es un sistema de diseño para Filament: la
hoja de estilos es la primera capa, no el conjunto.

En concreto, qué hay y qué no.

**Hoy.** Hoja de estilos precompilada, API de configuración para color,
tipografía, redondez, densidad y elevación, modos claro y oscuro cálidos,
estados vacíos ilustrados, estados de carga, contraste medido y una página de
apariencia dentro del panel que edita y persiste todo lo anterior.

**A continuación.** Una aplicación de demostración que sirva además como origen
de todas las capturas. Más preajustes distribuidos como paletas con nombre.

**Más adelante, y deliberadamente más vago porque no está construido.**
Componentes Blade que usen las variables directamente, para construir páginas
propias que encajen con el panel. Exportar una apariencia guardada de vuelta a
configuración, para poder versionar en el repositorio un ajuste hecho en un
entorno. Cobertura para los plugins de Filament que traen su propia interfaz.

No se prometen fechas. La serie `0.x` es donde esto se resuelve a la vista de
todos; ver [Estado del proyecto](#estado-del-proyecto).

## Licencia

MIT. Ver [LICENSE.md](LICENSE.md).
