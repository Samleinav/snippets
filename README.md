# Botble Snippets Plugin

![Snippets Banner](screenshot.png)

> **Empower your Botble CMS with custom dynamic logic. No more file editing, no more complex plugin scaffolding—just pure PHP power.**

The **Snippets Plugin** for Botble CMS allows developers and power users to inject custom PHP code into any part of the system directly from the administration dashboard. Whether you need to add a quick tracking script, a custom database hook, or modify frontend behavior on specific routes, Snippets makes it fast, safe, and professional.

---

## 🚀 Why Snippets?

Stop modifying your `functions.php` or hacking core files. Snippets provides a centralized, manageable, and safe environment to extend your platform's capabilities.

### ✨ Key Features

- **🎯 Precision Targeting**: Choose exactly where your code runs:
    - **Global**: Every single request.
    - **Admin Only**: Enhance your dashboard.
    - **Frontend Only**: Customize the visitor experience.
    - **API Only**: Extend your headless backend.
    - **Custom Routes**: Target specific Botble route names (comma-separated).
- **🛡️ Secure Mode**: Dynamic output buffering intercepts accidental `echo` calls during AJAX/JSON requests, preventing DataTables and APIs from breaking.
- **📺 Live Console Preview**: Test your code in a sandbox before saving. See the `[Output]` and `[Return]` values in real-time.
- **✅ Smart Syntax Guard**: Built-in PHP linting prevents you from saving broken code that could crash your site. 
- **🆘 Emergency Rescue Mode**: If a snippet goes wrong, use the **Rescue Button** in Settings or the **CLI Command** (`php artisan snippets:rescue`) to disable all active snippets instantly and restore access.
- **💎 Premium DX**: Integrated with CodeMirror for a smooth, VS-Code-like editing experience with error highlighting.

---

## 🛠️ Usage Examples

- **Custom Analytics**: Inject server-side tracking only on the Checkout Success route.
- **Dynamic CSS/JS**: Add conditional meta tags or styles based on the user's role.
- **API Extensions**: Add custom endpoints or data modifiers to the native Botble API.
- **Dev Tools**: Quickly dump variables or debug hooks without refreshing the browser.

---

## 🛑 Security & Safety

We take stability seriously. Snippets are executed within a controlled environment that:
1. Validates syntax on save.
2. Catches Runtime Exceptions and mapping them to line numbers.
3. Provides a "Nuclear Option" CLI command to kill all snippets if a loop occurs.

## 📄 Documentation

Comprehensive documentation can be found in the `docs-pro` folder, ready for the **Docs Pro** plugin.

---

### Developed with ❤️ by [Entomai](https://entomai.com)
*Empowering the local Laravel & Botble community.*
