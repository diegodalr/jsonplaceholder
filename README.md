# jsonplaceholder

# Módulo
Se crea el módulo jsonplaceholder para crear las páginas que serán llamadas por ajax

# Página inicial
http://dev-json-placeholder.pantheonsite.io/json-placeholder/links
Se verán 2 enlaces "Get posts" y "Get photos" para consultar por cada contenido.

# Dependencias
Se usa el módulo https://www.drupal.org/project/jcarousel para construir el slider de imágenes.

# Construcción del módulo
Se crea un servicio para consumir el WS con el contenido necesario.
modules/custom/json_placeholder/src/JsonPlaceholderService.php
Se crea un solo controlador para 3 páginas: enlaces, posts y photos, estas 2 últimas son llamadas por ajax.
