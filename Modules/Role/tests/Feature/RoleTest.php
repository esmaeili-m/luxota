<?php

namespace Modules\Role\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Role\App\Models\Role;
use Modules\Role\Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_get_all_roles()
    {
        Role::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/roles/all');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_get_paginated_roles()
    {
        Role::factory()->count(15)->create();

        $response = $this->getJson('/api/v1/roles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'status',
                        'status_label',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'current_page',
                'last_page',
                'per_page',
                'total'
            ]);
    }

    public function test_can_get_single_role()
    {
        $role = Role::factory()->create();

        $response = $this->getJson("/api/v1/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $role->id,
                    'title' => $role->title,
                    'status' => $role->status
                ]
            ]);
    }

    public function test_can_create_role()
    {
        $roleData = [
            'title' => 'Test Role',
            'status' => true
        ];

        $response = $this->postJson('/api/v1/roles', $roleData);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'Test Role',
                    'status' => true,
                    'status_label' => 'active'
                ]
            ]);

        $this->assertDatabaseHas('roles', [
            'title' => 'Test Role',
            'status' => true
        ]);
    }

    public function test_can_update_role()
    {
        $role = Role::factory()->create();
        $updateData = [
            'title' => 'Updated Role',
            'status' => false
        ];

        $response = $this->putJson("/api/v1/roles/{$role->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'Updated Role',
                    'status' => false,
                    'status_label' => 'inactive'
                ]
            ]);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'title' => 'Updated Role',
            'status' => false
        ]);
    }

    public function test_can_delete_role()
    {
        $role = Role::factory()->create();

        $response = $this->deleteJson("/api/v1/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Role deleted successfully']);

        $this->assertSoftDeleted('roles', ['id' => $role->id]);
    }

    public function test_can_restore_deleted_role()
    {
        $role = Role::factory()->create();
        $role->delete();

        $response = $this->postJson("/api/v1/roles/{$role->id}/restore");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Role restored successfully']);

        $this->assertDatabaseHas('roles', ['id' => $role->id]);
    }

    public function test_can_force_delete_role()
    {
        $role = Role::factory()->create();
        $role->delete();

        $response = $this->deleteJson("/api/v1/roles/{$role->id}/force");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Role permanently deleted']);

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    public function test_can_toggle_role_status()
    {
        $role = Role::factory()->create(['status' => true]);

        $response = $this->patchJson("/api/v1/roles/{$role->id}/toggle-status");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'status' => false,
                    'status_label' => 'inactive'
                ]
            ]);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'status' => false
        ]);
    }

    public function test_can_search_roles()
    {
        Role::factory()->create(['title' => 'Admin Role']);
        Role::factory()->create(['title' => 'User Role']);
        Role::factory()->create(['title' => 'Manager Role']);

        $response = $this->getJson('/api/v1/roles/search?title=Admin');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'title' => 'Admin Role'
                    ]
                ]
            ]);
    }

    public function test_can_get_trashed_roles()
    {
        $activeRole = Role::factory()->create();
        $deletedRole = Role::factory()->create();
        $deletedRole->delete();

        $response = $this->getJson('/api/v1/roles/trash');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'id' => $deletedRole->id,
                        'title' => $deletedRole->title
                    ]
                ]
            ]);
    }

    public function test_validation_errors_on_create()
    {
        $response = $this->postJson('/api/v1/roles', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_validation_errors_on_update()
    {
        $role = Role::factory()->create();

        $response = $this->putJson("/api/v1/roles/{$role->id}", [
            'title' => ''
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_returns_404_for_nonexistent_role()
    {
        $response = $this->getJson('/api/v1/roles/999');

        $response->assertStatus(404)
            ->assertJson(['error' => 'Not found']);
    }
} 