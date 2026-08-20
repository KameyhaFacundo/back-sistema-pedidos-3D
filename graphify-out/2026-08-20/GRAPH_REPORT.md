# Graph Report - back-sistema-pedidos-3D  (2026-08-20)

## Corpus Check
- 100 files · ~15,763 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 474 nodes · 917 edges · 50 communities (42 shown, 8 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 2 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `ab170ff2`
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
- Cupon
- Empresa
- App Bootstrap Config
- Logging Config
- Unit Test Example
- Illuminate\Support\Facades\Schema
- PedidoTest
- README.md
- Illuminate\Database\Schema\Blueprint
- RoleTest
- Console Commands
- UserFactory.php

## God Nodes (most connected - your core abstractions)
1. `Empresa` - 64 edges
2. `User` - 49 edges
3. `Plato` - 39 edges
4. `Mesa` - 31 edges
5. `Pedido` - 31 edges
6. `TestCase` - 24 edges
7. `Controller` - 23 edges
8. `Cupon` - 20 edges
9. `PedidoTest` - 20 edges
10. `CuponTest` - 16 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `Empresa`  [EXTRACTED]
  database/migrations/2026_08_12_140002_seed_posiciones_mesas.php → app/Models/Empresa.php
- `CuponTest` --references--> `Empresa`  [EXTRACTED]
  tests/Feature/CuponTest.php → app/Models/Empresa.php
- `PedidoTest` --references--> `Empresa`  [EXTRACTED]
  tests/Feature/PedidoTest.php → app/Models/Empresa.php
- `RoleTest` --references--> `Empresa`  [EXTRACTED]
  tests/Feature/RoleTest.php → app/Models/Empresa.php
- `MesaTest` --references--> `Mesa`  [EXTRACTED]
  tests/Feature/MesaTest.php → app/Models/Mesa.php

## Import Cycles
- None detected.

## Communities (50 total, 8 thin omitted)

### Community 0 - "Illuminate\Http\Request"
Cohesion: 0.06
Nodes (23): AuthController, EmpresaController, MenuController, MesaController, RegistroController, SSEController, StaffController, App\Http\Controllers\Controller (+15 more)

### Community 1 - "Composer Package Config"
Cohesion: 0.05
Nodes (41): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+33 more)

### Community 2 - "Pedido"
Cohesion: 0.07
Nodes (10): CocinaController, PedidoController, Llamado, Pedido, PedidoItem, PlatoAgregado, PlatoPresentacion, DemoSeeder (+2 more)

### Community 3 - "Illuminate\Database\Seeder"
Cohesion: 0.14
Nodes (7): DatabaseSeeder, DemoEmpresaSeeder, EmpresaSeeder, MesaSeeder, PlatoSeeder, UserSeeder, Illuminate\Database\Seeder

### Community 4 - "Composer Scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 5 - "Plato"
Cohesion: 0.16
Nodes (5): MetricaController, PlatoController, ArVista, Plato, self

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
- **8 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Empresa` connect `Empresa` to `Illuminate\Http\Request`, `Pedido`, `Illuminate\Database\Seeder`, `Plato`, `Cupon`, `PedidoTest`, `RoleTest`?**
  _High betweenness centrality (0.215) - this node is a cross-community bridge._
- **Why does `User` connect `Empresa` to `Illuminate\Http\Request`, `Illuminate\Database\Seeder`, `Cupon`, `UserFactory.php`, `PedidoTest`, `RoleTest`?**
  _High betweenness centrality (0.085) - this node is a cross-community bridge._
- **Why does `Mesa` connect `Illuminate\Http\Request` to `Empresa`, `Pedido`, `Illuminate\Database\Seeder`?**
  _High betweenness centrality (0.069) - this node is a cross-community bridge._
- **What connects `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader` to the rest of the system?**
  _54 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Illuminate\Http\Request` be split into smaller, more focused modules?**
  _Cohesion score 0.060153776571687016 - nodes in this community are weakly interconnected._
- **Should `Composer Package Config` be split into smaller, more focused modules?**
  _Cohesion score 0.047619047619047616 - nodes in this community are weakly interconnected._
- **Should `Pedido` be split into smaller, more focused modules?**
  _Cohesion score 0.0696969696969697 - nodes in this community are weakly interconnected._