<?php

namespace App\Upgrades;
//use spatie permission models
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Upgrade72 extends BaseUpgrade
{

    public $versionName = "1.7.45";
    //Runs or migrations to be done on this version
    public function run()
    {
       //add view_report_on_app to the permissions table
        $permission = new Permission();
        $permission->name = 'view_report_on_app';
        $permission->guard_name = 'web';
        $permission->save();
        //add view_report_on_app to the manager role
        $role = Role::where('name', 'manager')->first();
        $role->givePermissionTo('view_report_on_app');

        //make commission column in vendor table nullable
        Schema::table('vendors', function ($table) {
            $table->double('commission', 8, 2)->nullable()->change();
        });

        //add approved column to the products table if it does not exist
        if (!Schema::hasColumn('products', 'approved')) {
            Schema::table('products', function ($table) {
                $table->boolean('approved')->default(1);
            });
        }


        //first or create permission: auto-approve-product
        $permission = new Permission();
        $permission->name = 'auto-approve-product';
        $permission->guard_name = 'web';
        $permission->save();
        //add auto-approve-product to the admin & city-admin roles
        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->givePermissionTo('auto-approve-product');
        $cityAdminRole = Role::where('name', 'city-admin')->first();
        $cityAdminRole->givePermissionTo('auto-approve-product');
        //add `view-product-requests` permission to the admin role
        $permission = new Permission();
        $permission->name = 'view-product-requests';
        $permission->guard_name = 'web';
        $permission->save();
        //add view-product-requests to the admin role
        $adminRole->givePermissionTo('view-product-requests');
        $cityAdminRole->givePermissionTo('view-product-requests');

        //add new column to vendors table
        if (!\Schema::hasColumn('vendors', 'prepare_time_unit')) {
            \Schema::table('vendors', function ($table) {
                $table->enum('prepare_time_unit', ['minutes', 'hours', 'days', 'weeks', 'months'])->default('minutes')->after('prepare_time');
            });
        }

        //add new column to vendors table
        if (!\Schema::hasColumn('vendors', 'delivery_time_unit')) {
            \Schema::table('vendors', function ($table) {
                $table->enum('delivery_time_unit', ['minutes', 'hours', 'days', 'weeks', 'months'])->default('minutes')->after('delivery_time');
            });
        }


        //add permision to admin role
        $permission = Permission::findOrCreate('order-product-visibilities', 'web');
        //add permision to admin role
        $adminRole = Role::findOrCreate('admin');
        $adminRole->givePermissionTo($permission);


        //make unit column nullable in products table
        if (\Schema::hasColumn('products', 'unit')) {
            \Schema::table('products', function ($table) {
                $table->string('unit')->nullable()->change();
            });
        }

        //migrate: product_timings table
        $tableExists = \Schema::hasTable('product_timings');
        if (!$tableExists) {
            \Artisan::call('migrate', [
                '--path' => "database/migrations/2024_05_08_101308_create_product_timings_table.php",
                '--force' => true,
            ]);
        }


        //add columns to order_products table: options_price
        if (!\Schema::hasColumn('order_products', 'options_price')) {
            \Schema::table('order_products', function ($table) {
                $table->double('options_price', 8, 2)->default(0)->nullable()->after('options_ids');
            });
        }

        // product_price
        if (!\Schema::hasColumn('order_products', 'product_price')) {
            \Schema::table('order_products', function ($table) {
                $table->double('product_price', 8, 2)->default(0)->nullable()->after('options_ids');
            });
        }



        //add service price and options price to service orders
        if (!\Schema::hasColumn('order_services', 'options_price')) {
            \Schema::table('order_services', function ($table) {
                $table->double('options_price', 8, 2)->default(0)->nullable()->after('options_ids');
            });
        }

        // product_price
        if (!\Schema::hasColumn('order_services', 'service_price')) {
            \Schema::table('order_services', function ($table) {
                $table->double('service_price', 8, 2)->default(0)->nullable()->after('options_ids');
            });
        }


        //add allow_pickup to payment_methods
        if (!\Schema::hasColumn('payment_methods', 'allow_pickup')) {
            \Schema::table('payment_methods', function ($table) {
                $table->boolean('allow_pickup')->default(true)->after('use_wallet');
            });
        }

        //add allow_pickup to payment_method_vendor table
        if (!\Schema::hasColumn('payment_method_vendor', 'allow_pickup')) {
            \Schema::table('payment_method_vendor', function ($table) {
                $table->boolean('allow_pickup')->default(true)->after('vendor_id');
            });
        }
    }
}