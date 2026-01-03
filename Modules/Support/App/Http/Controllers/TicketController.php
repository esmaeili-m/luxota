<?php

namespace Modules\Support\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Support\App\Http\Requests\CreateTicketRequest;
use Modules\Support\App\resources\TicketResource;
use Modules\Support\Services\TicketService;

class TicketController extends Controller
{
    public TicketService $service;

    public function __construct(TicketService $service)
    {
        $this->service=$service;
    }
    public function get_user_tickets()
    {
        $tickets=$this->service->get_user_tickets();
        return  TicketResource::collection($tickets);
    }
    /**
     * Display a listing of the resource.
     */
    public function tickets(CreateTicketRequest $request)
    {
        $ticket=$this->service->create_ticket($request->validated());
        return new TicketResource($ticket);
    }

    public function get_ticket($id)
    {
        $ticket=$this->service->get_ticket_by_id($id);
        return new TicketResource($ticket);
    }
    public function index()
    {
        return view('support::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('support::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('support::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('support::edit');
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
