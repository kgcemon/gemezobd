@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order #{{ $order->id }} Details</h5>

                <div class="d-flex gap-2">
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#receiptModal">
                        <i class="bi bi-receipt"></i> Receipt
                    </button>
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Print Invoice
                    </button>
                </div>
            </div>

            <div class="card-body">
                {{-- Customer Information --}}
                <h6 class="mb-3">Customer Information</h6>
                <table class="table table-borderless table-sm w-50">
                    <tr>
                        <th>Name:</th>
                        <td>{{ $order->name }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $order->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td>{{ $order->phone }}</td>
                    </tr>
                    <tr>
                        <th>Placed:</th>
                        <td>{{ $order->created_at ? $order->created_at->format('d M, Y H:i') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        @php
                            $statusClass = match($order->status) {
                                'hold' => 'badge bg-warning',
                                'completed' => 'badge bg-success',
                                'cancelled' => 'badge bg-danger',
                                default => 'badge bg-secondary',
                            };
                        @endphp
                        <td><span class="{{ $statusClass }}">{{ ucfirst($order->status) }}</span></td>
                    </tr>
                </table>

                <hr>

                {{-- Order Items --}}
                <h6 class="mb-3">Order Details</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Item</th>
                            <th>{{$order->product->input_name}}</th>
                            <th>Quantity</th>
                            <th>Total (৳)</th>
                        </tr>
                        </thead>
                        <tbody class="text-center">
                        <tr>
                            <td>1</td>
                            <td>{{ $order->product->name }}</td>
                            <td>{{ $order->item->name ??  $order->product->name }}</td>
                            <td>{{ $order->customer_data }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ number_format($order->total, 2) }}</td>
                        </tr>
                        </tbody>
                        <tfoot class="text-end fw-bold">
                        <tr>
                            <td colspan="4" class="text-end">Grand Total:</td>
                            <td>{{ number_format($order->total, 2) }} ৳</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Payment & Transaction Info --}}
                <h6 class="mt-4 mb-3">Payment Information</h6>
                <table class="table table-borderless table-sm w-50">
                    <tr>
                        <th>Payment Method:</th>
                        <td>{{ $order->paymentMethod->method ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Payment Number:</th>
                        <td>{{ $order->number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Transaction ID:</th>
                        <td>{{ $order->transaction_id ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Order Note:</th>
                        <td>{{ $order->order_note ?? '-' }}</td>
                    </tr>
                </table>
            </div>

        </div>
        <div class="card-body">
            @if(count($order->usedCodes) > 0)
            <h6 class="mb-3">   Codes Details</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Note</th>
                        <th>Status</th>
                        <th>active</th>

                    </tr>
                    </thead>
                    <tbody class="text-center">
                    @foreach($order->usedCodes as $code)
                        @php
                            $fullCode = $code->code;
                            $firstPart = substr($fullCode, 0, 4);
                            $lastPart = substr($fullCode, -4);
                            $shortCode = $firstPart . '...' . $lastPart;
                        @endphp
                        <tr>
                            <td>1</td>
                            <td>
                                <span id="code-{{ $code->id }}">{{ $shortCode }}</span>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    onclick="copyCode('{{ $fullCode }}')">
                                    Copy
                                </button>
                            </td>
                            <td>{{ $code->note ?? '' }}</td>
                            <td>{{ $code->status }}</td>
                            <td>{{ $code->active == 1 ? 'Complete' : 'Problem Found' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        </div>
    </div>

    <!-- Receipt Modal -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-3 shadow">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="receiptModalLabel">
                        <i class="bi bi-receipt"></i> Receipt - Order #{{ $order->id }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <h4 class="fw-bold">GamezoBD</h4>
                        <small class="text-muted">Customer Receipt</small>
                        <hr>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <p class="mb-1"><strong>Name:</strong> {{ $order->name }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $order->phone }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $order->email ?? '-' }}</p>
                        </div>
                        <div class="text-end">
                            <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('d M, Y H:i') }}</p>
                            <p class="mb-1"><strong>Status:</strong>
                                <span class="{{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                            </p>
                        </div>
                    </div>

                    <table class="table table-sm table-bordered text-center">
                        <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Total (৳)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $order->product->name }}</td>
                            <td>{{ $order->item->name  ?? $order->product->name }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>{{ number_format($order->total, 2) }}</td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Grand Total:</td>
                            <td class="fw-bold">{{ number_format($order->total, 2) }} ৳</td>
                        </tr>
                        </tfoot>
                    </table>

                    <div class="mt-3">
                        <p class="mb-1"><strong>Payment Method:</strong> {{ $order->paymentMethod->method ?? '-' }}</p>
                        <p class="mb-1"><strong>Transaction ID:</strong> {{ $order->transaction_id ?? '-' }}</p>
                        <p class="mb-1"><strong>Note:</strong> {{ $order->order_note ?? '-' }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="printReceipt()" class="btn btn-primary">
                        <i class="bi bi-printer"></i> Print Receipt
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- JS for Printing Receipt --}}
    <script>
        function printReceipt() {
            var content = document.querySelector('#receiptModal .modal-body').innerHTML;
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Receipt</title>');
            printWindow.document.write('<style>body{font-family:sans-serif;padding:20px;} table{width:100%;border-collapse:collapse;} th,td{padding:8px;border:1px solid #ccc;} .text-center{text-align:center;} .fw-bold{font-weight:bold;} </style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        function copyCode(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert("Copied: " + text);
            }, function(err) {
                alert("Failed to copy: ", err);
            });
        }
    </script>
@endsection
