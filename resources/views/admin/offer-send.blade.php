@extends('admin.layouts.app')

@section('content')
    <div class="container p-3" style="padding: 20px!important;">
        <h2 class="mb-4">🎉 অফার ইমেইল পাঠান</h2>

        {{-- Success / Error Message --}}
        @if(session('status'))
            <div class="alert alert-info">
                {{ session('status') }}
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.offer.sends') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Discount (%)</label>
                <input type="number" class="form-control" name="discount" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Expiry Date</label>
                <input type="date" value="{{now()}}" class="form-control" name="expiryDate" required>
            </div>
            <button type="submit" class="btn btn-primary">Send Offer Mail</button>
        </form>
    </div>
@endsection
