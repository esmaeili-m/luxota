<?php

namespace Modules\Planner\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Planner\App\Http\Requests\CreateTagRequest;
use Modules\Planner\App\resources\TagResource;
use Modules\Planner\App\resources\TagTreeResource;
use Modules\Planner\Services\TagService;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $service;

    public function __construct(TagService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $view = $request->query('view', 'tree');

        $tags = $this->service->getTags($view);

        return $view === 'flat'
            ? TagResource::collection($tags)
            : TagTreeResource::collection($tags);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(CreateTagRequest $request)
    {
        $tag =$this->service->create($request->validated());
        return new TagResource($tag);
    }

    public function update(CreateTagRequest $request, $id)
    {
        $tag = $this->service->update($id, $request->validated());
        if (!$tag) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return new TagResource($tag);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
