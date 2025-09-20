@extends('admin.layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h3 class="mb-4">Notice Update</h3>

            <!-- Add / Update Notice Form -->
            <form id="noticeForm">
                @csrf
                <div class="form-group mb-3">
                    <textarea id="noticeInput" name="notice" class="form-control" rows="3" placeholder="Write your notice here...">{{ $notice->notice ?? '' }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Notice</button>
            </form>

            <hr>

            <!-- Show Latest Notice -->
            <div id="noticeSection">
                @if($notice)
                    <div class="alert alert-info d-flex justify-content-between align-items-center mt-3">
                        <span id="noticeText">{{ $notice->notice }}</span>
                        <button class="btn btn-danger btn-sm" onclick="deleteNotice({{ $notice->id }})">Delete</button>
                    </div>
                @else
                    <p class="text-muted">No notice available.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Custom Toast -->
    <div id="toastBox" class="toast-box"></div>

    <style>
        .toast-box {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
        .toast-msg {
            min-width: 220px;
            margin-bottom: 10px;
            padding: 12px 20px;
            border-radius: 6px;
            color: #fff;
            font-weight: 500;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            animation: slideIn 0.4s, fadeOut 0.5s 2.5s forwards;
        }
        .toast-success { background: #28a745; }
        .toast-error { background: #dc3545; }
        .toast-info { background: #17a2b8; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(100%); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeOut {
            to { opacity: 0; transform: translateX(100%); }
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Custom Toast Function
        function showToast(message, type = "info") {
            let toast = document.createElement("div");
            toast.classList.add("toast-msg", "toast-" + type);
            toast.innerText = message;

            document.getElementById("toastBox").appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Save Notice (AJAX)
        $('#noticeForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.notice.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        $('#noticeSection').html(`
                            <div class="alert alert-info d-flex justify-content-between align-items-center mt-3">
                                <span id="noticeText">${res.notice.notice}</span>
                                <button class="btn btn-danger btn-sm" onclick="deleteNotice(${res.notice.id})">Delete</button>
                            </div>
                        `);
                        $('#noticeInput').val('');
                        showToast("Notice saved successfully!", "success");
                    } else {
                        showToast("Failed to save notice!", "error");
                    }
                },
                error: function(err) {
                    showToast("Something went wrong!", "error");
                }
            });
        });

        // Delete Notice (AJAX)
        function deleteNotice(id) {
            if(confirm("Are you sure you want to delete this notice?")) {
                $.ajax({
                    url: "/admin/notice/" + id,
                    type: "DELETE",
                    data: {_token: "{{ csrf_token() }}"},
                    success: function(res) {
                        if (res.success) {
                            $('#noticeSection').html('<p class="text-muted">No notice available.</p>');
                            showToast("Notice deleted successfully!", "success");
                        } else {
                            showToast("Delete failed!", "error");
                        }
                    },
                    error: function() {
                        showToast("Something went wrong!", "error");
                    }
                });
            }
        }
    </script>
@endsection
