<?php

namespace Tests\Feature\App\Http\Controllers;

use App\User;
use App\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerControllerTest extends TestCase
{
    /** @test */
    public function itCanGetCustomerList()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('GET', '/api/customers')
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
    public function itCanGetSingleCustomer()
    {
        $newCustomer = factory(Customer::class)->create();
        
        $user = factory(User::class)->create();
        
        $this->actingAs($user, 'api')
            ->json('GET', '/api/customers/' . $newCustomer->id)
            ->assertStatus(200)
            ->assertJson($newCustomer->toArray());
    }

    /** @test */
    public function itCanCreateCustomer()
    {
        $customerData = factory(Customer::class)->raw();

        $user = factory(User::class)->create();
        
        $this->actingAs($user, 'api')
            ->json('POST', '/api/customers', $customerData)
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'name',
                'company_code',
                'vat_code',
                'country',
                'address',
                'phone_number',
                'email',
                'type',
                'created_at',
                'updated_at',
            ])
            ->assertJson($customerData);

        $this->assertDatabaseHas((new Customer())->getTable(), $customerData);
    }

    /** @test */
    public function itCanUpdateCustomer()
    {
        $newCustomer = factory(Customer::class)->create();
        $updateData = factory(Customer::class)->raw();

        $user = factory(User::class)->create();
        
        $this->actingAs($user, 'api')
            ->json('PUT', '/api/customers/' . $newCustomer->id, $updateData)
            ->assertStatus(200)
            ->assertJson($updateData);
    }

    /** @test */
    public function itCanDeleteCustomer()
    {
        $newCustomer = factory(Customer::class)->create();

        $user = factory(User::class)->create();
        
        $this->actingAs($user, 'api')
            ->json('DELETE', '/api/customers/' . $newCustomer->id)
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
            ])
            ->assertJson([
                'id' => $newCustomer->id,
            ]);

        $this->assertDatabaseMissing((new Customer())->getTable(), [
            'id' => $newCustomer->id,
        ]);
    }
}