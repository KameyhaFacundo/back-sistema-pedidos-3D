# Graph Report - .  (2026-08-15)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 339 nodes · 570 edges · 42 communities (35 shown, 7 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 4 edges (avg confidence: 0.8)
- Token cost: 37,686 input · 696 output

## Graph Freshness
- Built from commit: `9b23583f`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Auth & Empresa Controllers
- Composer Package Config
- Cocina & Métricas Controllers
- User Model & Auth
- Composer Scripts
- Plato Controller
- Pedido Controller
- App Service Provider
- Users & Platos Migrations
- Cache & Mesas Migrations
- Jobs & Pedido Items Migrations
- Feature Test Example
- Sanctum Token Middleware
- App Bootstrap Config
- Cache/DB/Session Config
- Logging Config
- Unit Test Example
- Console Commands

## God Nodes (most connected - your core abstractions)
1. `Plato` - 26 edges
2. `Mesa` - 24 edges
3. `Pedido` - 23 edges
4. `Empresa` - 22 edges
5. `Controller` - 21 edges
6. `User` - 17 edges
7. `PlatoController` - 13 edges
8. `PedidoItem` - 11 edges
9. `PedidoController` - 11 edges
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

## Communities (42 total, 7 thin omitted)

### Community 0 - "Auth & Empresa Controllers"
Cohesion: 0.09
Nodes (15): AuthController, EmpresaController, MenuController, MesaController, RegistroController, SSEController, Controller, Empresa (+7 more)

### Community 1 - "Composer Package Config"
Cohesion: 0.05
Nodes (41): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+33 more)

### Community 2 - "Cocina & Métricas Controllers"
Cohesion: 0.07
Nodes (12): CocinaController, MetricaController, Request, ArVista, Cupon, Llamado, PedidoItem, PlatoAgregado (+4 more)

### Community 3 - "User Model & Auth"
Cohesion: 0.09
Nodes (16): User, UserFactory, DatabaseSeeder, EmpresaSeeder, MesaSeeder, PlatoSeeder, UserSeeder, Illuminate\Database\Eloquent\Factories\Factory (+8 more)

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

### Community 15 - "Logging Config"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

## Knowledge Gaps
- **46 isolated node(s):** `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader`, `preferred-install`, `sort-packages` (+41 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **7 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Mesa` connect `Auth & Empresa Controllers` to `Cocina & Métricas Controllers`, `User Model & Auth`?**
  _High betweenness centrality (0.087) - this node is a cross-community bridge._
- **Why does `Empresa` connect `Auth & Empresa Controllers` to `Cocina & Métricas Controllers`, `User Model & Auth`?**
  _High betweenness centrality (0.085) - this node is a cross-community bridge._
- **Why does `Plato` connect `Plato Controller` to `Auth & Empresa Controllers`, `Cocina & Métricas Controllers`, `User Model & Auth`?**
  _High betweenness centrality (0.057) - this node is a cross-community bridge._
- **What connects `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader` to the rest of the system?**
  _46 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Auth & Empresa Controllers` be split into smaller, more focused modules?**
  _Cohesion score 0.08549019607843138 - nodes in this community are weakly interconnected._
- **Should `Composer Package Config` be split into smaller, more focused modules?**
  _Cohesion score 0.047619047619047616 - nodes in this community are weakly interconnected._
- **Should `Cocina & Métricas Controllers` be split into smaller, more focused modules?**
  _Cohesion score 0.07307692307692308 - nodes in this community are weakly interconnected._