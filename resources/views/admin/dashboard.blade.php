@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">

        <!-- Dashboard Overview -->
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-4">Dashboard Overview</h5>
                <div class="row g-4">
                    @php
                        $dashboardItems = [
                            ['label' => 'Today Orders', 'value' => $dashboardData['todayOrder'], 'icon' => 'fas fa-shopping-cart', 'bg' => 'bg-success', 'link' => '/orders/today'],
                            ['label' => 'Total Users', 'value' => $dashboardData['total_users'], 'icon' => 'fas fa-users', 'bg' => 'bg-warning', 'link' => '/users'],
                            ['label' => 'Total Products', 'value' => $dashboardData['total_products'], 'icon' => 'fas fa-boxes', 'bg' => 'bg-danger', 'link' => '/products'],
                            ['label' => 'Total Categories', 'value' => $dashboardData['total_cat'], 'icon' => 'fas fa-layer-group', 'bg' => 'bg-primary', 'link' => '/categories'],
                        ];
                    @endphp

                    @foreach ($dashboardItems as $item)
                        <div class="col-md-3 col-sm-6">
                            <div class="d-flex justify-content-between align-items-center border rounded p-3 h-100 bg-light shadow-sm hover-shadow">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box {{ $item['bg'] }} text-white rounded d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px;">
                                        <i class="{{ $item['icon'] }} fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold fs-5">{{ $item['value'] }}</div>
                                        <small class="text-muted">{{ $item['label'] }}</small>
                                    </div>
                                </div>
                                <a href="{{ $item['link'] }}">
                                    <i class="fas fa-arrow-right text-muted"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Orders Overview -->
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-4">Orders Overview</h5>
                <div class="row g-4">
                    @php
                        $orderItems = [
                            ['label' => 'Total Orders', 'value' => $dashboardData['total_orders'], 'icon' => 'fas fa-shopping-cart', 'bg' => 'bg-success', 'link' => '/orders'],
                            ['label' => 'Completed Orders', 'value' => $dashboardData['total_complete_order'], 'icon' => 'fas fa-check-circle', 'bg' => 'bg-warning', 'link' => '/orders?status=completed'],
                            ['label' => 'Cancelled Orders', 'value' => $dashboardData['total_cancel_order'], 'icon' => 'fas fa-times-circle', 'bg' => 'bg-danger', 'link' => '/orders?status=cancelled'],
                            ['label' => 'Total Sales', 'value' => $dashboardData['total_sell'], 'icon' => 'fas fa-dollar-sign', 'bg' => 'bg-primary', 'link' => '/sales'],
                        ];
                    @endphp

                    @foreach ($orderItems as $item)
                        <div class="col-md-3 col-sm-6">
                            <div class="d-flex justify-content-between align-items-center border rounded p-3 h-100 bg-light shadow-sm hover-shadow">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box {{ $item['bg'] }} text-white rounded d-flex justify-content-center align-items-center me-3" style="width: 50px; height: 50px;">
                                        <i class="{{ $item['icon'] }} fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold fs-5">{{ $item['value'] }}</div>
                                        <small class="text-muted">{{ $item['label'] }}</small>
                                    </div>
                                </div>
                                <a href="{{ $item['link'] }}">
                                    <i class="fas fa-arrow-right text-muted"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>


        <!-- recent Order -->
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-4">Recent Orders</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-dark">
                <tr>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Total (৳)</th>
                    <th>Status</th>
                    <th>TrxID</th>
                    <th>Placed</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->product->name }}</td>
                        <td>{{ $order->item->name ?? "" }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td class="text-bg-info">{{ number_format($order->total, 2) }}৳</td>
                        <td>
                            @php
                                $statusClass = match($order->status) {
                                    'hold' => 'badge bg-warning',
                                    'processing' => 'badge bg-success',
                                    'cancelled' => 'badge bg-danger',
                                    default => 'badge bg-secondary',
                                };
                            @endphp
                            <span class="{{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td>{{ $order->transaction_id ?? '-' }}</td>
                        <td>{{ $order->created_at ? $order->created_at->diffForHumans() : 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center py-4">No orders found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        </div>
        </div>


    </div>
@endsection
