# Creating Snippets

To create a new PHP Snippet, follow these steps:

1. Navigate to **Snippets** in the left sidebar menu of your admin panel.
2. Click the **Create** button.
3. You will be greeted with the Snippet editor form.

## Understanding the Fields

### 1. Name
A readable title for your script to identify it internally (e.g. `User Registration Hook`).

### 2. Description
Optional. A brief summary of what the code achieves, acting as internal documentation.

### 3. Target
Crucial configuration assigning the scope of the snippet.
- **Global**: Runs on every single request hitting your platform.
- **Admin**: Runs exclusively inside the Botble dashboard (`/admin/*`).
- **Frontend**: Runs exclusively facing your customers (Public views).
- **Api**: Runs purely for headless requests (`/api/*`).
- **Custom**: Opens the Route Rules text box, where you can limit the execution to specific route names.

### 4. Code Block
A dynamic PHP editor containing your raw logic. You do not need to enclose it inside `<?php` and `?>` tags as the system handles it under the hood. You can type native procedural PHP code, use Botble facades (`DB::`, `Log::`, `Route::`) and more.

### 5. Status
- **Published**: The snippet will become "active" and execute in the target boundaries immediately.
- **Draft / Pending**: The snippet is safely stored but entirely deactivated. Use this when you are creating work in progress.
