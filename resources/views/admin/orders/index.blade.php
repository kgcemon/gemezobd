@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Orders</h5>
                {{-- Search Form --}}
                <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex" style="max-width: 100%">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search orders..." value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                {{-- ✅ Bulk Action Form --}}
                <form action="{{ route('admin.orders.bulkAction') }}" method="POST" id="bulkActionForm">
                    @csrf
                    <div class="d-flex mb-3 align-content-center">
                        <select name="action" class="form-select me-2" style="max-width:100%;" required>
                            <option value="">Bulk Actions</option>
                            <option value="delivered">Mark as Completed</option>
                            <option value="processing">Mark as Processing</option>
                            <option value="cancelled">Mark as Cancelled</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button type="submit" class="btn btn-danger">Apply</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle text-center">
                            <thead class="table-dark">
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>#</th>
                                <th>Product</th>
                                <th>User Data</th>
                                <th>Total (৳)</th>
                                <th>Status</th>
                                <th>TrxID</th>
                                <th>Placed</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="orderCheckbox">
                                    </td>
                                    <td>{{$order->id}}</td>
                                    <td>{{ $order->item->name ?? $order->product->input_name }} </td>
                                    <td>{{ $order->customer_data ?? '' }} </td>
                                    <td>{{ number_format($order->total, 2) }}৳</td>
                                    <td>
                                        @php
                                            $statusClass = match($order->status) {
                                                'hold' => 'badge bg-warning',
                                                'completed' => 'badge bg-success',
                                                'cancelled' => 'badge bg-danger',
                                                default => 'badge bg-secondary',
                                            };
                                        @endphp
                                        <span class="{{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td>{{ $order->transaction_id ?? '-' }}</td>
                                    <td style="font-size: 10px">{{ $order->created_at ? $order->created_at->diffForHumans() : 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                          @if($order->status != 'delivered' || $order->status != 'refunded')
                                                <button class="btn btn-sm btn-warning p-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#updateStatusModal"
                                                        data-id="{{ $order->id }}"
                                                        data-status="{{ $order->status }}"
                                                        data-note="{{ $order->order_note }}">
                                                    <i class="bi bi-pencil-square"></i> Update
                                                </button>

                                          @endif

                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info p-2">
                                                <i class="bi bi-eye"></i> View
                                            </a>

                                            <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-eye"></i> Edit
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-4">No orders found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>

                    <div class="mt-3">
                        {{ $orders->links('admin.layouts.partials.__pagination') }}
                    </div>
            </div>
        </div>
    </div>

    {{-- Update Modal --}}
    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="updateStatusForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="updateOrderId">

                        <div class="mb-3">
                            <label for="orderStatus" class="form-label">Status</label>
                            <select name="status" id="orderStatus" class="form-select">
                                @php
                                    $statuses = [
                                        'hold' => 'Hold',
                                        'processing' => 'Processing',
                                        'delivered' => 'Completed',
                                        'Delivery Running' => 'Delivery Running',
                                        'cancelled' => 'Cancelled',
                                        'refunded' => 'Refunded',
                                    ];
                                    $currentStatus = $order->status ?? null;
                                @endphp

                                @foreach($statuses as $value => $label)
                                    @if($currentStatus !== $value)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="orderNote" class="form-label">Order Note</label>
                            <input type="text" name="order_note" id="orderNote" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Order</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script>
        // ✅ Select All Checkbox
        document.getElementById('selectAll').addEventListener('change', function () {
            let checkboxes = document.querySelectorAll('.orderCheckbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // ✅ Auto Fill Modal
        document.addEventListener('DOMContentLoaded', function () {
            var modal = document.getElementById('updateStatusModal');
            modal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var status = button.getAttribute('data-status');
                var note = button.getAttribute('data-note');

                var form = document.getElementById('updateStatusForm');
                form.action = `/admin/orders/${id}`;

                document.getElementById('updateOrderId').value = id;
                document.getElementById('orderStatus').value = status;
                document.getElementById('orderNote').value = note ?? '';
            });
        });
    </script>

@endsection

