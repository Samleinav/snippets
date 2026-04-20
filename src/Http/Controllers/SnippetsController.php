<?php

namespace Botble\Snippets\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Snippets\Http\Requests\SnippetsRequest;
use Botble\Snippets\Models\Snippets;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Snippets\Tables\SnippetsTable;
use Botble\Snippets\Forms\SnippetsForm;

class SnippetsController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans(trans('plugins/snippets::snippets.name')), route('snippets.index'));
    }

    public function index(SnippetsTable $table)
    {
        $this->pageTitle(trans('plugins/snippets::snippets.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/snippets::snippets.create'));

        return SnippetsForm::create()->renderForm();
    }

    public function store(SnippetsRequest $request)
    {
        $form = SnippetsForm::create()->setRequest($request);

        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('snippets.index'))
            ->setNextUrl(route('snippets.edit', $form->getModel()->getKey()))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(Snippets $snippets)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $snippets->name]));

        return SnippetsForm::createFromModel($snippets)->renderForm();
    }

    public function update(Snippets $snippets, SnippetsRequest $request)
    {
        SnippetsForm::createFromModel($snippets)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('snippets.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(Snippets $snippets)
    {
        return DeleteResourceAction::make($snippets);
    }

    public function toggle(Snippets $snippet, \Illuminate\Http\Request $request)
    {
        $snippet->status = $snippet->status->getValue() === \Botble\Base\Enums\BaseStatusEnum::PUBLISHED 
            ? \Botble\Base\Enums\BaseStatusEnum::DRAFT 
            : \Botble\Base\Enums\BaseStatusEnum::PUBLISHED;
        $snippet->save();
        
        $response = $this->httpResponse()->setMessage('Snippet status updated to ' . $snippet->status->label());
        
        if ($request->ajax() || $request->wantsJson()) {
            return $response;
        }
        
        return redirect()->back(); // Supports GET trigger from Action button
    }

    public function runPreview(\Illuminate\Http\Request $request)
    {
        $code = $request->input('code');
        if (! $code) {
            return $this->httpResponse()->setError()->setMessage('No code to run');
        }
        
        $snippet = new Snippets();
        $snippet->id = 999999;
        $snippet->name = 'Preview';
        $snippet->target = \Botble\Snippets\Enums\SnippetTargetEnum::GLOBAL();
        $snippet->code = $code;
        
        ob_start();
        try {
            $evaluator = clone app(\Botble\Snippets\Services\SnippetEvaluator::class);
            $result = $evaluator->execute($snippet, true);
            $output = ob_get_clean();
            
            return $this->httpResponse()->setData([
                'output' => $output,
                'result' => is_string($result) || is_numeric($result) ? $result : gettype($result)
            ])->setMessage('Snippet executed successfully.');
            
        } catch (\Throwable $e) {
            $line = $e->getLine();
            $output = ob_get_clean();
            return response()->json([
                'error' => true,
                'message' => "Runtime Error: " . $e->getMessage() . " on line " . $line,
                'output' => $output,
                'line' => $line
            ], 500);
        }
    }
    
    public function settings()
    {
        $this->pageTitle('Snippets Settings & Rescue');
        return view('plugins/snippets::settings');
    }

    public function rescue()
    {
        \Illuminate\Support\Facades\Artisan::call('snippets:rescue');
        
        return $this->httpResponse()
            ->setNextUrl(route('snippets.index'))
            ->setMessage('Rescue Mode Activated: All snippets have been safely disabled.');
    }
}
