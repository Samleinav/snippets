<?php

namespace Botble\Snippets\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class SnippetsRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:220'],
            'description' => ['nullable', 'string', 'max:400'],
            'code' => ['required', 'string'],
            'target' => ['required', Rule::in(\Botble\Snippets\Enums\SnippetTargetEnum::values())],
            'route_rules' => ['nullable', 'string', 'max:400'],
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $code = $this->input('code');
            $status = $this->input('status');
            
            if ($code && $status === BaseStatusEnum::PUBLISHED) {
                try {
                    $testCode = preg_replace('/^<\?php/i', '     ', $code);
                    $testCode = preg_replace('/\?>\s*$/i', '', $testCode);
                    // Prepend return to prevent execution of the code, but trigger the PHP Engine parser
                    // This will throw a ParseError if the syntax is broken (e.g. missing semicolons).
                    eval("return; " . $testCode);
                } catch (\ParseError $e) {
                    $validator->errors()->add('code', 'Syntax Error. Code cannot be published: ' . $e->getMessage() . ' on line ' . $e->getLine());
                } catch (\Throwable $e) {
                    // Ignore other runtime errors since we only care about ParseError here
                }
            }
        });
    }
}
