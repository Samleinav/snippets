# Rescue Mode

If a saved snippet creates an unending loop or fatal bug that prevents any pages on your site from loading (The White Screen of Death), it might be impossible to enter the admin panel to disable it by hand. This plugin is built with a dual Rescue architecture to restore the system instantly.

## 1. Web Rescue Protocol (Dashboard)

If only the frontend or a specific page has crashed but you still have internal admin access:
- Navigate to **Snippets** -> **Settings (Rescue)**.
- Read the warning and press the red **Fire Rescue Protocol** button.
- The system will override all database constraints and forcefully swap every single snippet's status from `Published` to `Draft`. The site will regain stability.

## 2. Terminal Auto-Rescue Protocol (CLI)

If the entire CMS framework, including your admin dashboard, is throwing 500 Fatal Server Errors:
1. Log into your hosting server via **SSH**, or enter Botble's Command Line interface if accessible.
2. Execute the official emergency reset command:
```bash
php artisan snippets:rescue
```

Because Artisan routines do not resolve HTTP events, the command will safely connect to the database via Query Builders (avoiding eloquent hooks) and shut off all snippets silently. The site is fixed!
