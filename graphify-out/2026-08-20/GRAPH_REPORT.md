# Graph Report - back-sistema-pedidos-3D  (2026-08-20)

## Corpus Check
- 102 files · ~16,211 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 484 nodes · 934 edges · 50 communities (43 shown, 7 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 8 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `0d404e14`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Illuminate\Http\Request
- Composer Package Config
- Pedido
- Mesa
- Composer Scripts
- Plato
- CLAUDE.md
- App Service Provider
- Illuminate\Support\Facades\Schema
- Cupon
- Empresa
- App Bootstrap Config
- Logging Config
- Unit Test Example
- Illuminate\Database\Migrations\Migration
- PedidoTest
- README.md
- Illuminate\Database\Schema\Blueprint
- Console Commands
- UserFactory.php

## God Nodes (most connected - your core abstractions)
1. `Empresa` - 65 edges
2. `User` - 49 edges
3. `Plato` - 37 edges
4. `Mesa` - 30 edges
5. `TestCase` - 24 edges
6. `Pedido` - 23 edges
7. `PedidoTest` - 20 edges
8. `Controller` - 19 edges
9. `Cupon` - 19 edges
10. `CuponTest` - 16 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `Empresa`  [EXTRACTED]
  database/migrations/2026_08_12_140002_seed_posiciones_mesas.php → app/Models/Empresa.php
- `CuponTest` --references--> `Empresa`  [EXTRACTED]
  tests/Feature/CuponTest.php → app/Models/Empresa.php
- `MesaTest` --references--> `Empresa`  [EXTRACTED]
  tests/Feature/MesaTest.php → app/Models/Empresa.php
- `PedidoTest` --references--> `Empresa`  [EXTRACTED]
  tests/Feature/PedidoTest.php → app/Models/Empresa.php
- `CuponTest` --references--> `Plato`  [EXTRACTED]
  tests/Feature/CuponTest.php → app/Models/Plato.php

## Import Cycles
- None detected.

## Communities (50 total, 7 thin omitted)

### Community 0 - "Illuminate\Http\Request"
Cohesion: 0.08
Nodes (26): App\Http\Controllers\Api\AuthController, AuthController, App\Http\Controllers\Api\CuponController, EmpresaController, MenuController, App\Http\Controllers\Api\MesaController, PagoController, App\Http\Controllers\Api\RegistroController (+18 more)

### Community 1 - "Composer Package Config"
Cohesion: 0.05
Nodes (41): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+33 more)

### Community 2 - "Pedido"
Cohesion: 0.07
Nodes (17): App\Http\Controllers\Api\MetricaController, MetricaController, PedidoController, ArVista, App\Models\Cupon, App\Models\Mesa, App\Models\Pedido, Pedido (+9 more)

### Community 3 - "Mesa"
Cohesion: 0.06
Nodes (14): App\Http\Controllers\Api\CocinaController, CocinaController, MesaController, Llamado, Mesa, up(), DatabaseSeeder, DemoEmpresaSeeder (+6 more)

### Community 4 - "Composer Scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 5 - "Plato"
Cohesion: 0.16
Nodes (6): App\Http\Controllers\Api\PlatoController, PlatoController, Plato, Illuminate\Support\Str, Pdo\Mysql, self

### Community 7 - "App Service Provider"
Cohesion: 0.29
Nodes (4): AppServiceProvider, Illuminate\Cache\RateLimiting\Limit, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\ServiceProvider

### Community 10 - "Cupon"
Cohesion: 0.19
Nodes (3): CuponController, Cupon, CuponTest

### Community 11 - "Empresa"
Cohesion: 0.06
Nodes (18): Empresa, User, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Foundation\Auth\User, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, Illuminate\Notifications\Notifiable, Illuminate\Support\Facades\Hash (+10 more)

### Community 13 - "App Bootstrap Config"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware

### Community 15 - "Logging Config"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 27 - "README.md"
Cohesion: 0.22
Nodes (8): About Laravel, Code of Conduct, Contributing, Laravel Sponsors, Learning Laravel, License, Premium Partners, Security Vulnerabilities

### Community 45 - "UserFactory.php"
Cohesion: 0.47
Nodes (3): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, static

## Knowledge Gaps
- **54 isolated node(s):** `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader`, `preferred-install`, `sort-packages` (+49 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **7 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Empresa` connect `Empresa` to `Illuminate\Http\Request`, `Pedido`, `Mesa`, `Cupon`, `PedidoTest`?**
  _High betweenness centrality (0.221) - this node is a cross-community bridge._
- **Why does `User` connect `Empresa` to `Illuminate\Http\Request`, `Mesa`, `Cupon`, `UserFactory.php`, `PedidoTest`?**
  _High betweenness centrality (0.085) - this node is a cross-community bridge._
- **Why does `Mesa` connect `Mesa` to `Illuminate\Http\Request`, `Pedido`, `Empresa`?**
  _High betweenness centrality (0.066) - this node is a cross-community bridge._
- **Are the 2 inferred relationships involving `Plato` (e.g. with `.index()` and `.store()`) actually correct?**
  _`Plato` has 2 INFERRED edges - model-reasoned connections that need verification._
- **What connects `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader` to the rest of the system?**
  _54 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Illuminate\Http\Request` be split into smaller, more focused modules?**
  _Cohesion score 0.07686274509803921 - nodes in this community are weakly interconnected._
- **Should `Composer Package Config` be split into smaller, more focused modules?**
  _Cohesion score 0.047619047619047616 - nodes in this community are weakly interconnected._