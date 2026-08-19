# Graph Report - back-sistema-pedidos-3D  (2026-08-19)

## Corpus Check
- 82 files · ~12,296 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 354 nodes · 590 edges · 44 communities (38 shown, 6 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 5 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `7c287e5d`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Illuminate\Http\Request
- Composer Package Config
- Pedido
- User
- Composer Scripts
- Plato
- CLAUDE.md
- App Service Provider
- Illuminate\Database\Migrations\Migration
- Illuminate\Database\Schema\Blueprint
- Illuminate\Support\Facades\Schema
- Feature Test Example
- Sanctum Token Middleware
- App Bootstrap Config
- Illuminate\Support\Str
- Logging Config
- Unit Test Example
- README.md
- Llamado
- Console Commands

## God Nodes (most connected - your core abstractions)
1. `Mesa` - 26 edges
2. `Plato` - 26 edges
3. `Empresa` - 23 edges
4. `Pedido` - 23 edges
5. `Controller` - 19 edges
6. `User` - 17 edges
7. `PlatoController` - 12 edges
8. `PedidoItem` - 11 edges
9. `PedidoController` - 10 edges
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

## Communities (44 total, 6 thin omitted)

### Community 0 - "Illuminate\Http\Request"
Cohesion: 0.08
Nodes (27): App\Http\Controllers\Api\AuthController, AuthController, EmpresaController, App\Http\Controllers\Api\MenuController, MenuController, App\Http\Controllers\Api\MesaController, MesaController, App\Http\Controllers\Api\PedidoController (+19 more)

### Community 1 - "Composer Package Config"
Cohesion: 0.05
Nodes (41): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+33 more)

### Community 2 - "Pedido"
Cohesion: 0.10
Nodes (8): PedidoController, Cupon, Pedido, PedidoItem, PlatoAgregado, PlatoPresentacion, DemoSeeder, Illuminate\Database\Eloquent\Model

### Community 3 - "User"
Cohesion: 0.12
Nodes (11): User, DatabaseSeeder, EmpresaSeeder, MesaSeeder, PlatoSeeder, UserSeeder, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Seeder (+3 more)

### Community 4 - "Composer Scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 5 - "Plato"
Cohesion: 0.16
Nodes (7): App\Http\Controllers\Api\MetricaController, MetricaController, Request, PlatoController, ArVista, Plato, self

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

### Community 27 - "README.md"
Cohesion: 0.22
Nodes (8): About Laravel, Code of Conduct, Contributing, Laravel Sponsors, Learning Laravel, License, Premium Partners, Security Vulnerabilities

### Community 28 - "Llamado"
Cohesion: 0.15
Nodes (4): App\Http\Controllers\Api\CocinaController, CocinaController, Llamado, Illuminate\Support\Facades\DB

## Knowledge Gaps
- **54 isolated node(s):** `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader`, `preferred-install`, `sort-packages` (+49 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **6 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Mesa` connect `Illuminate\Http\Request` to `Illuminate\Database\Migrations\Migration`, `Pedido`, `User`, `Llamado`?**
  _High betweenness centrality (0.085) - this node is a cross-community bridge._
- **Why does `Empresa` connect `Illuminate\Http\Request` to `Illuminate\Database\Migrations\Migration`, `Pedido`, `User`, `Plato`?**
  _High betweenness centrality (0.082) - this node is a cross-community bridge._
- **Why does `Plato` connect `Plato` to `Illuminate\Http\Request`, `Pedido`, `User`, `Llamado`?**
  _High betweenness centrality (0.053) - this node is a cross-community bridge._
- **Are the 2 inferred relationships involving `Empresa` (e.g. with `.show()` and `.registrarVista()`) actually correct?**
  _`Empresa` has 2 INFERRED edges - model-reasoned connections that need verification._
- **What connects `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader` to the rest of the system?**
  _54 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Illuminate\Http\Request` be split into smaller, more focused modules?**
  _Cohesion score 0.07946127946127945 - nodes in this community are weakly interconnected._
- **Should `Composer Package Config` be split into smaller, more focused modules?**
  _Cohesion score 0.047619047619047616 - nodes in this community are weakly interconnected._