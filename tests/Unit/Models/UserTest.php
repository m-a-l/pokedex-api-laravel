<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function usersTableExists()
    {
        $this->assertTrue(Schema::hasTable('users'));
    }

    /** @test  */
    public function usersDatabaseHasExpectedColumns()
    {
        $this->assertTrue(Schema::hasColumns('users', [
            'id','name', 'email', 'email_verified_at', 'password'
        ]), 1);
    }
}
