# Maneras de Vivir – WordPress Bedrock Theme

> Migración y modernización del histórico **Manerasdevivir.com** a un stack Bedrock + Sage‑style (Blade, Tailwind, Vite).

## Tabla de contenidos

* [Requisitos](#requisitos)
* [Instalación rápida](#instalación-rápida)
* [Estructura del proyecto](#estructura-del-proyecto)
* [Workflow de desarrollo](#workflow-de-desarrollo)
* [Estilos y UI](#estilos-y-ui)
* [Convenciones de commit](#convenciones-de-commit)
* [Tests](#tests)
* [Licencia](#licencia)

---

## Requisitos

| Herramienta | Versión recomendada              |
| ----------- | -------------------------------- |
| PHP         | ≥ 8.1                            |
| Composer    | ≥ 2.5                            |
| Node & NPM  | Node 18 / npm 9                  |
| WP‑CLI      | Opcional, para comandos WP       |
| Docker      | Opcional, para entornos aislados |


## Instalación rápida

```bash
# 1. Clona el repo (después del fork si procede)
$ git clone git@github.com:agradecido/manerasdevivir.git && cd manerasdevivir

# 2. Instala dependencias PHP
$ composer install

# 3. Instala dependencias JS + Tailwind + Vite
$ npm install

# 4. Copia envs y configura credenciales
$ cp .env.example .env        # ajusta DB_*, WP_HOME, etc.

# 5. Arranca el site
$ wp server --host=localhost --port=8000   # o usa docker o tu stack LEMP/LAMP habitual
$ npm run dev                              # Vite modo watch + HMR
```

Accede a [http://localhost:8000](http://localhost:8000) y deberías ver la Home.

---

## Estructura del proyecto

```
├── helpers/               # Bootstrap Blade, Vite helper, utilidades
├── resources/
│   ├── css/               # Tailwind + capas base/components/pages
│   ├── js/                # app.js + Alpine/Stimulus (futuro)
│   ├── images/            # Imágenes fuente (logo, icons, etc.)
│   └── views/             # Blade templates
│       ├── layouts/       # app.blade.php, header, footer...
│       ├── partials/      # pequeños fragmentos reutilizables
│       └── …
├── src/
│   ├── Controllers/       # Endpoint REST o lógica CPT (si aplica)
│   ├── Helpers/           # Funciones PHP agrupadas
│   └── View/
│       ├── Composers/     # View‑Composers (SingleArticle, etc.)
│       └── ViewComposers.php  # Loader central
├── public/                # Assets construidos por Vite (no editar)
├── cache/                 # Plantillas Blade compiladas (git‑ignored)
└── tailwind.config.js     # Tokens y extensiones de tema
```

---

## Workflow de desarrollo

| Acción           | Comando                   | Nota                              |
| ---------------- | ------------------------- | --------------------------------- |
| Watch + HMR      | `npm run dev`             | Servidor Vite en `localhost:5173` |
| Build producción | `npm run build`           | Salida minificada en `public/`    |
| Limpiar Blade    | `wp acorn optimize:clear` | O se hace auto en `WP_DEBUG`      |
| Test PHP         | `composer test`           | PHPUnit (pendiente añadir)        |
| Test JS          | `npm test`                | Vitest (pendiente añadir)         |

> **¡Ojo!** `public/` actúa como `build.outDir` **y** `publicDir`; el warning de Vite es normal.

---

## Estilos y UI

* **Mobile‑first** con breakpoints Tailwind (`sm`, `md`, `lg`, `xl`).
* Paleta: `bg` #0d0d0d, `surface` #1E2428, `primary` #E56600, `text` #CCCCCC.
* Componentes base definidos en `resources/css/components/` y documentados en comentarios.
* Usa **Feather Icons** con `data-feather` y se reemplazan vía JS (`feather.replace()`).
* Variables y comentarios de código en **inglés**.

### Añadir un nuevo componente CSS

1. Crea `resources/css/components/_mi-componente.css`.
2. Añade reglas dentro de `@layer components { ... }`.
3. Importa en `app.css` **después** de `@tailwind utilities;`.
4. `npm run dev` y prueba.

---

## Convenciones de commit

* **Conventional Commits** (`feat:`, `fix:`, `docs:`, `style:`, `refactor:`…).
* Encabezado en inglés, presente imperativo.
* Título ≤ 72 chars; cuerpo opcionalmente en castellano.

Ejemplo:

```
feat(view): add composer for single‑article and centralized loader
```

---

## Tests

*Aún no configurados.* Plan: PHPUnit para funciones PHP y Vitest para JS.

---

## Licencia

MIT © agradecido / Manerasdevivir.com
