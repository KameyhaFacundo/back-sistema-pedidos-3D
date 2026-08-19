# Graph Report - back-sistema-pedidos-3D  (2026-08-19)

## Corpus Check
- 94 files · ~15,139 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 464 nodes · 847 edges · 46 communities (40 shown, 6 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 7 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `68401b8d`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Illuminate\Http\Request
- Composer Package Config
- Pedido
- Illuminate\Database\Seeder
- Composer Scripts
- Plato
- CLAUDE.md
- App Service Provider
- Illuminate\Database\Migrations\Migration
- Illuminate\Database\Schema\Blueprint
- App\Http\Controllers\Controller
- Illuminate\Foundation\Testing\RefreshDatabase
- App\Models\Pedido
- App Bootstrap Config
- UserFactory.php
- Logging Config
- Unit Test Example
- Illuminate\Support\Facades\Schema
- Empresa
- README.md
- Console Commands

## God Nodes (most connected - your core abstractions)
1. `Plato` - 39 edges
2. `Empresa` - 32 edges
3. `User` - 26 edges
4. `Mesa` - 24 edges
5. `PedidoTest` - 20 edges
6. `CuponTest` - 16 edges
7. `Pedido` - 16 edges
8. `PlatoController` - 14 edges
9. `MesaTest` - 14 edges
10. `Controller` - 13 edges

## Surprising Connections (you probably didn't know these)
- `CuponTest` --references--> `Plato`  [EXTRACTED]
  tests/Feature/CuponTest.php → app/Models/Plato.php
- `MetricaTest` --references--> `Plato`  [EXTRACTED]
  tests/Feature/MetricaTest.php → app/Models/Plato.php
- `PedidoTest` --references--> `Plato`  [EXTRACTED]
  tests/Feature/PedidoTest.php → app/Models/Plato.php
- `up()` --calls--> `Mesa`  [EXTRACTED]
  database/migrations/2026_08_12_140002_seed_posiciones_mesas.php → app/Models/Mesa.php
- `up()` --calls--> `Empresa`  [EXTRACTED]
  database/migrations/2026_08_12_140002_seed_posiciones_mesas.php → app/Models/Empresa.php

## Import Cycles
- None detected.

## Communities (46 total, 6 thin omitted)

### Community 0 - "Illuminate\Http\Request"
Cohesion: 0.07
Nodes (24): App\Http\Controllers\Api\AuthController, AuthController, App\Http\Controllers\Api\CocinaController, CocinaController, App\Http\Controllers\Api\EmpresaController, EmpresaController, MenuController, App\Http\Controllers\Api\MesaController (+16 more)

### Community 1 - "Composer Package Config"
Cohesion: 0.05
Nodes (41): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+33 more)

### Community 2 - "Pedido"
Cohesion: 0.15
Nodes (4): Pedido, PedidoItem, DemoSeeder, Illuminate\Support\Facades\DB

### Community 3 - "Illuminate\Database\Seeder"
Cohesion: 0.17
Nodes (6): DatabaseSeeder, DemoEmpresaSeeder, MesaSeeder, PlatoSeeder, UserSeeder, Illuminate\Database\Seeder

### Community 4 - "Composer Scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 5 - "Plato"
Cohesion: 0.10
Nodes (12): MetricaController, PlatoController, App\Models\ArVista, ArVista, Cupon, App\Models\PedidoItem, Plato, PlatoAgregado (+4 more)

### Community 7 - "App Service Provider"
Cohesion: 0.29
Nodes (4): AppServiceProvider, Illuminate\Cache\RateLimiting\Limit, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\ServiceProvider

### Community 10 - "App\Http\Controllers\Controller"
Cohesion: 0.13
Nodes (10): CuponController, User, StaffController, App\Http\Controllers\Controller, App\Models\Cupon, Cupon, Illuminate\Support\Facades\Storage, Illuminate\Support\Str (+2 more)

### Community 11 - "Illuminate\Foundation\Testing\RefreshDatabase"
Cohesion: 0.06
Nodes (21): App\Models\Empresa, App\Models\Mesa, App\Models\User, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Support\Facades\Hash, Mesa, CuponTest, Empresa (+13 more)

### Community 12 - "App\Models\Pedido"
Cohesion: 0.33
Nodes (3): PedidoController, App\Models\Pedido, Pedido

### Community 13 - "App Bootstrap Config"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware

### Community 14 - "UserFactory.php"
Cohesion: 0.32
Nodes (4): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, self, static

### Community 15 - "Logging Config"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 25 - "Empresa"
Cohesion: 0.06
Nodes (14): Empresa, User, up(), EmpresaSeeder, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Foundation\Auth\User, Illuminate\Foundation\Testing\TestCase, Illuminate\Notifications\Notifiable (+6 more)

### Community 27 - "README.md"
Cohesion: 0.22
Nodes (8): About Laravel, Code of Conduct, Contributing, Laravel Sponsors, Learning Laravel, License, Premium Partners, Security Vulnerabilities

## Knowledge Gaps
- **54 isolated node(s):** `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader`, `preferred-install`, `sort-packages` (+49 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **6 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Empresa` connect `Empresa` to `Illuminate\Http\Request`, `Illuminate\Database\Seeder`, `Plato`, `Illuminate\Foundation\Testing\RefreshDatabase`, `UserFactory.php`?**
  _High betweenness centrality (0.137) - this node is a cross-community bridge._
- **Why does `Plato` connect `Plato` to `Illuminate\Http\Request`, `Pedido`, `Illuminate\Database\Seeder`, `App\Http\Controllers\Controller`, `Illuminate\Foundation\Testing\RefreshDatabase`, `App\Models\Pedido`, `Empresa`?**
  _High betweenness centrality (0.089) - this node is a cross-community bridge._
- **Why does `Mesa` connect `Illuminate\Http\Request` to `Pedido`, `Illuminate\Database\Seeder`, `Plato`, `Illuminate\Foundation\Testing\RefreshDatabase`, `Empresa`?**
  _High betweenness centrality (0.062) - this node is a cross-community bridge._
- **What connects `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader` to the rest of the system?**
  _54 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Illuminate\Http\Request` be split into smaller, more focused modules?**
  _Cohesion score 0.07390648567119155 - nodes in this community are weakly interconnected._
- **Should `Composer Package Config` be split into smaller, more focused modules?**
  _Cohesion score 0.047619047619047616 - nodes in this community are weakly interconnected._
- **Should `Composer Scripts` be split into smaller, more focused modules?**
  _Cohesion score 0.08 - nodes in this community are weakly interconnected._