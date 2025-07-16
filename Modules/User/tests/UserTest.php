<?php

namespace Modules\User\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user creation
     */
    public function test_can_create_user(): void
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'phone' => '+1234567890',
            'role_id' => 1,
            'zone_id' => 1,
            'city_id' => 1,
            'rank_id' => 2,
            'referrer_id' => 1,
            'branch_id' => 1,
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('+1234567890', $user->phone);
    }

    /**
     * Test user relationships
     */
    public function test_user_has_relationships(): void
    {
        $user = User::factory()->create();

        $this->assertTrue(method_exists($user, 'role'));
        $this->assertTrue(method_exists($user, 'zone'));
        $this->assertTrue(method_exists($user, 'city'));
        $this->assertTrue(method_exists($user, 'rank'));
        $this->assertTrue(method_exists($user, 'referrer'));
        $this->assertTrue(method_exists($user, 'branch'));
        $this->assertTrue(method_exists($user, 'parent'));
        $this->assertTrue(method_exists($user, 'children'));
        $this->assertTrue(method_exists($user, 'referredUsers'));
    }
} 