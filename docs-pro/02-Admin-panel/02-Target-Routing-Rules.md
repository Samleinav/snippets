# Target Routing Rules

When creating a snippet, if you select the **Custom** target, a previously hidden input field labeled **Route Rules** will automatically appear below it.

## How to use Custom Rules

The Route Rules allow you to comma-separate Botble internal route names to lock execution entirely. The snippet will remain dormant on any other non-matching URL.

### Example Rules

If you only want your PHP code triggered when a customer visits the cart and checkout page of your e-commerce module:

`public.cart, public.checkout.information`

If you want your code triggered on public API auth controllers:

`api.auth.login, api.auth.register`

### How does this save resources?

Instead of declaring a Global snippet that runs empty `if(request()->routeIs(...))` conditions 1000 times a day, assigning Route Rules allows the `SnippetsServiceProvider` to evaluate standard execution boundaries natively without ever spinning up the evaluator engine.
