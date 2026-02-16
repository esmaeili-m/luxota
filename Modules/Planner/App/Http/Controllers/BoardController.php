<?php

namespace Modules\Planner\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Planner\App\Http\Requests\CreateBoardRequest;
use Modules\Planner\App\resources\BoardResource;
use Modules\Planner\Services\BoardService;

class BoardController extends Controller
{
    protected BoardService $service;

    public function __construct(BoardService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $input = $request->only([
            'status',
            'content',
            'with',
            'parent_id',
        ]);
        $input['paginate'] = $request->boolean('paginate', true);
        $input['page'] = $request->input('page', 1);
        $input['per_page'] = $request->input('per_page', 10);

        $boards = $this->service->getBoards($input);
        return BoardResource::collection($boards);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(CreateBoardRequest $request)
    {
        $board= $this->service->create($request->validated());
        return new BoardResource($board);
    }

    public function show(Request $request,$id)
    {
        $with = $request->query('with');
        $with= $with ? explode(',', $with) : [];
        $board = $this->service->getById($id,$with);

        if (!$board) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return new BoardResource($board);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('planner::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
