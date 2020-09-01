<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Item;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemControllerTest extends TestCase
{
    /** @test */
    public function itCanGetItemList()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('GET', '/api/items')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'current_page',
                'last_page',
                'from',
                'to',
                'per_page',
                'total',
            ]);
    }

    /** @test */
    public function itCanGetSingleItem()
    {
        $newItem = factory(Item::class)->create();
        
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('GET', '/api/items/' . $newItem->id)
            ->assertStatus(200)
            ->assertJson($newItem->toArray());
    }

    /** @test */
    public function itCanCreateItem()
    {
        $itemData = factory(Item::class)->raw();
        
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('POST', '/api/items', $itemData)
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'description',
                'pretax_price',
                'taxes',
                'price',
                'created_at',
            ])
            ->assertJson($itemData);

        $this->assertDatabaseHas((new Item())->getTable(), $itemData);
    }

    /** @test */
    public function itCanUpdateItem()
    {
        $newItem = factory(Item::class)->create();
        $updateData = factory(Item::class)->raw();
        
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('PATCH', '/api/items/' . $newItem->id, $updateData)
            ->assertStatus(200)
            ->assertJson($updateData);
    }

    /** @test */
    public function itCanDeleteItem()
    {
        $newItem = factory(Item::class)->create();
        
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('DELETE', '/api/items/' . $newItem->id)
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
            ])
            ->assertJson([
                'id' => $newItem->id,
            ]);

        $this->assertDatabaseMissing((new Item())->getTable(), [
            'id' => $newItem->id,
        ]);
    }
}