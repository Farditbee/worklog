<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorklogResource\Pages;
use App\Filament\Resources\WorklogResource\RelationManagers;
use App\Models\Project;
use App\Models\Worklog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorklogResource extends Resource
{
    protected static ?string $model = Worklog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationLabel = 'Worklog';
    
    protected static ?string $modelLabel = 'Worklog';
    
    protected static ?string $pluralModelLabel = 'Worklog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required()
                    ->default(now()),
                    
                Forms\Components\Select::make('project_id')
                    ->label('Project')
                    ->options(Project::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                    
                Forms\Components\Textarea::make('sebelum')
                    ->label('Sebelum')
                    ->required()
                    ->rows(3),
                    
                Forms\Components\Textarea::make('sesudah')
                    ->label('Sesudah')
                    ->required()
                    ->rows(3),
                    
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan')
                    ->nullable()
                    ->rows(2),
                    
                Forms\Components\FileUpload::make('file')
                    ->label('Gambar')
                    ->directory('worklog-files')
                    ->image()
                    ->maxSize(5120) // 5MB
                    ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'])
                    ->downloadable()
                    ->previewable(false)
                    ->visibility('private')
                    ->storeFileNamesIn('file_name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('sebelum')
                    ->label('Sebelum')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                    
                Tables\Columns\TextColumn::make('sesudah')
                    ->label('Sesudah')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                    
                Tables\Columns\ImageColumn::make('file')
                    ->label('Gambar')
                    ->size(80)
                    ->square()
                    ->extraImgAttributes(['class' => 'rounded-lg object-cover'])
                    ->getStateUsing(function ($record) {
                        return $record->file ? asset('storage/' . $record->file) : null;
                    })
                    ->url(function ($record) {
                        return $record->file ? asset('storage/' . $record->file) : null;
                    })
                    ->openUrlInNewTab()
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project_id')
                    ->label('Project')
                    ->options(Project::all()->pluck('name', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tanggal', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorklogs::route('/'),
            'create' => Pages\CreateWorklog::route('/create'),
            'edit' => Pages\EditWorklog::route('/{record}/edit'),
        ];
    }
}
