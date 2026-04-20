# Console Preview

One of the biggest risks of executing custom code is bringing down the entire framework if you type something wrong. The Snippets plugin provides a Sandboxed environment to let you evaluate everything securely.

## The Run Preview Button

Above the Code Editor, you will notice a green **Run Preview** button. 
Clicking this triggers the backend evaluator **without saving your code**.

### 1. Simulated Environment
The snippet is sent over AJAX to an implicit controller `SnippetsController@runPreview`. It is treated as if it were a Global snippet. The sandbox will run the closure block in a detached state.

### 2. Standard Output
Any `echo`, `print_r`, or `var_dump` strings inside your block will be captured via Output Buffering and printed beautifully under an `[Output]` console block beneath the editor.

### 3. Return Statements
If you end your snippet securely with a `return $value;`, the Console will print the type evaluation and literal value inside the blue `[Return]` block.

### 4. Error Diagnostics
If your code reaches a Fatal Runtime Error, Exception, or Syntax breakpoint during the evaluation attempt, the Sandbox catches the `Throwable` exception and forwards it to your panel interface. The Editor itself will parse the error mapping, light up the precise faulty line with a soft red `<div class="error-line-highlight">` backdrop to pinpoint the mistake visually, and output the stack string in the terminal block!
