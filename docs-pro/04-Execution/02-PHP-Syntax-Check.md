# PHP Syntax Validation

We have baked a virtual linting routine right into the validation request (`SnippetsRequest.php`) when hitting "Save". 

Whenever you formulate your code and hit the Save button, if its status is marked as **Published**:

1. The validation interceptor halts the DB storage process.
2. It strips pseudo tags mapping and forces the engine to run `eval("return; " . $testCode)`.
3. Because we inject an early `return`, the script stops execution immediately but forces the PHP parser to analyze it fully up-front.
4. If semicolons are missing or variables are malformed, it triggers a `ParseError`.
5. The form will violently reject the snippet and present a validation rule error without actually persisting your destructive logic.

_You can only store broken code if you explicitly label its Status as **Draft**._
