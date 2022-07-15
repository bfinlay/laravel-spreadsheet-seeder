<?php

namespace bfinlay\SpreadsheetSeeder\Tests;

use bfinlay\SpreadsheetSeeder\Tests\Seeds\HashTest\UsersCsvHashSeeder;

use Illuminate\Support\Facades\Hash;

class HashTest extends TestCase
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
     * Seed a CSV file and verify that the CSV filename is used as the table name
     */
    public function test_table_name_is_csv_filename()
    {
        $this->seed(UsersCsvHashSeeder::class);

        $user = \DB::table('users')->where('name', 'John')->first();
        $this->assertEquals('John@Doe.com', $user->email);
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertEquals(2, \DB::table('users')->count());
    }
}