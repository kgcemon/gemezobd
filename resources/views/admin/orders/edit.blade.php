@extends('admin.layouts.app')

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">Edit Order #{{ $order->id }}</h4>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary">← Back</a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.orders.edits', $order->id) }}" method="POST">
                @csrf
                @method('POST')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">User ID</label>
                        <input type="number" class="form-control" name="user_id" value="{{ old('user_id', $order->user_id) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $order->name) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $order->phone) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $order->email) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product ID</label>
                        <input type="number" class="form-control" name="product_id" value="{{ old('product_id', $order->product_id) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Item ID</label>
                        <input type="number" class="form-control" name="item_id" value="{{ old('item_id', $order->item_id) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" value="{{ old('quantity', $order->quantity) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Total</label>
                        <input type="number" step="0.01" class="form-control" name="total" value="{{ old('total', $order->total) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Customer Data</label>
                        <input type="text" class="form-control" name="customer_data" value="{{ old('customer_data', $order->customer_data) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Others Data</label>
                        <input type="text" class="form-control" name="others_data" value="{{ old('others_data', $order->others_data) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Order Note</label>
                        <input type="text" class="form-control" name="order_note" value="{{ old('order_note', $order->order_note) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Payment Method</label>
                        <input type="number" class="form-control" name="payment_method" value="{{ old('payment_method', $order->payment_method) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Transaction ID</label>
                        <input type="text" class="form-control" name="transaction_id" value="{{ old('transaction_id', $order->transaction_id) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Number</label>
                        <input type="text" class="form-control" name="number" value="{{ old('number', $order->number) }}">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4">💾 Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection
