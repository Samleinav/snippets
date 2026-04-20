<?php

namespace Botble\Snippets\Tables;

use Botble\Snippets\Models\Snippets;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\Actions\Action;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class SnippetsTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Snippets::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('snippets.create'))
            ->addActions([
                EditAction::make()->route('snippets.edit'),
                Action::make('toggle')
                    ->route('snippets.toggle')
                    ->icon('ti ti-power')
                    ->color('info')
                    ->label('Stop / Enable'),
                DeleteAction::make()->route('snippets.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('snippets.edit'),
                \Botble\Table\Columns\EnumColumn::make('target')->title('Target'),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('snippets.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                $query->select([
                    'id',
                    'name',
                    'target',
                    'created_at',
                    'status',
                ]);
            });
    }
}
