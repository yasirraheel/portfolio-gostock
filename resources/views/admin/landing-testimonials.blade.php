@extends('layouts.admin')

@section('title', 'Landing Testimonials')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-chat-quote me-2"></i>Landing Testimonials
                        </h4>
                        <a href="{{ url('panel/admin/landing-testimonials/create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Add New Testimonial
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($testimonials->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Position</th>
                                        <th>Testimonial</th>
                                        <th>Rating</th>
                                        <th>Status</th>
                                        <th>Featured</th>
                                        <th>Sort Order</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($testimonials as $testimonial)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                                                        <span class="fw-bold small">{{ $testimonial->initials }}</span>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $testimonial->client_name }}</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $testimonial->client_position }}</td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="{{ $testimonial->testimonial_text }}">
                                                    {{ Str::limit($testimonial->testimonial_text, 80) }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star{{ $i <= $testimonial->rating ? '-fill' : '' }}"></i>
                                                    @endfor
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $testimonial->status == 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($testimonial->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($testimonial->is_featured)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-muted"></i>
                                                @endif
                                            </td>
                                            <td>{{ $testimonial->sort_order }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ url('panel/admin/landing-testimonials/edit', $testimonial->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form method="POST" action="{{ url('panel/admin/landing-testimonials/delete', $testimonial->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this testimonial?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $testimonials->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-chat-quote display-1 text-muted"></i>
                            <h5 class="mt-3">No Testimonials Found</h5>
                            <p class="text-muted">Start by adding your first landing page testimonial.</p>
                            <a href="{{ url('panel/admin/landing-testimonials/create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i>Add First Testimonial
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
