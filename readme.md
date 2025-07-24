
### Importante

- El proyecto esta desarrollado para PHP 8.4 o superior
- La base de datos utilizada de MySQL 8

Antes de comenzar ejecutar las migraciones:

`symfony console doctrine:migrations:migrate`

El ejemplo cuenta con datos de ejemplo:

`php bin/console doctrine:fixtures:load`

Iniciar servidor de desarrollo

`symfony server:start`
