<?php

// tests/Feature/Posts/PostApiTest.php

namespace Tests\Feature\Posts;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;  
    // ==================
    // INDEX Tests
    // ==================

    /** @test */
    public function it_can_fetch_all_posts()
    {
        // 3 posts banao
        Post::factory()->count(3)->create();

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');  
    }

    /** @test */
    public function it_returns_empty_when_no_posts()
    {
        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }
 
    /** @test */
    public function it_can_fetch_single_post()
    {
        $post = Post::factory()->create(['title' => 'Hello Post']);

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Hello Post']);
    }

    /** @test */
    public function it_returns_404_for_invalid_post()
    {
        $response = $this->getJson('/api/posts/999');

        $response->assertStatus(404);
    }
 

    /** @test */
    public function it_can_create_post()
    {
        $data = [
            'title'  => 'New Post',
            'body'   => 'Post body here',
            'status' => 'published'
        ];

        $response = $this->postJson('/api/posts', $data);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'New Post']);
 
        $this->assertDatabaseHas('posts', ['title' => 'New Post']);
    }

    /** @test */
    public function it_validates_title_is_required()
    {
        $response = $this->postJson('/api/posts', [
            'body' => 'Body without title'
        ]);

        $response->assertStatus(422)  
            ->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function it_validates_body_is_required()
    {
        $response = $this->postJson('/api/posts', [
            'title' => 'Title without body'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['body']);
    }

    /** @test */
    public function it_validates_status_values()
    {
        $response = $this->postJson('/api/posts', [
            'title'  => 'Test',
            'body'   => 'Test',
            'status' => 'invalid_status' // ← Wrong value
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }
 
    /** @test */
    public function it_can_update_post()
    {
        $post = Post::factory()->create(['title' => 'Old Title']);

        $response = $this->putJson("/api/posts/{$post->id}", [
            'title' => 'New Title'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'New Title']);

        $this->assertDatabaseHas('posts', ['title' => 'New Title']);
    }

    /** @test */
    public function it_returns_404_when_updating_invalid_post()
    {
        $response = $this->putJson('/api/posts/999', [
            'title' => 'Test'
        ]);

        $response->assertStatus(404);
    }
 
    /** @test */
    public function it_can_delete_post()
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['message' => 'Post deleted successfully!']);

        // Database se bhi gaya?
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    /** @test */
    public function it_returns_404_when_deleting_invalid_post()
    {
        $response = $this->deleteJson('/api/posts/999');

        $response->assertStatus(404);
    }
}
