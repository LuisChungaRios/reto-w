<?php

namespace Tests\Feature;

use App\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryModule extends TestCase
{
    /** @test */

    use RefreshDatabase;

    public function it_a_create_new_category()
    {
        $this->withoutExceptionHandling();
        $this->postJson(route('categories.store'),[
            'name' => 'Monitores'
        ])->assertStatus(200);

        $this->assertDatabaseHas('categories', [
            'name' => 'Monitores'
        ]);
    }

    /** @test */

    public function it_a_create_new_category_required_name()
    {
        $this->postJson(route('categories.store'),[])
            ->assertStatus(422)
            ->assertJsonStructure([
                'message','errors' => ['name']
            ]);
    }

    /** @test */

    public function it_update_a_category()
    {
        $this->withoutExceptionHandling();

        $categoryCreated = factory(Category::class)->create(['name' => 'monitores']);

        $this->putJson(route('categories.update', $categoryCreated->id), [
            'name' => 'Monitor'
        ])->assertStatus(200);

        $this->assertDatabaseHas('categories', [
            'name' => 'Monitor',
            'id' => $categoryCreated->id
        ]);

    }


    /** @test */

    public function it_update_a_category_required_a_exists_category()
    {

        $this->putJson(route('categories.update', rand(1,10)), [
            'name' => 'Monitor'
        ])->assertStatus(404);

    }

    /** @test */

    public function it_show_a_category()
    {
        $categoryCreated = factory(Category::class)->create(['name' => 'monitores']);

        $this->getJson(route('categories.show', $categoryCreated->id))
            ->assertJsonStructure([
                'success', 'data'
            ])
            ->assertStatus(200);

    }

    /** @test */

    public function it_a_delete_a_category()
    {
        $categoryCreated = factory(Category::class)->create(['name' => 'monitores']);

        $this->deleteJson(route('categories.destroy', $categoryCreated->id))
            ->assertStatus(200);

        $this->assertDatabaseMissing('categories', [
            'id' => $categoryCreated->id,
            'name' => 'monitores'
        ]);

    }
}
