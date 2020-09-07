<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * users table exists
     * @test
     * @return void
     */
    public function usersTableExists()
    {
        $this->assertTrue(Schema::hasTable('users'));
    }

    /**
    * users database has expected columns
    * @return void
    */
    public function usersDatabaseHasExpectedColumns()
    {
        $this->assertTrue(Schema::hasColumns('users', [
            'id','name', 'email', 'email_verified_at', 'password'
        ]), 1);
    }
}
