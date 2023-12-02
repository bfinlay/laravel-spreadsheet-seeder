<?php

namespace bfinlay\SpreadsheetSeeder\Tests;

use Illuminate\Support\Facades\Schema;

trait AssertsMigrations
{
    public function assertsUsersMigration()
    {
        $this->assertEquals([
            'id',
            'uuid',
            'name',
            'email',
            'email_verified_at',
            'password',
            'created_at',
            'updated_at',
        ], Schema::getColumnListing('users'));
    }

    public function assertsOrdersMigration()
    {
        $this->assertEquals([
            'id',
            'order_date',
            'required_date',
            'shipped_date',
            'status',
            'comments',
            'customer_id',
            'created_at',
            'updated_at'
        ],
            Schema::getColumnListing('orders'));
    }

    public function assertsCustomersMigration()
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

    public function assertsFavoriteNumbersMigration()
    {
        $this->assertEquals([
            'id',
            'created_at',
            'updated_at',
            'user_id',
            'favorite_number'
        ], Schema::getColumnListing('favorite_numbers'));
    }

    public function assertsFakeNamesMigration()
    {
        $this->assertEquals([
            'id',
            'Gender',
            'Title',
            'GivenName',
            'MiddleInitial',
            'Surname',
            'StreetAddress',
            'City',
            'State',
            'ZipCode',
            'created_at',
            'updated_at'
        ], \Schema::getColumnListing('fake_names'));
    }
}