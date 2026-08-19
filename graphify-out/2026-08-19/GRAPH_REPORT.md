# Graph Report - back-sistema-pedidos-3D  (2026-08-19)

## Corpus Check
- 85 files · ~13,133 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 391 nodes · 665 edges · 44 communities (38 shown, 6 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 4 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `78066f82`
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
- TestCase
- PedidoTest
- Sanctum Token Middleware
- App Bootstrap Config
- Illuminate\Support\Str
- Logging Config
- Unit Test Example
- README.md
- Console Commands
- Illuminate\Support\Facades\Schema

## God Nodes (most connected - your core abstractions)
1. `Mesa` - 26 edges
2. `Plato` - 26 edges
3. `Empresa` - 24 edges
4. `Pedido` - 23 edges
5. `Controller` - 21 edges
6. `PedidoTest` - 20 edges
7. `User` - 17 edges
8. `PlatoController` - 13 edges
9. `PlatoTest` - 12 edges
10. `PedidoController` - 11 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `Mesa`  [EXTRACTED]
  database/migrations/2026_08_12_140002_seed_posiciones_mesas.php → app/Models/Mesa.php
- `up()` --calls--> `Empresa`  [EXTRACTED]
  database/migrations/2026_08_12_140002_seed_posiciones_mesas.php → app/Models/Empresa.php
- `PedidoController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Api/PedidoController.php → app/Http/Controllers/Controller.php
- `CocinaController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Api/CocinaController.php → app/Http/Controllers/Controller.php
- `MetricaController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Api/MetricaController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (44 total, 6 thin omitted)

### Community 0 - "Illuminate\Http\Request"
Cohesion: 0.08
Nodes (16): AuthController, EmpresaController, MenuController, MesaController, RegistroController, SSEController, Controller, Empresa (+8 more)

### Community 1 - "Composer Package Config"
Cohesion: 0.05
Nodes (41): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+33 more)

### Community 2 - "Pedido"
Cohesion: 0.08
Nodes (12): CocinaController, PedidoController, Cupon, Llamado, App\Models\Pedido, Pedido, PedidoItem, PlatoAgregado (+4 more)

### Community 3 - "User"
Cohesion: 0.12
Nodes (12): App\Models\User, User, DatabaseSeeder, EmpresaSeeder, MesaSeeder, PlatoSeeder, UserSeeder, Illuminate\Database\Eloquent\Factories\HasFactory (+4 more)

### Community 4 - "Composer Scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 5 - "Plato"
Cohesion: 0.14
Nodes (7): MetricaController, Request, PlatoController, ArVista, Plato, Illuminate\Support\Facades\Storage, self

### Community 7 - "App Service Provider"
Cohesion: 0.29
Nodes (4): AppServiceProvider, Illuminate\Cache\RateLimiting\Limit, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\ServiceProvider

### Community 10 - "TestCase"
Cohesion: 0.50
Nodes (3): Illuminate\Foundation\Testing\TestCase, ExampleTest, TestCase

### Community 11 - "PedidoTest"
Cohesion: 0.08
Nodes (14): App\Models\Empresa, App\Models\Plato, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Support\Facades\Hash, Pedido, Plato, AuthTest, PedidoTest (+6 more)

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

## Knowledge Gaps
- **54 isolated node(s):** `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader`, `preferred-install`, `sort-packages` (+49 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **6 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Empresa` connect `Illuminate\Http\Request` to `User`, `Pedido`, `PedidoTest`, `Plato`?**
  _High betweenness centrality (0.118) - this node is a cross-community bridge._
- **Why does `Mesa` connect `Illuminate\Http\Request` to `Pedido`, `User`?**
  _High betweenness centrality (0.073) - this node is a cross-community bridge._
- **Why does `PedidoTest` connect `PedidoTest` to `User`?**
  _High betweenness centrality (0.064) - this node is a cross-community bridge._
- **What connects `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader` to the rest of the system?**
  _54 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Illuminate\Http\Request` be split into smaller, more focused modules?**
  _Cohesion score 0.08200290275761973 - nodes in this community are weakly interconnected._
- **Should `Composer Package Config` be split into smaller, more focused modules?**
  _Cohesion score 0.047619047619047616 - nodes in this community are weakly interconnected._
- **Should `Pedido` be split into smaller, more focused modules?**
  _Cohesion score 0.07505285412262157 - nodes in this community are weakly interconnected._