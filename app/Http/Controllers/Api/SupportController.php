<?php

namespace App\Http\Controllers\Api;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class SupportController extends BaseController
{
    /**
     * Display a listing of the resource (user's support tickets).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Ensure the user is authenticated and is a customer
        if (!$user || !$user->isCustomer()) {
             return $this->sendError('Unauthorized', ['error' => 'Only customers can view support tickets.'], 403);
        }

        $tickets = $user->supportTickets()->with('responses.user')->get();

        return $this->sendResponse($tickets, 'User support tickets retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Ensure the user is authenticated and is a customer
        if (!$user || !$user->isCustomer()) {
             return $this->sendError('Unauthorized', ['error' => 'Only customers can create support tickets.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $ticket = $user->supportTickets()->create([
                'subject' => $request->subject,
                'status' => 'open', // Set initial status
            ]);

            // Create the initial response (the user's message)
            $ticket->responses()->create([
                'user_id' => $user->id,
                'message' => $request->message,
            ]);

            $ticket->load('responses.user'); // Load relationships for response

            return $this->sendResponse($ticket, 'Support ticket created successfully.', 201);

        } catch (\Exception $e) {
            \Log::error('Support ticket creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return $this->sendError('Failed to create support ticket. Please try again later.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupportTicket  $ticket
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(\App\Models\SupportTicket $ticket)
    {
        $user = Auth::user();

        // Ensure the ticket belongs to the authenticated user
        if ($ticket->user_id !== $user->id) {
            return $this->sendError('Unauthorized.', ['error' => 'You do not have access to this support ticket.'], 403);
        }

        $ticket->load('responses.user');

        return $this->sendResponse($ticket, 'Support ticket retrieved successfully.');
    }

    /**
     * Add a response to the specified support ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupportTicket  $ticket
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond(Request $request, \App\Models\SupportTicket $ticket)
    {
        $user = $request->user();

        // Ensure the ticket belongs to the authenticated user
        if ($ticket->user_id !== $user->id) {
            return $this->sendError('Unauthorized.', ['error' => 'You do not have access to this support ticket.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $response = $ticket->responses()->create([
                'user_id' => $user->id,
                'message' => $request->message,
            ]);

            // Optionally update ticket status if it was closed/resolved and customer responds
            if (in_array($ticket->status, ['resolved', 'closed'])) {
                $ticket->status = 'in progress'; // Or 'open', depending on desired workflow
                $ticket->save();
            }

            $response->load('user'); // Load user relationship for response

            return $this->sendResponse($response, 'Response added successfully.', 201);

        } catch (\Exception $e) {
            \Log::error('Adding response to support ticket failed:', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return $this->sendError('Failed to add response. Please try again later.');
        }
    }
} 