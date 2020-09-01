<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Item;
use App\User;
use App\Invoice;
use App\Customer;
use Tests\TestCase;
use App\InvoiceSerie;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceControllerTest extends TestCase
{
    /** @test */
    public function itCanGetInvoiceList()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('GET', '/api/invoices')
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
    public function itCanGetSingleInvoice()
    {
        $newCustomer = factory(Customer::class)->create();
        $newSerie = factory(InvoiceSerie::class)->create();
        $newInvoice = factory(Invoice::class)->create([
            'customer_id' => $newCustomer->id,
            'invoice_serie_id' => $newSerie->id,
        ]);

        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('GET', '/api/invoices/' . $newInvoice->id)
            ->assertStatus(200)
            ->assertJson($newInvoice->toArray());
    }

    /** @test */
    public function itCanCreateInvoice()
    {
        $this->withoutExceptionHandling();

        $newItem = factory(Item::class)->create();
        $newCustomer = factory(Customer::class)->create();
        $newSerie = factory(InvoiceSerie::class)->create();
        
        $invoiceData = factory(Invoice::class)->raw([
            'customer_id' => $newCustomer->id,
            'invoice_serie_id' => $newSerie->id,
            'items' => [
                [
                    'item_id' => $newItem->id,
                    'quantity' => 2,
                    'unit_pretax_price' => $newItem->pretax_price,
                    'unit_taxes' => $newItem->taxes,
                ]
            ],
        ]);
        
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('POST', '/api/invoices', $invoiceData)
            ->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'customer_id',
                'invoice_serie_id',
                'number',
                'date',
                'due_date',
                'notes',
                'subtotal',
                'taxes',
                'total',
                'sent',
                'created_at',
                'updated_at',
            ]);
    }

    /** @test */
    public function itCanUpdateInvoice()
    {
        $newCustomer = factory(Customer::class)->create();
        $newSerie = factory(InvoiceSerie::class)->create();
        $newInvoice = factory(Invoice::class)->create([
            'customer_id' => $newCustomer->id,
            'invoice_serie_id' => $newSerie->id,
        ]);
        $updateData = factory(Invoice::class)->raw();
        
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('PUT', '/api/invoices/' . $newInvoice->id, $updateData)
            ->assertStatus(200)
            ->assertJson($updateData);
    }

    /** @test */
    public function itCanDeleteInvoice()
    {
        $newCustomer = factory(Customer::class)->create();
        $newSerie = factory(InvoiceSerie::class)->create();
        $newInvoice = factory(Invoice::class)->create([
            'customer_id' => $newCustomer->id,
            'invoice_serie_id' => $newSerie->id,
        ]);
        
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')
            ->json('DELETE', '/api/invoices/' . $newInvoice->id)
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
            ])
            ->assertJson([
                'id' => $newInvoice->id,
            ]);

        $this->assertDatabaseMissing((new Invoice())->getTable(), [
            'id' => $newInvoice->id,
        ]);
    }
}
