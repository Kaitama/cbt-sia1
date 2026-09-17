<?php

namespace App\Filament\Resources\Exams\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Override;

class SubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'subjects';

    protected static string|null $title = 'Pelajaran yang diujiankan';

    #[Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('#')
                    ->rowIndex()
                    ->width('10px'),
                TextColumn::make('name')
                    ->label('Pelajaran')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Tersedia')
                    ->boolean(),
                TextColumn::make('pivot.qty')
                    ->label('Jlh. Soal'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Tambah pelajaran')
                    ->preloadRecordSelect()
                    ->schema(function (AttachAction $action): array {
                        return [
                            $action->getRecordSelect(),
                            TextInput::make('qty')
                                ->label('Jumlah soal')
                                ->required()
                                ->default(10)
                                ->numeric(),
                        ];
                    })
                    ->modalSubmitActionLabel('Tambah')
                    ->attachAnother(false),
            ])
            ->recordActions([
                DetachAction::make()
                    ->label('Singkirkan pelajaran'),
            ])
            ->toolbarActions([
                DetachBulkAction::make()
                    ->label('Singkirkan pelajaran yang dipilih'),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
