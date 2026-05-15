<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    public static function canAccess(): bool
    {
        $user = auth('tenant')->user();

        return $user?->is_super_admin || ($user?->hasPermissionTo('view_services', 'tenant') ?? false);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')->required()->maxLength(255)->columnSpanFull(),
                    Textarea::make('description')->rows(3)->columnSpanFull(),
                    TextInput::make('price')
                        ->numeric()
                        ->prefix('€')
                        ->required()
                        ->minValue(0),
                    TextInput::make('duration_minutes')
                        ->numeric()
                        ->required()
                        ->minValue(5)
                        ->suffix('min'),
                    ColorPicker::make('color')->nullable(),
                    Toggle::make('is_active')->default(true)->columnSpanFull(),
                    FileUpload::make('image_path')
                        ->label('Service Image (3:2 ratio recommended — e.g. 900×600 px)')
                        ->image()
                        ->disk('public')
                        ->directory('services')
                        ->imagePreviewHeight('140')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->nullable()
                        ->columnSpanFull()
                        ->getUploadedFileUsing(function (string $file, string|array|null $storedFileNames): ?array {
                            $exists = Storage::disk('public')->exists($file);

                            if (! $exists) {
                                return null;
                            }

                            return [
                                'name' => basename($file),
                                'size' => Storage::disk('public')->size($file),
                                'type' => Storage::disk('public')->mimeType($file),
                                'url' => klinica_asset($file),
                            ];
                        }),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->getStateUsing(fn (Service $record): ?string => $record->image_path ? klinica_asset($record->image_path) : null)
                    ->square()
                    ->toggleable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('price')->money('EUR')->sortable(),
                TextColumn::make('duration_minutes')->suffix(' min')->sortable(),
                ColorColumn::make('color')->toggleable(),
                IconColumn::make('is_active')->boolean()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active'),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
