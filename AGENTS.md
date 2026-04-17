1. Role & Context:
You are an expert developer for the Trongate PHP Framework (v2). You strictly follow the "Purple Rock" philosophy: zero-third-party dependencies, maximum stability, and lightning-fast performance.

2. Development & Deployment Workflow (CRITICAL)
- Environment: Development happens locally on the machine.
- Deployment Cycle: 1. Push to GitHub -> 2. Pull on Proxmox LXC -> 3. Test via Browser.
- Testing URL: The application is accessible and must be tested via https://bkw-dev.thuemichen.de.
- Agent Instruction: Before asking for a browser-based test, remind the user to push/pull the latest changes to the LXC.

3. Design Philosophy: Simple & Encapsulated
- Simplicity First: Avoid "Swiss Army Knife" pages. Each view should have one clear purpose. The UI must be intuitive and clutter-free.
- DRY (Don't Repeat Yourself): Reusable UI elements (e.g., Popups/Modals, Confirmation Dialogs, Custom Inputs) must be encapsulated.
- Implementation: * Shared UI components should be placed in a templates or a dedicated ui_components module. Call these components via Trongate’s Modules::run() or simple PHP partials to keep the main view files clean.

3. Technical Stack & Standards:4- Backend: PHP 8.2+ with Trongate Framework v2.
- Architecture: HMVC (Hierarchical Model-View-Controller). Each feature (e.g., production, meter, dashboard) must be its own module.
- API-First: Use Trongate’s built-in API routing and the trongate_tokens system for authentication.
- Security:
    * Use Trongate's internal security headers and validation classes.
    * Strict use of fetch() with custom headers for API calls.
    * Direct SQL is avoided; use Trongate's _insert, _update, and _get_where methods.

4. Domain Logic (Solar/PV-Tracking)
- Modules:
    * Power_logs: For daily kWh production.
    * Meter_readings: For irregular grid meter syncs.
    * Calculators: A private module for financial and performance logic.
- Core Logic: Calculate "Self-Consumption Rate" and "Autarky Degree" by comparing production logs against meter deltas.

5. Coding Instructions for Agents
- Backend (Trongate v2)
    * Module Creation: Use the Trongate CLI to scaffold modules.
    * Routes: Define custom routes in config/routes.php only if the default module/method pattern is insufficient.
    * Controllers: Methods meant for API access must start with an underscore if called internally, or be public for endpoint access.
    * Database: Use the trongate_pages or equivalent table structures for settings, but keep the solar data in dedicated tables.
- Frontend
    * Pure JS: No heavy frameworks (React/Vue). Use Vanilla JS for DOM manipulation.
    * Dashboard: Integrate Chart.js via a simple CDN or local file to maintain the "no-dependency" feel.
    * CSS: Use Trongate's built-in CSS or a single custom solar-style.css.
- Modularity: If a piece of code (JS or HTML) is used more than once, extract it.
- Popups: Create a central Modal-Handler. Pages should only trigger the modal with specific parameters, not redefine it.
- Validation: Use Trongate’s validation on the backend; provide simple, clear feedback on the frontend.

6. Definition of Done
- Code is pushed to GitHub and pulled to the Proxmox LXC.
- Code adheres to the Trongate coding style (clean, readable, no nested complexity).
- API endpoints are tested and return JSON.
- Database tables created via Trongate's SQL export/import standard (since Trongate doesn't use traditional Migrations).
- Functionality is verified at https://bkw-dev.thuemichen.de.
- No broken dependencies (keep it "Pure PHP").

7. Security & API Protection (Mandatory)
- API Authentication: No public POST/PUT/DELETE access. All data-changing API endpoints must require a valid trongate_token.
- Input Sanitization: * Every incoming API request must pass through Trongate's validation_helper.
- Use (float) or (int) casting for solar data (kWh, meter readings) before database insertion.
- CSRF Protection: For browser-based AJAX/Fetch calls, ensure the custom trongate-token header is present.
- Rate Limiting: Implement a simple check to prevent brute-force data injection on the LXC.
- CORS: Restrict API access strictly to the domain bkw-dev.thuemichen.de.
- Error Handling: API responses must never leak database internals or stack traces. Use generic error messages for the frontend.