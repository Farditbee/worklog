<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Data User';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Hidden::make('password')
                    ->default('password123')
                    ->dehydrateStateUsing(fn ($state) => bcrypt('password123')),
                Forms\Components\Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'penanggung_jawab' => 'Penanggung Jawab',
                        'tenaga_ahli' => 'Tenaga Ahli',
                    ])
                    ->required(),
                Forms\Components\Select::make('tenaga_ahli')
                    ->options([
                        's1' => 'S1',
                        's2' => 'S2',
                        's3' => 'S3',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'admin' => 'success',
                        'user' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('tenaga_ahli')
                    ->searchable()
                    ->placeholder('Tidak ada'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'penanggung_jawab' => 'Penanggung Jawab',
                        'tenaga_ahli' => 'Tenaga Ahli',
                    ]),
                Tables\Filters\SelectFilter::make('tenaga_ahli')
                    ->label('Tenaga Ahli')
                    ->options([
                        's1' => 'S1',
                        's2' => 'S2',
                        's3' => 'S3',
                    ])
                    ->query(function ($query, array $data) {
                        if (filled($data['value'])) {
                            return $query->where('role', 'tenaga_ahli')
                                        ->where('tenaga_ahli', $data['value']);
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
