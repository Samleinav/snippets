# Snippets Plugin Overview

Welcome to the **Snippets** plugin documentation for Botble CMS.

The Snippets plugin is a powerful, enterprise-grade tool designed for administrators and developers to execute custom PHP scripts directly within the Botble ecosystem from the comfort of the admin panel. 

It completely eliminates the need to edit core files or write heavy full-fledged plugins just to execute simple hooks, add temporary scripts, or build micro-API logic.

## Key Features

- **Live PHP Sandboxed Editor**: Write your code utilizing our advanced CodeMirror integration.
- **Dynamic Routing**: Decide precisely where you want your PHP to run (Frontend, Backend, APIs, or Custom Routes).
- **Run Preview Console**: Evaluate your code live to check syntax, standard outputs, and return values directly on your browser without crashing your site.
- **Strict Syntax Protection**: Only syntactically correct PHP code is allowed to be published and saved.
- **Secure Mode**: Prevents `echo` statements from breaking native JSON components like DataTables.
- **Rescue Terminal**: A failsafe "panic" button and CLI command to disable all snippets if a snippet creates an unexpected infinite loop or fatal crash.

## Target Audience

This plugin is exclusively designed for **Developers and System Administrators** who have a deep understanding of standard PHP code and want to rapidly customize the platform's behavior.
