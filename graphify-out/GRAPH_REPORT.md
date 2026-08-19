# Graph Report - back-sistema-pedidos-3D  (2026-08-19)

## Corpus Check
- 97 files · ~15,474 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 466 nodes · 902 edges · 49 communities (42 shown, 7 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 3 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `19e6d92a`
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
- Illuminate\Database\Schema\Blueprint
- Illuminate\Support\Facades\Schema
- Cupon
- Empresa
- TokenFromQuery.php
- App Bootstrap Config
- UserFactory.php
- Logging Config
- Unit Test Example
- RoleTest
- PedidoTest
- README.md
- Illuminate\Database\Migrations\Migration
- Console Commands

## God Nodes (most connected - your core abstractions)
1. `Empresa` - 61 edges
2. `User` - 49 edges
3. `Plato` - 39 edges
4. `Mesa` - 31 edges
5. `Pedido` - 31 edges
6. `Controller` - 23 edges
7. `TestCase` - 22 edges
8. `Cupon` - 20 edges
9. `PedidoTest` - 20 edges
10. `CuponTest` - 16 edges

## Surprising Connections (you probably didn't know these)
- `CuponTest` --references--> `User`  [EXTRACTED]
  tests/Feature/CuponTest.php → app/Models/User.php
- `PedidoTest` --references--> `User`  [EXTRACTED]
  tests/Feature/PedidoTest.php → app/Models/User.php
- `MesaTest` --references--> `Mesa`  [EXTRACTED]
  tests/Feature/MesaTest.php → app/Models/Mesa.php
- `CuponTest` --references--> `Empresa`  [EXTRACTED]
  tests/Feature/CuponTest.php → app/Models/Empresa.php
- `CuponTest` --references--> `Plato`  [EXTRACTED]
  tests/Feature/CuponTest.php → app/Models/Plato.php

## Import Cycles
- None detected.

## Communities (49 total, 7 thin omitted)

### Community 0 - "Illuminate\Http\Request"
Cohesion: 0.07
Nodes (30): App\Http\Controllers\Api\AuthController, AuthController, App\Http\Controllers\Api\CuponController, App\Http\Controllers\Api\EmpresaController, EmpresaController, App\Http\Controllers\Api\MenuController, MenuController, App\Http\Controllers\Api\MesaController (+22 more)

### Community 1 - "Composer Package Config"
Cohesion: 0.05
Nodes (41): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+33 more)

### Community 2 - "Pedido"
Cohesion: 0.08
Nodes (10): App\Http\Controllers\Api\CocinaController, CocinaController, PedidoController, Llamado, Pedido, PedidoItem, PlatoAgregado, PlatoPresentacion (+2 more)

### Community 3 - "Illuminate\Database\Seeder"
Cohesion: 0.14
Nodes (7): DatabaseSeeder, DemoEmpresaSeeder, EmpresaSeeder, MesaSeeder, PlatoSeeder, UserSeeder, Illuminate\Database\Seeder

### Community 4 - "Composer Scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 5 - "Plato"
Cohesion: 0.20
Nodes (4): MetricaController, PlatoController, Plato, self

### Community 7 - "App Service Provider"
Cohesion: 0.29
Nodes (4): AppServiceProvider, Illuminate\Cache\RateLimiting\Limit, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\ServiceProvider

### Community 10 - "Cupon"
Cohesion: 0.18
Nodes (3): CuponController, Cupon, CuponTest

### Community 11 - "Empresa"
Cohesion: 0.05
Nodes (19): ArVista, Empresa, User, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Foundation\Auth\User, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, Illuminate\Notifications\Notifiable (+11 more)

### Community 12 - "TokenFromQuery.php"
Cohesion: 0.32
Nodes (4): EnsureRole, TokenFromQuery, Closure, Laravel\Sanctum\PersonalAccessToken

### Community 13 - "App Bootstrap Config"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware

### Community 14 - "UserFactory.php"
Cohesion: 0.38
Nodes (4): Database\Factories\UserFactory, UserFactory, Illuminate\Database\Eloquent\Factories\Factory, static

### Community 15 - "Logging Config"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 18 - "RoleTest"
Cohesion: 0.27
Nodes (4): App\Models\Empresa, Empresa, RoleTest, Tests\TestCase

### Community 27 - "README.md"
Cohesion: 0.22
Nodes (8): About Laravel, Code of Conduct, Contributing, Laravel Sponsors, Learning Laravel, License, Premium Partners, Security Vulnerabilities

## Knowledge Gaps
- **54 isolated node(s):** `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader`, `preferred-install`, `sort-packages` (+49 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **7 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Empresa` connect `Empresa` to `Illuminate\Http\Request`, `Pedido`, `Illuminate\Database\Seeder`, `Plato`, `Cupon`, `UserFactory.php`, `RoleTest`, `PedidoTest`?**
  _High betweenness centrality (0.199) - this node is a cross-community bridge._
- **Why does `User` connect `Empresa` to `Illuminate\Http\Request`, `Pedido`, `Illuminate\Database\Seeder`, `Cupon`, `UserFactory.php`, `RoleTest`, `PedidoTest`?**
  _High betweenness centrality (0.086) - this node is a cross-community bridge._
- **Why does `Mesa` connect `Illuminate\Http\Request` to `Empresa`, `Pedido`, `Illuminate\Database\Seeder`?**
  _High betweenness centrality (0.067) - this node is a cross-community bridge._
- **What connects `pestphp/pest-plugin`, `php-http/discovery`, `optimize-autoloader` to the rest of the system?**
  _54 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Illuminate\Http\Request` be split into smaller, more focused modules?**
  _Cohesion score 0.06994535519125683 - nodes in this community are weakly interconnected._
- **Should `Composer Package Config` be split into smaller, more focused modules?**
  _Cohesion score 0.047619047619047616 - nodes in this community are weakly interconnected._
- **Should `Pedido` be split into smaller, more focused modules?**
  _Cohesion score 0.08333333333333333 - nodes in this community are weakly interconnected._