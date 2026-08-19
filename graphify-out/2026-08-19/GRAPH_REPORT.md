# Graph Report - back-sistema-pedidos-3D  (2026-08-18)

## Corpus Check
- 82 files · ~12,260 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 344 nodes · 579 edges · 42 communities (36 shown, 6 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 7 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `2c23b6bc`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Illuminate\Http\Request
- Composer Package Config
- Pedido
- User
- Composer Scripts
- Plato Controller
- CLAUDE.md
- App Service Provider
- Users & Platos Migrations
- Cache & Mesas Migrations
- Jobs & Pedido Items Migrations
- Feature Test Example
- Sanctum Token Middleware
- App Bootstrap Config
- Illuminate\Support\Str
- Logging Config
- Unit Test Example
- Console Commands

## God Nodes (most connected - your core abstractions)
1. `Plato` - 26 edges
2. `Mesa` - 25 edges
3. `Empresa` - 23 edges
4. `Pedido` - 23 edges
5. `Controller` - 19 edges
6. `User` - 16 edges
7. `PlatoController` - 13 edges
8. `PedidoController` - 11 edges
9. `PedidoItem` - 11 edges
10. `Llamado` - 10 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `Empresa`  [EXTRACTED]
  database/migrations/2026_08_12_140002_seed_posiciones_mesas.php → app/Models/Empresa.php
- `up()` --calls--> `Mesa`  [EXTRACTED]
  database/migrations/2026_08_12_140002_seed_posiciones_mesas.php → app/Models/Mesa.php
- `CocinaController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Api/CocinaController.php → app/Http/Controllers/Controller.php
- `MetricaController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Api/MetricaController.php → app/Http/Controllers/Controller.php
- `PedidoController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Api/PedidoController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (42 total, 6 thin omitted)

### Community 0 - "Illuminate\Http\Request"
Cohesion: 0.08
Nodes (19): AuthController, EmpresaController, MenuController, MesaController, RegistroController, SSEController, App\Http\Controllers\Controller, Controller (+11 more)

### Community 1 - "Composer Package Config"
Cohesion: 0.05
Nodes (41): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+33 more)

### Community 2 - "Pedido"
Cohesion: 0.07
Nodes (14): CocinaController, MetricaController, Request, PedidoController, ArVista, Cupon, Llamado, Pedido (+6 more)

### Community 3 - "User"
Cohesion: 0.10
Nodes (14): App\Models\Mesa, App\Models\User, User, DatabaseSeeder, DemoEmpresaSeeder, EmpresaSeeder, MesaSeeder, PlatoSeeder (+6 more)

### Community 4 - "Composer Scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 5 - "Plato Controller"
Cohesion: 0.26
Nodes (3): PlatoController, Plato, self

### Community 7 - "App Service Provider"
Cohesion: 0.29
Nodes (4): AppServiceProvider, Illuminate\Cache\RateLimiting\Limit, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\ServiceProvider

### Community 11 - "Feature Test Example"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Testing\TestCase, ExampleTest, TestCase

### Community 12 - "Sanctum Token Middleware"
Cohesion: 0.50
Nodes (3): TokenFromQuery, Closure, Laravel\Sanctum\PersonalAccessToken

### Community 13 - "App Bootstrap Config"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware

### Community 14 - "Illuminate\Support\Str"
Cohesion: 0.22
Nodes (5): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Str, Pdo\Mysql, static

### Community 15 - "Logging Config"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

## Knowledge Gaps
- **47 isolated node(s):** `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader`, `preferred-install`, `sort-packages` (+42 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **6 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Mesa` connect `Illuminate\Http\Request` to `Pedido`, `User`?**
  _High betweenness centrality (0.087) - this node is a cross-community bridge._
- **Why does `Empresa` connect `Illuminate\Http\Request` to `Pedido`, `User`?**
  _High betweenness centrality (0.086) - this node is a cross-community bridge._
- **Why does `Plato` connect `Plato Controller` to `Illuminate\Http\Request`, `Pedido`, `User`?**
  _High betweenness centrality (0.055) - this node is a cross-community bridge._
- **Are the 2 inferred relationships involving `Empresa` (e.g. with `.registrarVista()` and `.run()`) actually correct?**
  _`Empresa` has 2 INFERRED edges - model-reasoned connections that need verification._
- **What connects `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader` to the rest of the system?**
  _47 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Illuminate\Http\Request` be split into smaller, more focused modules?**
  _Cohesion score 0.07811447811447811 - nodes in this community are weakly interconnected._
- **Should `Composer Package Config` be split into smaller, more focused modules?**
  _Cohesion score 0.047619047619047616 - nodes in this community are weakly interconnected._