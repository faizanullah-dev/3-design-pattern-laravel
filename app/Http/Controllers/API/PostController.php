<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Services\PostService;

class PostController extends Controller
{
    public function __construct(
        protected PostService $postService
    ) {}

    public function index()
    {
        $posts = $this->postService->getAllPosts();;
        return PostResource::collection($posts);
    }

    public function store(StorePostRequest $request)
    {
        $post = $this->postService->createPost($request->validated());
        return (new PostResource($post))
            ->response()
            ->setStatusCode(201);
    }

    public function show(int $id)
    {
        $post = $this->postService->getPost($id);
        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, int $id)
    {
        $post = $this->postService->updatePost($id, $request->validated());
        return new PostResource($post);
    }

    public function destroy(int $id)
    {
        $this->postService->deletePost($id);
        return response()->json([
            'message' => 'Post deleted successfully!'
        ], 200);
    }
}
