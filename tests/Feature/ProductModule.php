<?php

namespace Tests\Feature;

use App\Category;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Product;
class ProductModule extends TestCase
{

    use RefreshDatabase;



    /** @test */

    public function it_a_create_new_product()
    {
        $this->withoutExceptionHandling();
        $categoryCreated = factory(Category::class)->create();
        $this->actingAs($this->createUser())
            ->postJson(route('products.store'),[
            'name' => 'Memoria Ram',
            'price' => 155,
            'category_id' => $categoryCreated->id
        ])->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'name' => 'Memoria Ram',
            'price' => 155,
            'category_id' => $categoryCreated->id
        ]);
    }

    /** @test */

    public function it_a_create_new_product_required_name()
    {
        $categoryCreated = factory(Category::class)->create();
        $this->actingAs($this->createUser())
            ->postJson(route('products.store'),[
            'price' => 155,
            'category_id' => $categoryCreated->id
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message','errors' => ['name']
            ]);
    }

    /** @test */

    public function it_a_create_new_product_required_price()
    {
        $categoryCreated = factory(Category::class)->create();
        $this->actingAs($this->createUser())
            ->postJson(route('products.store'),[
            'name' => 'Memoria Ram',
            'category_id' => $categoryCreated->id
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message','errors' => ['price']
            ]);
    }

    /** @test */

    public function it_a_create_new_product_required_category()
    {

        $this->actingAs($this->createUser())
            ->postJson(route('products.store'),[
            'name' => 'Memoria Ram',
            'price' => 155,
        ])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message','errors' => ['category_id']
            ]);
    }


    /** @test */

    public function it_update_a_product()
    {

        $ProductCreated = factory(Product::class)->create(['name' => 'memoria ram']);
        $categoryCreated = factory(Category::class)->create();
        $this->actingAs($this->createUser())
            ->putJson(route('products.update', $ProductCreated->id), [
            'name' => 'Monitor',
            'category_id' => $categoryCreated->id,
            'price' => 10.0
        ])->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'name' => 'Monitor',
            'id' => $ProductCreated->id
        ]);

    }


    /** @test */

    public function it_update_a_product_required_a_exists_product()
    {

        $this->actingAs($this->createUser())
            ->putJson(route('products.update', rand(1,10)), [
            'name' => 'Monitor'
        ])->assertStatus(404);

    }

    /** @test */

    public function it_show_a_product()
    {
        $ProductCreated = factory(Product::class)->create(['name' => 'memoria ram']);

        $this->actingAs($this->createUser())
            ->getJson(route('products.show', $ProductCreated->id))
            ->assertJsonStructure([
                'success', 'data'
            ])
            ->assertStatus(200);

    }

    /** @test */

    public function it_a_delete_a_product()
    {
        $ProductCreated = factory(Product::class)->create(['name' => 'memoria ram']);

        $this->actingAs($this->createUser())
            ->deleteJson(route('products.destroy', $ProductCreated->id))
            ->assertStatus(200);

        $this->assertDatabaseMissing('products', [
            'id' => $ProductCreated->id,
            'name' => 'memoria ram'
        ]);

    }
    public function createUser()
    {
        return factory(User::class)->create();
    }

}
