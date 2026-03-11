<?php

// tests/Unit/Services/PostServiceTest.php

namespace Tests\Unit\Services;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    protected PostService $postService;
    protected $mockRepo;

    protected function setUp(): void
    {
        parent::setUp();
 
        $this->mockRepo = Mockery::mock(PostRepositoryInterface::class);
        $this->postService = new PostService($this->mockRepo);
    }

    /** @test */
    public function it_can_get_all_posts()
    { 
        $fakePosts = collect([
            new Post(['title' => 'Post 1', 'body' => 'Body 1']),
            new Post(['title' => 'Post 2', 'body' => 'Body 2']),
        ]);
 
        $this->mockRepo
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($fakePosts);
 
        $result = $this->postService->getAllPosts();
 
        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_can_create_post_with_default_draft_status()
    {
        $data = ['title' => 'New Post', 'body' => 'Some body'];

        $fakePost = new Post([...$data, 'status' => 'draft']);
 
        $this->mockRepo
            ->shouldReceive('create')
            ->once()
            ->with([...$data, 'status' => 'draft'])
            ->andReturn($fakePost);

        $result = $this->postService->createPost($data);
 
        $this->assertEquals('draft', $result->status);
    }

    /** @test */
    public function it_can_find_post_by_id()
    {
        $fakePost = new Post(['id' => 1, 'title' => 'Test']);
        $fakePost->id = 1;
        $this->mockRepo
            ->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn($fakePost);

        $result = $this->postService->getPost(1);

        $this->assertEquals(1, $result->id);
        $this->assertEquals('Test', $result->title);
    }

    /** @test */
    public function it_can_update_post()
    {
        $fakePost = new Post(['id' => 1, 'title' => 'Updated']);

        $this->mockRepo
            ->shouldReceive('update')
            ->once()
            ->with(1, ['title' => 'Updated'])
            ->andReturn($fakePost);

        $result = $this->postService->updatePost(1, ['title' => 'Updated']);

        $this->assertEquals('Updated', $result->title);
    }

    /** @test */
    public function it_can_delete_post()
    {
        $this->mockRepo
            ->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $result = $this->postService->deletePost(1);

        $this->assertTrue($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();  
        parent::tearDown();
    }
}
