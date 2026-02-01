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

    public function ticket_count()
    {
        return $this->service->ticket_count();
    }

    public function get_tickets(Request $request)
    {
        $tickets = $this->service->get_tickets($request->only(['status','content','code','user_id']));
        return TicketResource::collection($tickets);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['subject','status']);

        $ticket = $this->service->update($id, $data);

        return response()->json([
            'message' => 'Status updated successfully',
            'data' => $ticket,
        ]);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $deleted = $this->service->delete($id);
        if (!$deleted) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json(['message' => 'Message deleted successfully']);
    }
}
