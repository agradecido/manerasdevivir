# Paginación en el tema Maneras

Este documento explica cómo funciona la paginación en el tema Maneras y cómo reutilizarla en distintas partes del sitio.

## Trait WithPagination

El tema incluye un trait `WithPagination` que proporciona funcionalidad reutilizable para la paginación. Este trait está ubicado en `app/Traits/WithPagination.php`.

### Características principales:

- Métodos reutilizables para obtener posts paginados
- Almacenamiento automático de la última consulta para su reutilización
- Generación de datos de paginación consistentes para plantillas Twig

### Cómo usar el trait

1. **Importar el trait en tu controlador**:

```php
use ManerasTheme\Traits\WithPagination;

class MiControlador extends Controller {
    use WithPagination;
    
    // Resto del código del controlador
}
```

2. **Obtener posts paginados**:

```php
public function mis_posts() {
    return $this->get_paginated_posts(
        array(
            'post_type'      => 'article',
            'posts_per_page' => 10,
            // Otros parámetros de WP_Query
        )
    );
}
```

3. **Obtener datos de paginación**:

```php
public function paginacion() {
    return $this->get_pagination_data();
}
```

4. **Usar una WP_Query existente**:

```php
public function __construct() {
    global $wp_query;
    $this->last_query = $wp_query; // Guardar la consulta principal
}

public function paginacion() {
    return $this->get_pagination_data($wp_query);
}
```

## Ejemplos de implementación

### FrontPage

Consulta el archivo `app/Controllers/FrontPage.php` para ver cómo se implementa la paginación en la página principal del sitio.

### Archive

Consulta el archivo `app/Controllers/Archive.php` para ver cómo se implementa la paginación en las páginas de archivo.

### Search

Consulta el archivo `app/Controllers/Search.php` para ver cómo se implementa la paginación en los resultados de búsqueda.

## En plantillas Twig

Para utilizar la paginación en tus plantillas Twig:

```twig
{% if pagination.total > 1 %}
  <nav class="site-pagination" aria-label="Paginación">
    {% if pagination.prev_link %}
      <a href="{{ pagination.prev_link }}">Anterior</a>
    {% endif %}
    
    <span class="current">{{ pagination.current }}</span>
    
    {% if pagination.total > 1 %}
      <span>de {{ pagination.total }}</span>
    {% endif %}
    
    {% if pagination.next_link %}
      <a href="{{ pagination.next_link }}">Siguiente</a>
    {% endif %}
  </nav>
{% endif %}
```

## Consideraciones

- La paginación utiliza los enlaces estándar de WordPress (`get_previous_posts_page_link()` y `get_next_posts_page_link()`).
- El trait maneja automáticamente la detección de la página actual.
- Recuerda inicializar `$this->last_query = $wp_query;` en el constructor si estás trabajando con la consulta principal de WordPress.
