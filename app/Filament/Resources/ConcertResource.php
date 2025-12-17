<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConcertResource\Pages;
use App\Models\Concert;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class ConcertResource extends Resource
{
    protected static ?string $model = Concert::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Sección 1: Información Principal
                TextInput::make('title')
                    ->label('Título del Concierto')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('location')
                    ->label('Ubicación / Estadio')
                    ->required()
                    // CORRECCIÓN: Usamos prefixIcon en lugar de icon
                    ->prefixIcon('heroicon-o-map-pin'),

                DateTimePicker::make('date')
                    ->label('Fecha y Hora')
                    ->required()
                    ->native(false),

                // Sección 2: Economía
                TextInput::make('price')
                    ->label('Precio ($)')
                    ->numeric()
                    ->prefix('$')
                    ->maxValue(10000)
                    ->required(),

                TextInput::make('total_tickets')
                    ->label('Total de Entradas')
                    ->numeric()
                    ->minValue(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Evento')
                    ->searchable() // ¡Añade barra de búsqueda automático!
                    ->weight('bold'),

                TextColumn::make('date')
                    ->label('Fecha')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),

                TextColumn::make('location')
                    ->label('Lugar')
                    ->icon('heroicon-o-map-pin'),

                TextColumn::make('price')
                    ->label('Precio')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('total_tickets')
                    ->label('Capacidad')
                    ->numeric()
                    ->sortable(),

                // Columna calculada (cuántos quedan)
                TextColumn::make('sold_tickets')
                    ->label('Vendidos')
                    ->getStateUsing(function ($record) {
                        // Cuenta las filas que NO están disponibles (sold, reserved, etc.)
                        return $record->tickets()->where('status', '!=', 'available')->count();
                    })
                    ->badge()
                    ->color(fn (string $state, $record): string =>
                    $state >= $record->total_tickets ? 'danger' : 'success'
                    ),
            ])
            ->filters([
                // Aquí podríamos poner filtros por fecha, precio, etc.
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
            'index' => Pages\ListConcerts::route('/'),
            'create' => Pages\CreateConcert::route('/create'),
            'edit' => Pages\EditConcert::route('/{record}/edit'),
        ];
    }
}
