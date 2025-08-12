<?php

namespace Modules\Zone\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Zone\App\Models\Zone;
use Modules\Zone\Tests\TestCase;

class ZoneTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_get_all_zones()
    {
        Zone::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/zones/all');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_get_paginated_zones()
    {
        Zone::factory()->count(15)->create();

        $response = $this->getJson('/api/v1/zones');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
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

    public function test_can_get_single_zone()
    {
        $zone = Zone::factory()->create();

        $response = $this->getJson("/api/v1/zones/{$zone->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $zone->id,
                    'title' => $zone->title,
                    'description' => $zone->description,
                    'status' => $zone->status
                ]
            ]);
    }

    public function test_can_create_zone()
    {
        $zoneData = [
            'title' => 'Test Zone',
            'description' => 'Test zone description',
            'status' => true
        ];

        $response = $this->postJson('/api/v1/zones', $zoneData);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'Test Zone',
                    'description' => 'Test zone description',
                    'status' => true,
                    'status_label' => 'active'
                ]
            ]);

        $this->assertDatabaseHas('zones', [
            'title' => 'Test Zone',
            'description' => 'Test zone description',
            'status' => true
        ]);
    }

    public function test_can_update_zone()
    {
        $zone = Zone::factory()->create();
        $updateData = [
            'title' => 'Updated Zone',
            'description' => 'Updated zone description',
            'status' => false
        ];

        $response = $this->putJson("/api/v1/zones/{$zone->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'title' => 'Updated Zone',
                    'description' => 'Updated zone description',
                    'status' => false,
                    'status_label' => 'inactive'
                ]
            ]);

        $this->assertDatabaseHas('zones', [
            'id' => $zone->id,
            'title' => 'Updated Zone',
            'description' => 'Updated zone description',
            'status' => false
        ]);
    }

    public function test_can_delete_zone()
    {
        $zone = Zone::factory()->create();

        $response = $this->deleteJson("/api/v1/zones/{$zone->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Zone deleted successfully']);

        $this->assertSoftDeleted('zones', ['id' => $zone->id]);
    }

    public function test_can_restore_deleted_zone()
    {
        $zone = Zone::factory()->create();
        $zone->delete();

        $response = $this->postJson("/api/v1/zones/{$zone->id}/restore");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Zone restored successfully']);

        $this->assertDatabaseHas('zones', ['id' => $zone->id]);
    }

    public function test_can_force_delete_zone()
    {
        $zone = Zone::factory()->create();
        $zone->delete();

        $response = $this->deleteJson("/api/v1/zones/{$zone->id}/force");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Zone permanently deleted']);

        $this->assertDatabaseMissing('zones', ['id' => $zone->id]);
    }

    public function test_can_toggle_zone_status()
    {
        $zone = Zone::factory()->create(['status' => true]);

        $response = $this->patchJson("/api/v1/zones/{$zone->id}/toggle-status");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'status' => false,
                    'status_label' => 'inactive'
                ]
            ]);

        $this->assertDatabaseHas('zones', [
            'id' => $zone->id,
            'status' => false
        ]);
    }

    public function test_can_search_zones()
    {
        Zone::factory()->create(['title' => 'North Zone']);
        Zone::factory()->create(['title' => 'South Zone']);
        Zone::factory()->create(['title' => 'East Zone']);

        $response = $this->getJson('/api/v1/zones/search?title=North');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'title' => 'North Zone'
                    ]
                ]
            ]);
    }

    public function test_can_get_trashed_zones()
    {
        $activeZone = Zone::factory()->create();
        $deletedZone = Zone::factory()->create();
        $deletedZone->delete();

        $response = $this->getJson('/api/v1/zones/trash');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJson([
                'data' => [
                    [
                        'id' => $deletedZone->id,
                        'title' => $deletedZone->title
                    ]
                ]
            ]);
    }

    public function test_validation_errors_on_create()
    {
        $response = $this->postJson('/api/v1/zones', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_validation_errors_on_update()
    {
        $zone = Zone::factory()->create();

        $response = $this->putJson("/api/v1/zones/{$zone->id}", ['title' => '']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_returns_404_for_nonexistent_zone()
    {
        $response = $this->getJson('/api/v1/zones/999');

        $response->assertStatus(404);
    }
} 