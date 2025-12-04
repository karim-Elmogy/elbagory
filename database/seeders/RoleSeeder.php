<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'مدير النظام',
                'slug' => 'admin',
                'description' => 'مدير النظام مع جميع الصلاحيات',
                'permissions' => ['*'],
            ],
            [
                'name' => 'محاسب',
                'slug' => 'accountant',
                'description' => 'إدارة الفواتير والمدفوعات',
                'permissions' => ['invoices.view', 'invoices.create', 'invoices.edit', 'payments.view', 'payments.create'],
            ],
            [
                'name' => 'مسؤول مخزون',
                'slug' => 'inventory_manager',
                'description' => 'إدارة المنتجات والمخزون',
                'permissions' => ['products.view', 'products.create', 'products.edit', 'stock.view', 'stock.manage'],
            ],
            [
                'name' => 'كاشير',
                'slug' => 'cashier',
                'description' => 'إدارة الطلبات والمبيعات',
                'permissions' => ['orders.view', 'orders.create', 'orders.edit'],
            ],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }
    }
}
