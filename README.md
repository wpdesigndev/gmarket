<p align="center">
  <img src="docs/img/logo.svg" alt="GMarket" width="110">
</p>

<h1 align="center">GMarket</h1>

<p align="center">
  <strong>Tu tienda online, con aspecto de marketplace.</strong><br>
  Tema de WordPress para tiendas WooCommerce.
</p>

<p align="center">
  <a href="../../releases/latest"><strong>⬇ Descargar la última versión</strong></a>
  &nbsp;·&nbsp; <a href="https://wpdesigndev.github.io/gmarket/">🌐 Web del tema</a>
  &nbsp;·&nbsp; <a href="#english">English</a>
  &nbsp;·&nbsp; Desarrollado por <a href="https://gmeti.com">GMETI</a>
</p>

---

![Portada de una tienda con GMarket](screenshot.png)

## Qué incluye

- **Cabecera completa:** buscador por categorías con sugerencias en vivo (foto y precio), cuenta, pedidos, carrito y selector de idioma.
- **Menú lateral de categorías** con subcategorías y enlaces de ayuda.
- **Filtros de tienda** por precio, atributos, valoración, disponibilidad y ofertas, con recuentos reales.
- **Tarjetas de producto uniformes** y **ficha de producto** con bloque de ventajas (envío, devoluciones, pago seguro).
- **Bloques de portada** mediante shortcodes.
- **Panel de textos:** todos los textos de la tienda se editan en **Apariencia › Textos de la tienda**. Si dejas un campo vacío, ese elemento se oculta.
- **Colores y logo del pie** en **Apariencia › Personalizar › GMarket**.
- **Compatible** con Elementor Header & Footer Builder, TranslatePress (segundo idioma) y YITH WooCommerce Wishlist. El tema funciona sin ellos.

## Instalación

1. Descarga **`gmarket.zip`** desde [la última versión](../../releases/latest) (el archivo adjunto, no "Source code").
2. En WordPress: **Apariencia → Temas → Añadir nuevo tema → Subir tema**, elige el zip y actívalo.
3. Instala **WooCommerce** (el aviso del escritorio te da el enlace).
4. Cambia los textos en **Apariencia › Textos de la tienda** y los colores en **Apariencia › Personalizar › GMarket**.

**Requisitos:** WordPress 6.4+, PHP 7.4+ y WooCommerce para la tienda.

## Shortcodes

Portada: `[gmk_categories]` `[gmk_tiles cats="slug1,slug2"]` `[gmk_row title="..." cat="slug" type="featured|sale|new" limit="10" link=""]` `[gmk_link page="shop" text="..."]` `[gmk_email]`

Cabecera y pie (Elementor): `[gmk_burger] [gmk_deliver] [gmk_search] [gmk_lang] [gmk_account] [gmk_orders] [gmk_cart] [gmk_nav] [gmk_menu location="footer_1"] [gmk_footer_cats] [gmk_pay_icons] [gmk_year] [gmk_breadcrumb]`

### Pie hecho con Elementor
Si diseñas el pie con Elementor Header & Footer Builder, pon estas clases CSS (Avanzado › Clases CSS) para que tome el estilo del tema:
`gmk-ftr` (contenedor del pie), `gmk-ftr__top` (título «volver arriba»), `gmk-ftr__trust` (franja de 4 ventajas), `gmk-ftr__cols` (columnas), `gmk-ftr__brand` (columna del logo), `gmk-ftr__notice` (aviso), `gmk-ftr__bottom`, `gmk-ftr__pay` (iconos de pago) y `gmk-ftr__copy` (derechos).

## Traducción

Plantilla en `languages/gmarket.pot`. Ábrela con Poedit (o usa Loco Translate) y guarda, por ejemplo, `gmarket-en_US.po`/`.mo` en esa carpeta.

## Actualizaciones

El tema se actualiza solo desde este repositorio: cuando se publica una versión nueva, aparece el aviso en **Apariencia › Temas** y se actualiza con un clic. Solo consulta qué versión es la última (como mucho cada 6 horas); no envía datos de la tienda ni de los clientes.

## Soporte

Abre un [issue](../../issues).

## Licencia

GPLv2 o posterior (ver `LICENSE`). Iconos de pago: Font Awesome Free (CC BY 4.0). Fuente Inter (SIL OFL).

---

<a id="english"></a>

## English

**GMarket** is a WordPress theme for marketplace-style WooCommerce stores.

- Header with category search and **live suggestions**, account, orders, cart and language switcher.
- Side **category drawer**, **shop filters** (price, attributes, rating, stock, sale) with real counts.
- Even **product cards**, a product page with a perks box, and **home page blocks** via shortcodes.
- Every store text editable in *Appearance › Store texts*; colors and footer logo in *Appearance › Customize › GMarket*.
- Works with Elementor Header & Footer Builder, TranslatePress and YITH WooCommerce Wishlist (all optional). Translation-ready (`languages/gmarket.pot`).

**Install:** download `gmarket.zip` from the [latest release](../../releases/latest), then *Appearance → Themes → Add New Theme → Upload Theme*, and install WooCommerce. Updates arrive automatically from this repository.

Developed by [GMETI](https://gmeti.com) · License: GPLv2 or later.
