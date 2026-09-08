<div align="center">

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/mia-avatar-512.png" alt="" width="132" />

# Mía

### Un tema editorial y cálido para Filament v5

Para paneles que forman parte del producto y no un añadido detrás de un<br />
login. Crema y champán en claro, espresso en oscuro, titulares en serif.

[![Estado](https://img.shields.io/badge/estado-v0.x%20%C2%B7%20desarrollo%20activo-D9A14E?style=flat-square&labelColor=3C3227)](#estado-del-proyecto)
[![Licencia](https://img.shields.io/badge/licencia-MIT-D9A14E?style=flat-square&labelColor=3C3227)](LICENSE.md)
[![PHP](https://img.shields.io/badge/PHP-8.4%20%E2%80%93%208.5-777BB4?style=flat-square&labelColor=3C3227)](https://www.php.net)
[![Laravel](https://img.shields.io/badge/Laravel-11%20%C2%B7%2012%20%C2%B7%2013-FF2D20?style=flat-square&labelColor=3C3227)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-v5.7%2B-F59E0B?style=flat-square&labelColor=3C3227)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-v4.3-06B6D4?style=flat-square&labelColor=3C3227)](https://tailwindcss.com)
[![Idiomas](https://img.shields.io/badge/idiomas-en%20%C2%B7%20es-8B9FB0?style=flat-square&labelColor=3C3227)](#idiomas)

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

## Capturas

Todas las imágenes salen del panel de previsualización que acompaña al paquete,
con datos inventados sembrados a partir de una semilla fija. Al clonar el
repositorio, dos órdenes reproducen el conjunto entero, capturas de esta página
incluidas: una galería que necesita otra aplicación para regenerarse deja de
corresponder al código que anuncia.

### Antes de entrar

La pantalla de acceso es lo único que ve un visitante sin cuenta, así que el
tema trae cinco puestas en escena. Se diferencian en la composición, no en la
identidad, y se elige en una línea de configuración o desde la página de
apariencia.

<table>
<tr>
<td width="50%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-card-light-desktop.jpg" alt="Composición de tarjeta centrada en modo claro" /></td>
<td width="50%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-split-light-desktop.jpg" alt="Composición de pantalla partida en modo claro" /></td>
</tr>
<tr>
<td><b>Tarjeta centrada</b><br />Una tarjeta sobre el lienzo, con dos halos de luz cálida.</td>
<td><b>Pantalla partida</b><br />Dos columnas, una de ellas territorio de marca.</td>
</tr>
<tr>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-bleed-light-desktop.jpg" alt="Composición de fondo a sangre en modo claro" /></td>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-editorial-light-desktop.jpg" alt="Composición editorial en modo claro" /></td>
</tr>
<tr>
<td><b>Fondo a sangre</b><br />Campo cálido a todos los bordes, con el panel tendido a lo ancho sobre cristal.</td>
<td><b>Editorial</b><br />Asimétrica y de imprenta, con el costado opuesto en aire.</td>
</tr>
<tr>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-portal-light-desktop.jpg" alt="Composición de portal en modo claro" /></td>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-split-dark-desktop.jpg" alt="Composición de pantalla partida en modo oscuro" /></td>
</tr>
<tr>
<td><b>Portal</b><br />Columna estrecha y alta, con medallón de marca y sin borde de tarjeta.</td>
<td><b>Modo oscuro</b><br />La misma composición en la paleta cálida oscura.</td>
</tr>
</table>

Cada composición aguanta los estados que de verdad ocurren: un error de
validación, un teléfono, un desafío en dos pasos.

<table>
<tr>
<td width="25%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-split-light-mobile.jpg" alt="Composición de pantalla partida en un teléfono, con la columna de marca plegada en una franja" /></td>
<td width="37%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-card-light-desktop-error.jpg" alt="Composición de tarjeta centrada con un error de validación" /></td>
<td width="38%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/login-two-step-split-dark.jpg" alt="Desafío en dos pasos en la composición de pantalla partida, modo oscuro" /></td>
</tr>
<tr>
<td><b>Plegada</b><br />La columna de marca se vuelve una franja.</td>
<td><b>Credenciales incorrectas</b><br />El mensaje ocupa una línea; nada se recoloca.</td>
<td><b>Dos pasos</b><br />El desafío conserva la puesta en escena.</td>
</tr>
</table>

### Dentro del panel

<table>
<tr>
<td width="50%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-dashboard-light.jpg" alt="Un panel principal en modo claro: lienzo crema, titulares en serif y widgets sobre tarjetas con borde de un pelo" /></td>
<td width="50%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-dashboard-dark.jpg" alt="El mismo panel en modo oscuro, en espresso y umbra cálidos en lugar de gris frío" /></td>
</tr>
<tr>
<td><b>Panel principal</b><br />Widgets sobre superficies apenas diferenciadas y cifras compuestas en la serif de titulares.</td>
<td><b>El mismo, oscuro</b><br />Espresso y umbra, construidos con la misma rampa neutra que el modo claro.</td>
</tr>
<tr>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-table-light.jpg" alt="Una tabla de proyectos: versalitas espaciadas en los encabezados, cifras tabulares y distintivos teñidos" /></td>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-table-dark.jpg" alt="La misma tabla en modo oscuro" /></td>
</tr>
<tr>
<td><b>Tablas</b><br />Encabezados en versalitas espaciadas, cifras tabulares y distintivos como lavados de color.</td>
<td><b>La densidad es un ajuste</b><br />La altura de fila y el relleno siguen a <code>density()</code>.</td>
</tr>
<tr>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-form-light.jpg" alt="Un formulario de edición con secciones, desplegables, selector de fecha y campos con borde de un pelo" /></td>
<td><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-charts-dark.jpg" alt="Dos widgets de gráfico en modo oscuro, con rejilla, ejes y etiquetas en la paleta cálida del tema" /></td>
</tr>
<tr>
<td><b>Formularios</b><br />Los campos llevan un borde de un pelo y un halo suave al enfocarse, en lugar del anillo de Filament.</td>
<td><b>Gráficos</b><br />La rejilla, los ejes y la leyenda también toman la paleta, no solo las series.</td>
</tr>
</table>

<table>
<tr>
<td width="34%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-chart-tooltip-light.jpg" alt="Un tooltip de gráfico: una pastilla cálida oscura con esquinas redondeadas" /></td>
<td width="33%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-dashboard-light-mobile.jpg" alt="El panel principal en un teléfono, con la barra lateral plegada" /></td>
<td width="33%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-table-light-mobile.jpg" alt="La tabla de proyectos en un teléfono, con desplazamiento horizontal" /></td>
</tr>
<tr>
<td><b>Tooltip de gráfico</b><br />La misma pastilla cálida que el resto, con las esquinas de <code>roundness()</code>.</td>
<td><b>En un teléfono</b><br />La barra lateral se pliega y el diseño conserva su aire.</td>
<td><b>Tablas en un teléfono</b><br />Desplazamiento horizontal, con el tratamiento del encabezado intacto.</td>
</tr>
</table>

El estado vacío es una pantalla que casi todo panel encuentra y casi ninguno
diseña. Este es una lista vaciada por una búsqueda, no por no tener nada: la
versión que necesita una vuelta atrás y no un punto de partida.

<div align="center">
<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-table-empty-light.jpg" alt="Una tabla de proyectos con una búsqueda que no encuentra nada: una marca ilustrada en un halo cálido, un titular que dice Nothing matches y una acción para volver a verlo todo" width="860" />
</div>

### La página de apariencia

Color, tipografía, redondez, densidad y elevación, editados dentro del panel
con previsualización en vivo. La composición de acceso se elige aquí también, y
se previsualiza como el layout real y no como un esquema.

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-appearance-login.jpg" alt="Sección de acceso de la página de apariencia, con las cinco composiciones y una previsualización en vivo" width="100%" />

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-appearance-light.jpg" alt="La página de apariencia: controles de color, tipografía, forma y profundidad junto a una muestra de los componentes que afectan" width="100%" />

## Estado del proyecto

Mía es un proyecto joven en desarrollo activo, hoy en la serie `0.x`.

Qué significa eso en la práctica. El tema está completo y es utilizable: los
modos claro y oscuro están terminados, la API de configuración es lo bastante
estable como para construir sobre ella, la hoja de estilos se distribuye
precompilada y la batería de pruebas corre contra PHP 8.4 y 8.5. Lo que no
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
| PHP | 8.4 u 8.5 |
| Laravel | 11.28, 12 o 13 |
| Filament | 5.7 o superior |

La restricción es `^8.4`. Las dos versiones del rango están probadas, no
supuestas: cada envío ejecuta la suite en 8.4 y 8.5, con las dependencias más
antiguas y las más nuevas que se puedan resolver, y con `error_reporting=-1`,
de modo que una deprecación, aviso o advertencia originada en el paquete
detiene la construcción. Las de Laravel o Filament se ignoran, porque no dicen
nada sobre este paquete.

El renderizado de un panel real también se ejercitó bajo PHP 8.5.8 con el mismo
nivel de errores, que la suite por sí sola no cubre.

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

## Idiomas

El tema trae inglés y español, y puede poner un conmutador de idioma en el menú
del usuario, debajo del interruptor de claro y oscuro.

<table>
<tr>
<td width="50%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/locale-menu-en.jpg" alt="El menú del usuario abierto, con el interruptor de claro y oscuro sobre English y Español, y English marcado como el actual" /></td>
<td width="50%"><img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/locale-menu-es.jpg" alt="El mismo panel tras elegir Español, con la página, la navegación y los elementos propios de Filament en español" /></td>
</tr>
<tr>
<td><b>El conmutador</b><br />Un elemento por idioma, en el nombre del propio idioma, junto al de claro y oscuro.</td>
<td><b>Elegido</b><br />El panel entero sigue la elección, incluido el texto propio de Filament.</td>
</tr>
</table>

### Qué está traducido

El tema solo rotula una superficie: la [página de
apariencia](#la-página-de-apariencia), con los nombres y las descripciones de
los preajustes incluidos. Está traducida por completo a los dos idiomas, y la
paridad de claves entre los dos archivos se comprueba en la suite de tests. El
resto del tema no
lleva texto: las composiciones de acceso muestran tu marca y tu propia frase, y
el conmutador nombra cada idioma en ese idioma, algo que a propósito no se
traduce.

Todo lo demás de un panel viene de otra parte, y el conmutador también lo
cambia:

- **El texto propio de Filament** —titulares, botones, mensajes de tablas y
  formularios— existe en más de sesenta idiomas, español entre ellos.
- **Tus recursos, páginas y campos** los traduces tú. Un conmutador sobre texto
  sin traducir deja el panel a medias, que se lee peor que un solo idioma en
  todo. Compruébalo antes de activarlo.

### Cómo se activa

```php
->plugin(
    MiaTheme::make()->localeSwitcher(['en', 'es']),
)
```

Los códigos deben coincidir con los directorios de tu carpeta `lang`. Cada
idioma se rotula con su propio nombre; pasa una etiqueta para cambiar alguno:

```php
MiaTheme::make()->localeSwitcher(['en' => 'English (US)', 'es', 'pt_BR'])
```

Es una lista y no un botón que alterna, para que el nombre de cada opción esté
siempre a la vista —que es justo lo que hace falta cuando alguien no puede leer
el idioma en el que está la interfaz— y para que añadir un tercer idioma no
cambie nada de cómo funciona.

La elección se guarda en una cookie de larga duración, `filament_mia_locale`,
escrita por el gestor de cookies de Laravel como cualquier otra. Ahí vive
también la elección de claro y oscuro, y por el mismo motivo: pertenece al
navegador, no a la sesión. Sobrevive a una recarga, a otra página, a una sesión
caducada y a cerrar sesión, así que quien eligió español ayer se encuentra hoy
la pantalla de acceso en español.

Un límite que conviene decir: el conmutador vive en el menú del usuario, que no
existe antes de entrar. Quien llega por primera vez ve la pantalla de acceso en
el idioma por defecto de la aplicación. Un panel que necesite elegir el idioma
desde la propia pantalla de acceso debería fijar el locale desde la URL o la
petición, que es trabajo de la aplicación y no del tema.

<div align="center">
<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/locale-login-es.jpg" alt="La pantalla de acceso en español, con las etiquetas propias de Filament traducidas, después de descartar la sesión" width="720" />
</div>

### Añadir un idioma

No hay que contribuir nada al paquete. Las traducciones de paquete se pueden
sobreescribir por aplicación, así que un cuarto o un cuadragésimo idioma es una
carpeta en tu proyecto:

```
lang/vendor/filament-mia/fr/customizer.php
```

Copia `vendor/johnrivera7/filament-mia/resources/lang/en/customizer.php` como
punto de partida, o publica primero los dos idiomas incluidos:

```bash
php artisan vendor:publish --tag=filament-mia-translations
```

Y luego ofrécelo:

```php
MiaTheme::make()->localeSwitcher(['en', 'es', 'fr'])
```

También querrás las traducciones propias de Filament para ese idioma, que se
publican con `php artisan vendor:publish --tag=filament-translations`.

### Cómo se desactiva

Está desactivado hasta que lo pides, y `localeSwitcher(false)` vuelve a
apagarlo, lo que sirve para desactivarlo en un panel cuando el archivo de
configuración lo activa en todos.

Desactivado por defecto a propósito. Fijar el locale no es una decisión visual:
cambia el texto de Filament, el de tu aplicación y cualquier otra cosa que lea
`app()->getLocale()` durante la petición. Muchas aplicaciones ya deciden el
idioma desde el registro del usuario, el subdominio o la cabecera
`Accept-Language`, e instalar un tema no debería apropiarse de eso en silencio.

Cuando sí lo activas, esto es lo que el tema toca y lo que no:

- El locale lo aplica un middleware **registrado solo en ese panel**. El resto
  de las rutas de tu aplicación, y cualquier panel con el conmutador apagado,
  quedan intactos.
- El middleware se añade *después* de los que registra tu panel, así que dentro
  de ese panel gana la elección de quien lo usa por encima de un `setLocale()`
  anterior. Es justo el sentido de activarlo. Si tu propia lógica debe ganar,
  deja el conmutador apagado o registra tu middleware en el panel después del
  plugin.
- No se aplica nada hasta que alguien elige un idioma. Sin la cookie, el tema
  no llama a `setLocale()` en absoluto, y un panel al que no se le ha tocado
  nada se comporta igual que antes.
- La cookie se valida contra los idiomas que ofrece ese panel, así que un valor
  escrito por otro panel se ignora en lugar de darse por bueno.

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

**Ninguna.** El tema está implementado en CSS y dos render hooks:

- `PanelsRenderHook::STYLES_AFTER` emite las propiedades personalizadas en
  tiempo de ejecución.
- `PanelsRenderHook::SIMPLE_LAYOUT_START` emite el elemento marcador que
  selecciona una [composición de acceso](#composiciones-de-la-pantalla-de-acceso)
  y el escenario de marca de las dos que lo usan.

No se publica ni se reemplaza ninguna vista Blade, así que una actualización de
Filament no puede revertir en silencio a una copia antigua de una plantilla del
framework. El layout simple es, además, uno de los archivos que más cambia
entre versiones, y una copia publicada dejaría de seguir a la original sin
avisar.

El [conmutador de idioma](#idiomas) es el mismo argumento por el otro lado:
aparece en el menú del usuario a través de `Panel::userMenuItems()`, el punto de
extensión que Filament ofrece para ese menú, y no publicando su vista.

## Composiciones de la pantalla de acceso

La pantalla de acceso es lo único de un panel que ve alguien sin cuenta, así
que el tema trae cinco puestas en escena en lugar de una. Lo que cambia es la
composición —dónde vive el formulario y qué ocupa el resto de la pantalla—, no
la identidad: paleta, tipografía y tratamiento de formas son idénticos en las
cinco.

| Valor | Composición |
|---|---|
| `card` | Una tarjeta centrada sobre el lienzo, con dos halos de luz cálida. La más serena, y la que viene por defecto. |
| `split` | Dos columnas. Una es territorio de marca —un campo cálido y profundo con el logotipo, el nombre del panel y una línea de texto opcional— y la otra lleva el formulario, sin tarjeta, sobre el lienzo crema. |
| `bleed` | Un campo cálido que llega a todos los bordes. El panel se tiende *a lo ancho* sobre cristal esmerilado: marca y encabezado en una mitad, campos en la otra, separados por un capilar. |
| `editorial` | Asimétrica y de imprenta. El formulario se ancla a un costado sin tarjeta alrededor, y el costado opuesto queda en aire con el nombre del panel a tamaño de titular. |
| `portal` | Una columna estrecha y alta, centrada, con un medallón de marca sobre el encabezado y sin borde de tarjeta. El lienzo degrada en vertical. |

Se elige al registrar el plugin:

```php
->plugin(
    MiaTheme::make()
        ->loginLayout('split')
        ->loginTagline('Client work, kept in one place.'),
)
```

O desde la [página de apariencia](#la-página-de-apariencia), que previsualiza
la elección antes de guardarla. La previsualización es el layout real, no un
esquema: renderiza el mismo marcado con la misma hoja de estilos.

La elección se aplica a todo el flujo de autenticación. Registro, recuperación
de contraseña y el desafío en dos pasos toman la misma puesta en escena, así
que el panel no cambia de forma entre escribir la contraseña y confirmar un
código.

**No hace falta configurar nada.** Sin ningún ajuste, el panel recibe la
composición `card`, que ya está lejos de la caja por defecto de Filament.

### Qué pasa en el móvil

Las composiciones a dos columnas son las que se rompen en pantallas estrechas,
así que cada una declara qué hace:

- `split` pliega la columna de marca a una franja sobre el formulario, con el
  logotipo, el nombre y la regla de acento, y suelta la línea de texto y la
  rama botánica, que necesitan ancho para no leerse como ruido.
- `editorial` suelta el costado opuesto por completo: es ornamento, y apilarlo
  bajo el formulario solo añadiría desplazamiento.
- `bleed` vuelve a una sola columna, y el cristal vuelve a ser tarjeta.
- `card` y `portal` ya son de una columna.

Las composiciones con tarjeta conservan su radio en anchos de móvil, con un
margen pequeño donde apoyarlo, ahí donde Filament la lleva de borde a borde.

### Accesibilidad de las composiciones

El contraste de estas pantallas se mide sobre los píxeles renderizados, no se
calcula desde la paleta. Dos composiciones ponen texto sobre un degradado y una
lo pone sobre cristal esmerilado, y una razón calculada contra un fondo nominal
no sería la medida de nada.

`bin/contrast-login.mjs` recorre las cinco en ambos modos de color, con un
error de validación en pantalla, y de cada texto toma el color computado,
oculta los glifos, fotografía la caja que ocupaban y promedia lo que hay
detrás. 48 pares, todos por encima de WCAG AA.

Esa medición es también la que detectó los dos fallos que ahora evita: el texto
atenuado y las acciones de enlace quedaban en torno a 4.1:1 sobre el crema una
vez pintados los degradados cálidos del fondo, mientras pasaban con holgura
contra el fondo plano que asume el informe de paleta.

## La página de apariencia

<img src="https://raw.githubusercontent.com/Johnrivera7/filament-mia/main/art/panel-appearance-login.jpg" alt="Sección de acceso de la página de apariencia, con las cinco composiciones y una previsualización en vivo" width="100%" />

Una página opcional dentro del panel para editar el tema y guardar el
resultado, pensada para cuando quien decide cómo se ve el panel no es quien lo
despliega.

Ajusta los colores de acento, secundario y de estado; las familias de interfaz
y de titulares, desde una lista comprobada de Bunny Fonts; la redondez, la
densidad y la elevación; la [composición de la pantalla de
acceso](#composiciones-de-la-pantalla-de-acceso) y su línea de texto; e incluye
cinco preajustes, entre ellos el tema tal como se distribuye. Debajo del
formulario hay una muestra de los componentes a los que más afectan los
ajustes.

La composición de acceso es el único ajuste cuyo resultado no se ve desde la
página, porque cambia una pantalla que solo ven quienes no han entrado, así que
tiene su propia previsualización en vivo. Esa previsualización renderiza el
layout simple real con el marcador real, bajo la hoja de estilos compilada —no
un esquema— y se redibuja al cambiar la elección sin esperar una ida y vuelta
al servidor.

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
estados vacíos ilustrados, estados de carga, contraste medido, una página de
apariencia dentro del panel que edita y persiste todo lo anterior, e inglés y
español con un conmutador opcional.

**A continuación.** Una aplicación de demostración que sirva además como origen
de todas las capturas. Más preajustes distribuidos como paletas con nombre. Más
idiomas incluidos, según lo que se pida.

**Más adelante, y deliberadamente más vago porque no está construido.**
Componentes Blade que usen las variables directamente, para construir páginas
propias que encajen con el panel. Exportar una apariencia guardada de vuelta a
configuración, para poder versionar en el repositorio un ajuste hecho en un
entorno. Cobertura para los plugins de Filament que traen su propia interfaz.

No se prometen fechas. La serie `0.x` es donde esto se resuelve a la vista de
todos; ver [Estado del proyecto](#estado-del-proyecto).

## Licencia

MIT. Ver [LICENSE.md](LICENSE.md).
