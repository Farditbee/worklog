<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsenResource\Pages;
use App\Models\Absen;
use App\Models\Project;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AbsenResource extends Resource
{
    protected static ?string $model = Absen::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    
    protected static ?string $navigationLabel = 'Absen Tenaga Ahli';
    
    protected static ?string $modelLabel = 'Absen Tenaga Ahli';
    
    protected static ?string $pluralModelLabel = 'Absen Tenaga Ahli';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')
                    ->required()
                    ->label('Tanggal'),
                    
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required()
                    ->label('Project')
                    ->searchable()
                    ->preload(),
                    
                Forms\Components\Select::make('tenaga_ahli')
                    ->options([
                        's1' => 'S1',
                        's2' => 'S2',
                        's3' => 'S3',
                    ])
                    ->required()
                    ->label('Tenaga Ahli')
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('user_id', null)),
                    
                Components\Select::make('user_id')
                    ->label('Nama')
                    ->required()
                    ->options(function (callable $get) {
                        $tenagaAhli = $get('tenaga_ahli');
                        if (!$tenagaAhli) {
                            return [];
                        }
                        return User::where('tenaga_ahli', $tenagaAhli)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->disabled(fn (callable $get): bool => !$get('tenaga_ahli')),
                    
                Forms\Components\Textarea::make('kegiatan')
                    ->required()
                    ->label('Kegiatan')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date('d-m-Y')
                    ->sortable()
                    ->label('Tanggal'),
                    
                Tables\Columns\TextColumn::make('kegiatan')
                    ->wrap()
                    ->label('Kegiatan'),
                    
                Tables\Columns\TextColumn::make('tanda_tangan')
                    ->default('')
                    ->label('Tanda Tangan'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project_id')
                    ->relationship('project', 'name')
                    ->label('Nama Aplikasi')
                    ->placeholder('-- Semua Aplikasi --')
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\SelectFilter::make('tenaga_ahli')
                    ->options([
                        's1' => 'S1',
                        's2' => 'S2',
                        's3' => 'S3',
                    ])
                    ->label('Tenaga Ahli')
                    ->placeholder('-- Semua Jabatan --')
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $value): Builder => $query->whereHas(
                                'user',
                                fn (Builder $query): Builder => $query->where('tenaga_ahli', $value)
                            )
                        );
                    }),
                    
                Tables\Filters\Filter::make('user_filter')
                    ->form([
                        Components\Select::make('user_id')
                            ->label('Nama')
                            ->placeholder('-- Pilih Tenaga Ahli dulu --')
                            ->options(function (callable $get) {
                                $tenagaAhli = $get('../tenaga_ahli');
                                if (!$tenagaAhli) {
                                    return [];
                                }
                                return User::where('tenaga_ahli', $tenagaAhli)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->reactive()
                            ->disabled(function (callable $get) {
                                return !$get('../tenaga_ahli');
                            })
                            ->afterStateUpdated(function (callable $set, $state, callable $get) {
                                if (!$get('../tenaga_ahli')) {
                                    $set('user_id', null);
                                }
                            }),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['user_id'],
                            fn (Builder $query, $userId): Builder => $query->where('user_id', $userId)
                        );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['user_id']) {
                            $user = User::find($data['user_id']);
                            return $user ? 'Nama: ' . $user->name : null;
                        }
                        return null;
                    }),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
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
            'index' => Pages\ListAbsens::route('/'),
            'create' => Pages\CreateAbsen::route('/create'),
            'edit' => Pages\EditAbsen::route('/{record}/edit'),
        ];
    }
    
    protected static function mutateFormDataBeforeCreate(array $data): array
    {
        // Hapus field tenaga_ahli karena tidak ada di database
        unset($data['tenaga_ahli']);
        return $data;
    }
    
    protected static function mutateFormDataBeforeSave(array $data): array
    {
        // Hapus field tenaga_ahli karena tidak ada di database
        unset($data['tenaga_ahli']);
        return $data;
    }
}