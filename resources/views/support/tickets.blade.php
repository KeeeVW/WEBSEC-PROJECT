@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h2>Support Tickets</h2>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Customer</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket->user->name }}</td>
                            <td>{{ $ticket->subject }}</td>
                            <td>
                                <span class="badge bg-{{ $ticket->status === 'open' ? 'danger' : 'success' }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td>{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#ticketModal{{ $ticket->id }}">
                                    View & Respond
                                </button>
                            </td>
                        </tr>

                        <!-- Ticket Modal -->
                        <div class="modal fade" id="ticketModal{{ $ticket->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ticket #{{ $ticket->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>From:</strong> {{ $ticket->user->name }}</p>
                                        <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
                                        <p><strong>Message:</strong></p>
                                        <p>{{ $ticket->message }}</p>

                                        @if($ticket->response)
                                        <hr>
                                        <p><strong>Response:</strong></p>
                                        <p>{{ $ticket->response }}</p>
                                        @else
                                        <form action="{{ route('support.tickets.respond', $ticket) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="response" class="form-label">Your Response</label>
                                                <textarea class="form-control" id="response" name="response" rows="3" required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Send Response</button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection 