<?php

namespace App\Filament\Resources\Customers;

use App\Filament\Resources\Customers\Pages\ListCustomers;
use App\Filament\Resources\Customers\Pages\ViewCustomer;
use App\Filament\Resources\Customers\RelationManagers\OrdersRelationManager;
use App\Filament\Resources\Customers\Tables\CustomersTable;
use App\Models\Customer;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Support\RawJs;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Pelanggan';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationItems(): array
    {
        return array_map(fn ($item) => $item->extraAttributes(['title' => 'Lihat data dan riwayat belanja pelanggan']), parent::getNavigationItems());
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Nama'),
                TextInput::make('email')
                    ->label('Email'),
                TextInput::make('phone')
                    ->label('Telepon'),
                TextInput::make('address')
                    ->label('Alamat')
                    ->columnSpanFull(),
                TextInput::make('total_spent')
                    ->label('Total Belanja')
                    ->prefix('Rp')
                    ->disabled()
                    ->mask(RawJs::make('(function(){var d=$input.replace(/\D/g,\'\');if(!d)return\'\';var p=[],r=d.length;while(r>0){var t=r>3?3:r;p.unshift(\'9\'.repeat(t));r-=t}return p.join(\'.\')})()'))
                    ->afterStateHydrated(fn (TextInput $component, $state) => $component->state($state ? number_format((int) $state, 0, ',', '.') : '0'))
                    ->columnSpanFull(),
                TextInput::make('created_at')
                    ->label('Bergabung'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return CustomersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomers::route('/'),
            'view' => ViewCustomer::route('/{record}'),
        ];
    }
}
