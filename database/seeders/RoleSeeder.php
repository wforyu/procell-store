<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'category.view', 'category.create', 'category.update', 'category.delete',
            'product.view', 'product.create', 'product.update', 'product.delete',
            'stock_movement.view',
            'supplier.view', 'supplier.create', 'supplier.update', 'supplier.delete',
            'purchase_order.view', 'purchase_order.create', 'purchase_order.update', 'purchase_order.delete',
            'order.view', 'order.create', 'order.update', 'order.delete',
            'order.confirm_payment', 'order.process', 'order.ship', 'order.complete', 'order.cancel',
            'return.view', 'return.create', 'return.update', 'return.delete',
            'return.approve', 'return.reject',
            'banner.view', 'banner.create', 'banner.update', 'banner.delete',
            'expense.view', 'expense.create', 'expense.update', 'expense.delete',
            'customer.view',
            'coupon.view', 'coupon.create', 'coupon.update', 'coupon.delete',
            'bank_account.view', 'bank_account.create', 'bank_account.update', 'bank_account.delete',
            'setting.view', 'setting.update',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions($permissions);

        $stok = Role::firstOrCreate(['name' => 'Stok', 'guard_name' => 'web']);
        $stok->syncPermissions([
            'category.view', 'category.create', 'category.update', 'category.delete',
            'product.view', 'product.create', 'product.update', 'product.delete',
            'stock_movement.view',
            'supplier.view', 'supplier.create', 'supplier.update', 'supplier.delete',
            'purchase_order.view', 'purchase_order.create', 'purchase_order.update', 'purchase_order.delete',
        ]);

        $keuangan = Role::firstOrCreate(['name' => 'Keuangan', 'guard_name' => 'web']);
        $keuangan->syncPermissions([
            'order.view', 'order.update',
            'order.confirm_payment', 'order.process', 'order.ship', 'order.complete', 'order.cancel',
            'return.view', 'return.update', 'return.approve', 'return.reject',
            'expense.view', 'expense.create', 'expense.update', 'expense.delete',
            'coupon.view', 'coupon.update',
            'customer.view',
            'setting.view',
            'bank_account.view',
        ]);

        Role::firstOrCreate(['name' => 'Kasir', 'guard_name' => 'web']);

        $user = User::where('email', 'admin@procell.com')->first();
        if ($user) {
            $user->assignRole('Super Admin');
        }
    }
}
