<?php

namespace Botble\Snippets\Forms;

use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Snippets\Http\Requests\SnippetsRequest;
use Botble\Snippets\Models\Snippets;

class SnippetsForm extends FormAbstract
{
    public function setup(): void
    {
        $targets = \Botble\Snippets\Enums\SnippetTargetEnum::labels();

        $this
            ->model(Snippets::class)
            ->setValidatorClass(SnippetsRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('description', \Botble\Base\Forms\Fields\TextareaField::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => 3,
                    'placeholder' => 'What does this snippet do?'
                ]
            ])
            ->add('console_ui', 'html', [
                'html' => '
                <style>
                    .CodeMirror { min-height: 400px !important; }
                    .error-line-highlight { background-color: rgba(220, 53, 69, 0.3) !important; border-left: 4px solid #dc3545 !important; }
                </style>
                <div class="mb-2 d-flex gap-2">
                    <button type="button" class="btn btn-success" id="btn-run-snippet"><i class="ti ti-play"></i> Run Preview</button>
                    <button type="button" class="btn btn-warning" id="btn-clear-console"><i class="ti ti-eraser"></i> Clear Console</button>
                    <span id="console-spinner" style="display:none;" class="spinner-border spinner-border-sm text-primary ms-2" role="status"></span>
                </div>
                <div id="snippet-console" style="display:none; background: #1e1e1e; border: 1px solid #333; border-radius: 5px; margin-top: 15px; margin-bottom: 20px;">
                    <div style="background: #333; padding: 5px 10px; color: #ccc; font-size: 12px; border-bottom: 1px solid #222; border-top-left-radius: 5px; border-top-right-radius: 5px;">
                        <i class="ti ti-terminal"></i> Console Output
                    </div>
                    <div id="snippet-console-body" style="padding: 15px; color: #1fd655; max-height: 400px; overflow: auto; font-family: \'Consolas\', \'Courier New\', monospace; font-size: 13px; white-space: pre-wrap;"></div>
                </div>
                <!-- Inline scripts -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const targetSelect = document.querySelector("select[name=target]");
                        const rulesWrapper = document.querySelector(".target-custom-rules");
                        
                        function toggleRules() {
                            if (targetSelect && targetSelect.value === "custom") {
                                rulesWrapper.style.display = "block";
                            } else if (rulesWrapper) {
                                rulesWrapper.style.display = "none";
                            }
                        }
                        
                        if (targetSelect) {
                            targetSelect.addEventListener("change", toggleRules);
                            toggleRules();
                        }

                        // Run Preview logic
                        const runBtn = document.getElementById("btn-run-snippet");
                        const clearBtn = document.getElementById("btn-clear-console");
                        const consoleWrapper = document.getElementById("snippet-console");
                        const consoleDiv = document.getElementById("snippet-console-body");
                        const spinner = document.getElementById("console-spinner");
                        
                        if (runBtn) {
                            runBtn.addEventListener("click", function(e) {
                                e.preventDefault();
                                let code = "";
                                const cmNode = document.querySelector(".CodeMirror");
                                if (cmNode && cmNode.CodeMirror) {
                                    code = cmNode.CodeMirror.getValue();
                                    // Clear prior highlights
                                    const doc = cmNode.CodeMirror.getDoc();
                                    doc.eachLine(function(line) {
                                        doc.removeLineClass(line, "background", "error-line-highlight");
                                    });
                                } else {
                                    code = document.querySelector("textarea[name=code]").value;
                                }
                                
                                spinner.style.display = "inline-block";
                                btnText = runBtn.innerHTML;
                                runBtn.innerHTML = "Running...";
                                runBtn.disabled = true;

                                $.ajax({
                                    url: "' . route('snippets.run-preview') . '",
                                    type: "POST",
                                    data: {
                                        _token: document.querySelector("meta[name=csrf-token]").content,
                                        code: code
                                    },
                                    success: function(res) {
                                        consoleWrapper.style.display = "block";
                                        consoleDiv.innerHTML += "<div><strong style=\"color:#ccc;\">[Output]</strong><br>" + (res.data.output || "<i style=\"color:#666;\">(no output)</i>") + "</div>";
                                        consoleDiv.innerHTML += "<div style=\"color:#569cd6; margin-top:5px;\"><strong>[Return]</strong> " + res.data.result + "</div><hr style=\"border-color: #333;\">";
                                        consoleDiv.scrollTop = consoleDiv.scrollHeight;
                                    },
                                    error: function(err) {
                                        consoleWrapper.style.display = "block";
                                        let errMsg = err.responseJSON ? err.responseJSON.message : err.responseText;
                                        consoleDiv.innerHTML += "<div style=\"color:#f44336;\"><strong>[Error]</strong><br>" + errMsg + "</div><hr style=\"border-color: #333;\">";
                                        consoleDiv.scrollTop = consoleDiv.scrollHeight;
                                        
                                        // Visual inline error highlighting!
                                        if (err.responseJSON && err.responseJSON.line && cmNode && cmNode.CodeMirror) {
                                            const doc = cmNode.CodeMirror.getDoc();
                                            const errLine = err.responseJSON.line - 1; // CodeMirror lines are 0-indexed
                                            
                                            doc.addLineClass(errLine, "background", "error-line-highlight");
                                            cmNode.CodeMirror.scrollIntoView({line: errLine, ch: 0}, 200);
                                            
                                            // Automatically clear the red backdrop if they type anything again
                                            cmNode.CodeMirror.on("change", function clearErr(instance) {
                                                doc.removeLineClass(errLine, "background", "error-line-highlight");
                                                instance.off("change", clearErr);
                                            });
                                        }
                                    },
                                    complete: function() {
                                        spinner.style.display = "none";
                                        runBtn.innerHTML = btnText;
                                        runBtn.disabled = false;
                                    }
                                });
                            });
                        }

                        if (clearBtn) {
                            clearBtn.addEventListener("click", function(e) {
                                e.preventDefault();
                                consoleDiv.innerHTML = "";
                                consoleWrapper.style.display = "none";
                            });
                        }
                    });
                </script>
                ',
            ])
            ->add('code', \Botble\Base\Forms\Fields\CodeEditorField::class, [
                'label' => 'PHP Code',
                'required' => true,
                'attr' => [
                    'data-mode' => 'php',
                ],
                'value' => $this->getModel()->code ?: "<?php\n\n// Your custom PHP code here\n",
            ])
            ->add('target', SelectField::class, [
                'label' => 'Execute Target',
                'required' => true,
                'choices' => $targets,
                'help_block' => [
                    'text' => 'Where should this snippet be executed?',
                ]
            ])
            ->add('route_rules', TextField::class, [
                'label' => 'Route Rules',
                'wrapper' => [
                    'class' => 'form-group mb-3 target-custom-rules',
                ],
                'help_block' => [
                    'text' => 'Specify exact route names or regular expressions (e.g. public.index). Separate multiple with commas. Leave blank to run on all routes matching the selected target.',
                ]
            ])
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
