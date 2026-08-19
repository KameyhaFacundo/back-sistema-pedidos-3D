# Graph Report - back-sistema-pedidos-3D  (2026-08-19)

## Corpus Check
- 85 files · ~13,133 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 385 nodes · 671 edges · 43 communities (37 shown, 6 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 4 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `cd6faa01`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Illuminate\Http\Request
- Composer Package Config
- Illuminate\Database\Eloquent\Model
- Illuminate\Database\Seeder
- Composer Scripts
- Plato
- CLAUDE.md
- App Service Provider
- Illuminate\Database\Migrations\Migration
- Illuminate\Database\Schema\Blueprint
- Empresa
- Sanctum Token Middleware
- App Bootstrap Config
- Illuminate\Support\Str
- Logging Config
- Unit Test Example
- README.md
- Console Commands
- Illuminate\Support\Facades\Schema

## God Nodes (most connected - your core abstractions)
1. `Empresa` - 37 edges
2. `Plato` - 32 edges
3. `Mesa` - 26 edges
4. `Pedido` - 26 edges
5. `User` - 26 edges
6. `Controller` - 21 edges
7. `PedidoTest` - 20 edges
8. `PlatoController` - 13 edges
9. `PlatoTest` - 12 edges
10. `PedidoController` - 11 edges

## Surprising Connections (you probably didn't know these)
- `up()` --calls--> `Mesa`  [EXTRACTED]
  database/migrations/2026_08_12_140002_seed_posiciones_mesas.php → app/Models/Mesa.php
- `PedidoTest` --references--> `Plato`  [EXTRACTED]
  tests/Feature/PedidoTest.php → app/Models/Plato.php
- `up()` --calls--> `Empresa`  [EXTRACTED]
  database/migrations/2026_08_12_140002_seed_posiciones_mesas.php → app/Models/Empresa.php
- `PedidoTest` --references--> `Empresa`  [EXTRACTED]
  tests/Feature/PedidoTest.php → app/Models/Empresa.php
- `PlatoTest` --references--> `Empresa`  [EXTRACTED]
  tests/Feature/PlatoTest.php → app/Models/Empresa.php

## Import Cycles
- None detected.

## Communities (43 total, 6 thin omitted)

### Community 0 - "Illuminate\Http\Request"
Cohesion: 0.08
Nodes (15): AuthController, EmpresaController, MenuController, MesaController, PedidoController, RegistroController, SSEController, Controller (+7 more)

### Community 1 - "Composer Package Config"
Cohesion: 0.05
Nodes (41): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+33 more)

### Community 2 - "Illuminate\Database\Eloquent\Model"
Cohesion: 0.09
Nodes (8): CocinaController, Llamado, PedidoItem, PlatoAgregado, PlatoPresentacion, DemoSeeder, Illuminate\Database\Eloquent\Model, Illuminate\Support\Facades\DB

### Community 3 - "Illuminate\Database\Seeder"
Cohesion: 0.14
Nodes (7): DatabaseSeeder, DemoEmpresaSeeder, EmpresaSeeder, MesaSeeder, PlatoSeeder, UserSeeder, Illuminate\Database\Seeder

### Community 4 - "Composer Scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 5 - "Plato"
Cohesion: 0.14
Nodes (7): MetricaController, Request, PlatoController, ArVista, Plato, Illuminate\Support\Facades\Storage, self

### Community 7 - "App Service Provider"
Cohesion: 0.29
Nodes (4): AppServiceProvider, Illuminate\Cache\RateLimiting\Limit, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\ServiceProvider

### Community 11 - "Empresa"
Cohesion: 0.06
Nodes (16): Empresa, User, up(), Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Foundation\Auth\User, Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, Illuminate\Notifications\Notifiable (+8 more)

### Community 12 - "Sanctum Token Middleware"
Cohesion: 0.50
Nodes (3): TokenFromQuery, Closure, Laravel\Sanctum\PersonalAccessToken

### Community 13 - "App Bootstrap Config"
Cohesion: 0.40
Nodes (3): Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware

### Community 14 - "Illuminate\Support\Str"
Cohesion: 0.20
Nodes (5): UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Str, Pdo\Mysql, static

### Community 15 - "Logging Config"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 27 - "README.md"
Cohesion: 0.22
Nodes (8): About Laravel, Code of Conduct, Contributing, Laravel Sponsors, Learning Laravel, License, Premium Partners, Security Vulnerabilities

## Knowledge Gaps
- **54 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+49 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **6 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Empresa` connect `Empresa` to `Illuminate\Http\Request`, `Illuminate\Database\Eloquent\Model`, `Illuminate\Database\Seeder`, `Plato`, `Illuminate\Support\Str`?**
  _High betweenness centrality (0.152) - this node is a cross-community bridge._
- **Why does `Mesa` connect `Illuminate\Http\Request` to `Illuminate\Database\Seeder`, `Illuminate\Database\Eloquent\Model`, `Empresa`?**
  _High betweenness centrality (0.073) - this node is a cross-community bridge._
- **Why does `Plato` connect `Plato` to `Illuminate\Http\Request`, `Empresa`, `Illuminate\Database\Eloquent\Model`, `Illuminate\Database\Seeder`?**
  _High betweenness centrality (0.068) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _54 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Illuminate\Http\Request` be split into smaller, more focused modules?**
  _Cohesion score 0.07922077922077922 - nodes in this community are weakly interconnected._
- **Should `Composer Package Config` be split into smaller, more focused modules?**
  _Cohesion score 0.047619047619047616 - nodes in this community are weakly interconnected._
- **Should `Illuminate\Database\Eloquent\Model` be split into smaller, more focused modules?**
  _Cohesion score 0.09195402298850575 - nodes in this community are weakly interconnected._