## Problema
- Errore in build: "vite: Permission denied" durante `npm run build` in Railpack.
- Env DB usa `DB_URL` invece di `DATABASE_URL` o variabili separate.

## Cause probabili
- Dev dependencies non installate (vite è devDependency) o permessi mancanti su `node_modules/.bin/vite`.
- Versione Node non forzata; Railpack installa Node automaticamente ma possiamo definire la versione.

## Piano di intervento
1. Aggiornare `railpack.json` per build frontend robusta
   - Impostare la versione di Node (es. 20/22) via `packages`.
   - Garantire dev deps e permessi prima della build:
     - `npm ci --include=dev`
     - `chmod +x node_modules/.bin/vite`
     - `npx vite build` (fallback più affidabile di `vite` diretto)
     - Cache Laravel: `php artisan config:cache`, `route:cache`, `view:cache`
   - Fallback alternativo: usare `node node_modules/vite/bin/vite.js build` se `npx` non fosse disponibile.
2. Sistemare variabili DB in Dokploy
   - Opzione A (consigliata): variabili separate
     - `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT=3306`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
   - Opzione B (URL unica):
     - `DATABASE_URL=mysql://user:pass@host:port/db`
   - Impostazioni core: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://${{DOKPLOY_DEPLOY_URL}}`, `APP_KEY=<valore>`
3. Sessione/Cache per test
   - Temporaneamente: `SESSION_DRIVER=file`, `CACHE_STORE=file` (evita tabelle aggiuntive).
   - Quando il DB è confermato: aggiungere migrazioni `sessions` e `cache` e passare a `database` se necessario.
4. Redeploy in Dokploy
   - Verificare nei log l’esecuzione di `npm ci` e `vite build`, assenza di errori, migrazioni ok.
5. Verifica applicativa
   - Testare `/login`, `/register`, creazione utenti su MySQL.

## Opzione rapida (se il build frontend continua a fallire)
- Precompilare assets localmente (`npm run build`) e committare `public/build/` nel repo; rimuovere la build vite dal deploy (solo per test, non consigliato in produzione).

## Output atteso
- Build completata senza "Permission denied".
- App Laravel con Breeze servita correttamente e DB MySQL funzionante.

Posso applicare subito le modifiche al `railpack.json`, aggiornare le variabili in Dokploy e rieseguire il deploy?