<?php

namespace Tests\Feature\App\Http\Controllers;

use App\User;
use Tests\TestCase;
use App\InvoiceSerie;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemSerieControllerTest extends TestCase
{
    /** @test */
    public function itCanGetSerieList()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('GET', '/api/series')
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
    public function itCanGetSingleSerie()
    {
        $newSerie = factory(InvoiceSerie::class)->create();
        
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('GET', '/api/series/' . $newSerie->id)
            ->assertStatus(200)
            ->assertJson($newSerie->toArray());
    }

    /** @test */
    public function itCanCreateSerie()
    {
        $serieData = factory(InvoiceSerie::class)->raw();
        
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('POST', '/api/series', $serieData)
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'prefix',
                'next_number',
                'created_at',
            ])
            ->assertJson($serieData);

        $this->assertDatabaseHas((new InvoiceSerie())->getTable(), $serieData);
    }

    /** @test */
    public function itCanUpdateSerie()
    {
        $newSerie = factory(InvoiceSerie::class)->create();
        $updateData = factory(InvoiceSerie::class)->raw();
        
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('PATCH', '/api/series/' . $newSerie->id, $updateData)
            ->assertStatus(200)
            ->assertJson($updateData);
    }

    /** @test */
    public function itCanDeleteSerie()
    {
        $newSerie = factory(InvoiceSerie::class)->create();
        
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('DELETE', '/api/series/' . $newSerie->id)
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
            ])
            ->assertJson([
                'id' => $newSerie->id,
            ]);

        $this->assertDatabaseMissing((new InvoiceSerie())->getTable(), [
            'id' => $newSerie->id,
        ]);
    }
}
