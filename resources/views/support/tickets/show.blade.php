@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Ticket #{{ $ticket->id }}: {{ $ticket->subject }}</h5>
                    <a href="{{ route('support.tickets.index') }}" class="btn btn-secondary">Back to Tickets</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Ticket Information</h6>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $ticket->status === 'open' ? 'success' : ($ticket->status === 'closed' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </p>
                            <p><strong>Priority:</strong> 
                                <span class="badge bg-{{ $ticket->priority === 'high' ? 'danger' : ($ticket->priority === 'medium' ? 'warning' : 'info') }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </p>
                            <p><strong>Created:</strong> {{ $ticket->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>User Information</h6>
                            <p><strong>Name:</strong> {{ $ticket->user->name }}</p>
                            <p><strong>Email:</strong> {{ $ticket->user->email }}</p>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Message</h6>
                        </div>
                        <div class="card-body">
                            {{ $ticket->message }}
                        </div>
                    </div>

                    <div class="replies mb-4">
                        <h6>Replies</h6>
                        @forelse($ticket->replies as $reply)
                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $reply->user->name }}</strong>
                                        <small class="text-muted ms-2">{{ $reply->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                </div>
                                <div class="card-body">
                                    {{ $reply->message }}
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No replies yet.</p>
                        @endforelse
                    </div>

                    @if($ticket->status !== 'closed')
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Add Reply</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('support.tickets.respond', $ticket) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea name="message" id="message" rows="4" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Send Reply</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 