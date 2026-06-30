<?php

namespace App\Filament\Resources\Returns;

use App\Filament\Resources\Returns\Pages\EditReturn;
use App\Filament\Resources\Returns\Pages\ListReturns;
use App\Filament\Resources\Returns\Schemas\ReturnForm;
use App\Filament\Resources\Returns\Tables\ReturnsTable;
use App\Models\Returns;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ReturnResource extends Resource
{
    protected static ?string $model = Returns::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUturnLeft;

    protected static ?string $navigationLabel = 'Retur';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'return_number';

    protected static UnitEnum|string|null $navigationGroup = 'Transaksi';

    public static function getNavigationItems(): array
    {
        return array_map(fn ($item) => $item->extraAttributes(['title' => 'Kelola pengajuan retur barang dari pelanggan']), parent::getNavigationItems());
    }

    public static function form(Schema $schema): Schema
    {
        return ReturnForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReturnsTable::configure($table);
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
            'index' => ListReturns::route('/'),
            'edit' => EditReturn::route('/{record}/edit'),
        ];
    }
}
