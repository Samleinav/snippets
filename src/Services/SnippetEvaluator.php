<?php

namespace Botble\Snippets\Services;

use Botble\Snippets\Models\Snippets;
use Throwable;
use Illuminate\Support\Facades\Log;

class SnippetEvaluator
{
    public function execute(Snippets $snippet, bool $throwException = false)
    {
        try {
            // Replace opening <?php with spaces to perfectly preserve line numbers for exact Error tracking
            $code = preg_replace('/^<\?php/i', '     ', $snippet->code);
            $code = preg_replace('/\?>\s*$/i', '', $code);
            
            // Execute the code inside an isolated closure to prevent scope leaking
            $evaluator = function() use ($code) {
                return eval($code);
            };
            
            return $evaluator();
        } catch (Throwable $e) {
            if ($throwException) {
                throw $e;
            }
            
            // Sandbox to protect runtime: grab the exception and log instead of crashing
            Log::error("Snippet Runtime Error - '{$snippet->name}' (ID: {$snippet->id}): " . $e->getMessage(), [
                'code_snippet_id' => $snippet->id,
                'target' => $snippet->target->getValue(),
            ]);
            
            return null;
        }
    }
}
