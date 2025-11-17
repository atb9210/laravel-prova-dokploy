## Scelta DB
- Laravel supporta sia variabili separate (`DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) sia URL unica (`DATABASE_URL`).
- Raccomandazione: usa variabili separate per chiarezza. In alternativa puoi usare `DATABASE_URL=mysql://user:pass@host:port/db` se vuoi una sola variabile (Laravel legge `DATABASE_URL` in `config/database.php`).
- In Dokploy, preferisci l'Internal Connection URL del servizio MySQL (sicuro e interno al progetto) per valorizzare le variabili.

## Prerequisiti
- Repository Laravel pulito (già creato).
- Railpack come Build Type in Dokploy.
- Servizio MySQL creato in Dokploy (annota host interno, porta, db, utente, password e/o internal connection URL).

## Passi Locali (login)
1. Aggiungi autenticazione base:
   - `composer require laravel/breeze --dev`
   - `php artisan breeze:install blade`
   - `npm install && npm run build`
2. Verifica localmente `/login` e `/register`.

## Configurazione Dokploy (Railpack)
1. Crea Application (Build Type: Railpack) e collega il repo.
2. Imposta variabili ambiente (service-level):
   - Core: `APP_ENV=production`, `APP_DEBUG=false`, `APP_KEY=<chiave>`, `APP_URL=https://${{DOKPLOY_DEPLOY_URL}}`
   - MySQL (opzione A – variabili separate):
     - `DB_CONNECTION=mysql`
     - `DB_HOST=<internal host>`
     - `DB_PORT=3306`
     - `DB_DATABASE=<nome db>`
     - `DB_USERNAME=<utente>`
     - `DB_PASSWORD=<password>`
   - MySQL (opzione B – URL unica):
     - `DATABASE_URL=mysql://<user>:<pass>@<host>:<port>/<db>`
3. Migrazioni: non impostare `RAILPACK_SKIP_MIGRATIONS` (così Railpack può eseguirle in startup). In caso di problemi, le eseguiremo manualmente post-deploy.

## Deploy e Verifica
- Esegui Deploy in Dokploy.
- Controlla i log: build Railpack, avvio FrankenPHP/Caddy, migrazioni.
- Testa: `/login`, `/register`, creazione utente, accesso.

## Opzionale
- Estensioni PHP: `RAILPACK_PHP_EXTENSIONS=gd,redis` se servono.
- `railpack.json` minimale per ottimizzazioni di cache (non obbligatorio): aggiunta di `php artisan config:cache/route:cache/view:cache` e `npm run build` in step build.

## Riferimenti
- Dokploy DB connection: https://docs.dokploy.com/docs/core/databases/connection
- Variabili ambiente Dokploy: https://docs.dokploy.com/docs/core/variables
- Railpack PHP (Laravel): https://railpack.com/languages/php/