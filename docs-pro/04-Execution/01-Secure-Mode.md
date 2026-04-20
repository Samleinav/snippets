# Secure Mode

When developers create snippets, they regularly use `echo "Debugging...";` during tests and accidentally leave them stored.

In older systems, executing native `echo` statements on an Admin or Global hook causes the framework to flush output prematurely. If a user subsequently navigates to a table view (like Datatables), Botble expects an intact `{"data": []}` JSON response. Any stray string (`Debugging...{"data": []}`) corrupts decoding.

## Auto-Suppression
Our custom `SnippetEvaluator` wrapper implements a smart **Secure Mode**.

Whenever snippets run:
1. The output stream evaluates normally.
2. An inspector checks whether `$request->ajax()` or `$request->wantsJson()` is active.
3. If JSON is expected, the Sandbox fully truncates and swallows your raw output to protect the application UI headers.
4. It logs a Debug event in Laravel's native log mentioning "Snippet 'X' produced output during an AJAX request, suppressed to prevent JSON corruption."

_You never need to worry about breaking the internal Botble Grid Layouts._
