@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2" style="padding: 10px!important; align-content: center!important;">
            <h4>Payment SMS</h4>

            <div class="d-flex gap-2 flex-wrap">
                <!-- Status Filter & Search -->
                <div class="d-flex gap-2 flex-wrap">
                    <select id="statusFilter" class="form-select">
                        <option value="">All Status</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Completed</option>
                    </select>

                    <input type="text" id="searchInput" class="form-control" placeholder="Search by sender, number, trxID, or amount" value="{{ request('search') }}">
                </div>

            </div>
        </div>

        <!-- Table Container -->
        <div id="smsTableContainer">
            @include('admin.paymentSms.sms_table', ['data' => $data])
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const statusFilter = document.getElementById('statusFilter');
            const searchInput = document.getElementById('searchInput');
            const smsTableContainer = document.getElementById('smsTableContainer');

            let debounceTimer;

            function fetchData() {
                const status = statusFilter.value;
                const search = searchInput.value;

                fetch(`{{ route('admin.sms') }}?status=${status}&search=${search}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(response => response.text())
                    .then(html => {
                        smsTableContainer.innerHTML = html;
                    });
            }

            statusFilter.addEventListener('change', fetchData);
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(fetchData, 500); // 0.5s debounce
            });
        });
    </script>
@endpush
