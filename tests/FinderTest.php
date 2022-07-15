<?php


namespace bfinlay\SpreadsheetSeeder\Tests;


use bfinlay\SpreadsheetSeeder\SpreadsheetSeederSettings;
use bfinlay\SpreadsheetSeeder\Tests\Seeds\FinderTest\FinderSeeder;
use Symfony\Component\Finder\Finder;

class FinderTest extends TestCase
{
    /** @test */
    public function it_runs_the_migrations()
    {
        $this->assertEquals([
            'id',
            'customer_name',
            'contact_last_name',
            'contact_first_name',
            'phone',
            'address_line_1',
            'address_line_2',
            'city',
            'state',
            'postal_code',
            'country',
            'sales_rep_id',
            'credit_limit',
            'created_at',
            'updated_at',
        ], \Schema::getColumnListing('customers'));
    }

    /**
     * @depends it_runs_the_migrations
     */
    public function test_without_finder_all_files()
    {
        $testTables = [
            'offices',
            'employees',
            'customers'
        ];

        $emptyTables = [
            'order_details',
            'orders',
            'payments',
            'product_lines',
            'products',
        ];

        $settings = resolve(SpreadsheetSeederSettings::class);
        $settings->file = '/../../../bfinlay/laravel-excel-seeder-test-data/FinderTest/*.xlsx';

        $this->seed(FinderSeeder::class);

        $this->assertTableHasExpectedData($testTables);
        foreach ($emptyTables as $table) $this->assertEquals(0, \DB::table($table)->count());
    }

    /**
     * @depends it_runs_the_migrations
     */
    public function test_finder_all_files()
    {
        $testTables = [
            'offices',
            'employees',
            'customers'
        ];

        $emptyTables = [
            'order_details',
            'orders',
            'payments',
            'product_lines',
            'products',
        ];

        $settings = resolve(SpreadsheetSeederSettings::class);

        $f = [];
        $finder = new Finder();
        $finder->in(base_path() . '/../../../bfinlay')->depth(0);
        foreach ($finder as $f) $files[] = $f->getFilename();

        $settings->file = (new Finder)->in(base_path() . '/../../../bfinlay/laravel-excel-seeder-test-data/FinderTest/')->name('*.xlsx')->sortByName();

        $this->seed(FinderSeeder::class);

        $this->assertTableHasExpectedData($testTables);
        foreach ($emptyTables as $table) $this->assertEquals(0, \DB::table($table)->count());
    }

    /**
     * @depends it_runs_the_migrations
     */
    public function test_finder_exclude_customers()
    {
        $testTables = [
            'offices',
            'employees',
        ];

        $emptyTables = [
            'customers',
            'order_details',
            'orders',
            'payments',
            'product_lines',
            'products',
        ];

        $settings = resolve(SpreadsheetSeederSettings::class);

        $settings->file =
            (new Finder)
                ->in(base_path() . '/../../../bfinlay/laravel-excel-seeder-test-data/FinderTest/')
                ->name('*.xlsx')
                ->notName('*customers*')
                ->sortByName();

        $this->seed(FinderSeeder::class);

        $this->assertTableHasExpectedData($testTables);
        foreach ($emptyTables as $table) $this->assertEquals(0, \DB::table($table)->count());
    }

    /**
     * @param $table string|array
     */
    public function assertTableHasExpectedData($table)
    {
        if (is_string($table)) $table = [$table];

        if (in_array('customers', $table)) {
            $customer = \DB::table('customers')->where('id', '=', 103)->first();
            $this->assertEquals('Schmitt', $customer->contact_last_name);
            $this->assertEquals(122, \DB::table('customers')->count());
        }

        if (in_array('employees', $table)) {
            $employee = \DB::table('employees')->where('id', 1216)->first();
            $this->assertEquals('Steve', $employee->first_name);
            $this->assertEquals('Patterson', $employee->last_name);
            $this->assertEquals(23, \DB::table('employees')->count());
        }

        if (in_array('offices', $table)) {
            $offices = \DB::table('offices')->where('id', 4)->first();
            $this->assertEquals('Paris', $offices->city);
            $this->assertEquals(7, \DB::table('offices')->count());
        }

        if (in_array('order_details', $table)) {
            $orderDetail = \DB::table('order_details')->where('id', 470)->first();
            $this->assertEquals('S24_2840', $orderDetail->product_code);
            $this->assertEquals(2996, \DB::table('order_details')->count());
        }

        if (in_array('orders', $table)) {
            $order = \DB::table('orders')->where('id', 10407)->first();
            $this->assertEquals(450, $order->customer_id);
            $this->assertEquals('On Hold', $order->status);
            $this->assertEquals(326, \DB::table('orders')->count());
        }

        if (in_array('payments', $table)) {
            $payment = \DB::table('payments')->where('id', 18)->first();
            $this->assertEquals(101244.59, $payment->amount);
            $this->assertEquals(273, \DB::table('payments')->count());
        }

        if (in_array('product_lines', $table)) {
            $product_line = \DB::table('product_lines')->where('id', 7)->first();
            $this->assertEquals('Vintage Cars', $product_line->product_line);
            $this->assertEquals(7, \DB::table('product_lines')->count());
        }

        if (in_array('products', $table)) {
            $product = \DB::table('products')->where('id', 85)->first();
            $this->assertEquals("1980's GM Manhattan Express", $product->name);
            $this->assertEquals(110, \DB::table('products')->count());
        }
    }
}