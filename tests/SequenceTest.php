<?php

namespace bfinlay\SpreadsheetSeeder\Tests;

use bfinlay\SpreadsheetSeeder\Tests\Seeds\ParsersTest\UsersCsvParsersSeeder;

use bfinlay\SpreadsheetSeeder\Tests\Seeds\SequenceTest\UsersSeq1Seeder;
use bfinlay\SpreadsheetSeeder\Writers\Database\DatabaseWriter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SequenceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

//        $this->loadMigrationsFrom(__DIR__ . '/SequenceTest');

        // and other test setup steps you need to perform
    }

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
        ], Schema::getColumnListing('customers'));
    }

    /**
     * Seed a CSV file and verify that the CSV filename is used as the table name
     *
     * Seed file and verify sequence counter is incremented to 2
     * Add user to table and verify id number is 3
     */
    public function test_sequence_counter_is_updated()
    {
        $this->seed(UsersCsvParsersSeeder::class);

        $user = DB::table('users')->where('name', 'John')->first();
        // verify parser has converted email from John@Doe.com to john@doe.com
        $this->assertEquals('john@doe.com', $user->email);
        $this->assertEquals(2, DB::table('users')->count());

        // Foo,Foo@Bar.com,2019-01-23 21:38:54,password
        $id = DB::table('users')->insertGetId([
            'name' => 'Francis',
            'email' => 'FrancisNMartin@einrot.com',
            'email_verified_at' => '2023-01-21 11:18:00',
            'password' => 'password'
            ]
        );
        $this->assertEquals(6, $id);
    }

    public function test_table_name_in_seq_column_name()
    {
        $tableSequences = DatabaseWriter::getSequencesForTable('users_seq1');
        $this->seed(UsersSeq1Seeder::class);

        $user = DB::table('users_seq1')->where('name', 'John')->first();
        // verify parser has converted email from John@Doe.com to john@doe.com
        $this->assertEquals('john@doe.com', $user->email);
        $this->assertEquals(2, DB::table('users_seq1')->count());

        // Foo,Foo@Bar.com,2019-01-23 21:38:54,password
        DB::table('users_seq1')->insert([
                'name' => 'Francis',
                'email' => 'FrancisNMartin@einrot.com',
                'email_verified_at' => '2023-01-21 11:18:00',
                'password' => 'password'
            ]
        );
        $user = DB::table('users_seq1')->where('name', 'Francis')->first();
        $this->assertEquals(6, $user->users_seq1_id);
    }

    // test two sequences

    // test fail when sequence doesn't update
    public function test_insert_fails_if_sequence_counter_is_not_updated()
    {
        app()->bind(DatabaseWriter::class, \bfinlay\SpreadsheetSeeder\Tests\SequenceTest\DatabaseWriter::class);
        $this->seed(UsersCsvParsersSeeder::class);

        $user = DB::table('users')->where('name', 'John')->first();
        // verify parser has converted email from John@Doe.com to john@doe.com
        $this->assertEquals('john@doe.com', $user->email);
        $this->assertEquals(2, DB::table('users')->count());

        $this->expectExceptionMessage("Unique violation: 7 ERROR:  duplicate key value violates unique constraint");
        // Foo,Foo@Bar.com,2019-01-23 21:38:54,password
        $id = DB::table('users')->insertGetId([
                'name' => 'Francis',
                'email' => 'FrancisNMartin@einrot.com',
                'email_verified_at' => '2023-01-21 11:18:00',
                'password' => 'password'
            ]
        );
        $this->assertNotEquals(3, $id);
    }

    // table name matches part of an existing sequence name.
    //  For example: table = "users" sequence = "users_seq1_users_seq1_id_seq"
    //  considers column as "seq1_users_seq1_id"
    // migrate and seed both tables.
    //   get list of sequences to verify both exist
    // verify that both have been properly updated.

    // situation where table and column names overlap?
    // table1, seq_id --> table1_seq_id_seq
    // table1_seq, id --> table1_seq_id_seq

    // two tables with same sequence names, or same column names - how is sequence collisison handled?
    // can there be sequence collision across two tables?  sequence name starts with table name.
}