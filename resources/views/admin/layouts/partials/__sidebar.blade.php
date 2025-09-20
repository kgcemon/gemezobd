<!-- Sidebar -->

<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="/admin/dashboard" class="logo">
                <img src="{{asset('logo.png')}}" alt="App Name" class="navbar-brand" height="20">
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>

    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">

                <!-- Dashboard -->
                <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a href="/admin/dashboard">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>


                @php
                    use App\Models\Order;
                       $ordersActive = request()->is('admin/orders*');
                       $processingCount = Order::where('status', 'processing')->count() ?? 0;
                       $holdCount = Order::where('status', 'hold')->count() ?? 0;
                @endphp

                <li class="nav-item {{ $ordersActive ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#ordersMenu" aria-expanded="{{ $ordersActive ? 'true' : 'false' }}">
                        <i class="fas fa-list"></i>
                        <p>Orders</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $ordersActive ? 'show' : '' }}" id="ordersMenu">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="/admin/orders">
                    <span class="sub-item {{ request('filter') == 'Processing' ? 'active' : '' }}">
                        All Orders
                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/orders?filter=Processing">
                    <span class="sub-item {{ request('filter') == 'Processing' ? 'active' : '' }}">
                        Processing
                        @if($processingCount >0)
                            <span class="badge bg-success-gradient ms-2">{{ $processingCount }}</span>
                        @endif
                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/orders?filter=Hold">
                    <span class="sub-item {{ request('filter') == 'Hold' ? 'active' : '' }}">
                        Hold
                        @if($holdCount > 0)
                            <span class="badge bg-warning ms-2">{{ $holdCount }}</span>
                        @endif
                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/orders?filter=Delivery+Running">
                    <span class="sub-item {{ request('filter') == 'Delivery Running' ? 'active' : '' }}">
                        Delivery Running
                        @if($dashboardData['deliveryRunningCount'] ?? false)
                            <span class="badge bg-primary ms-2">{{ $dashboardData['deliveryRunningCount'] }}</span>
                        @endif
                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/orders?filter=Delivered">
                    <span class="sub-item {{ request('filter') == 'Delivered' ? 'active' : '' }}">
                        Delivered
                        @if($dashboardData['deliveredCount'] ?? false)
                            <span class="badge bg-success ms-2">{{ $dashboardData['deliveredCount'] }}</span>
                        @endif
                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/orders?filter=Cancelled">
                    <span class="sub-item {{ request('filter') == 'Cancelled' ? 'active' : '' }}">
                        Cancelled
                        @if($dashboardData['cancelledCount'] ?? false)
                            <span class="badge bg-secondary ms-2">{{ $dashboardData['cancelledCount'] }}</span>
                        @endif
                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>



                <!-- Users -->
                <li class="nav-item {{ Str::contains(request()->path(), 'products') ? 'active' : '' }}">
                    <a href="/admin/products">
                        <i class="fas fa-shopping-bag"></i>
                        <p>Product</p>
                    </a>
                </li>




                <!-- Plans -->
                <li class="nav-item {{ Str::contains(request()->path(), 'variant') ? 'active' : '' }}">
                    <a href="/admin/variant">
                        <i class="fas fa-database"></i>
                        <p>Variant</p>
                    </a>
                </li>


                <li class="nav-item {{ Str::contains(request()->path(), 'codes') ? 'active' : '' }}">
                    <a href="/admin/codes">
                        <i class="fas fa-calculator"></i>
                        <p>Codes</p>
                    </a>
                </li>

                <!-- Withdraw -->
                <li class="nav-item {{ Str::contains(request()->path(), 'categories') ? 'active' : '' }}">
                    <a href="/admin/categories" class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-cart-arrow-down"></i>
                            <p class="m-0">Categories</p>
                        </div>
                    </a>
                </li>


                <!-- Transactions -->
                <li class="nav-item {{ request()->is('admin/sliders') ? 'active' : '' }}">
                    <a href="/admin/sliders">
                        <i class="fas fa-money-check"></i>
                        <p>Slider Image</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('admin/payment-sms') ? 'active' : '' }}">
                    <a href="/admin/payment-sms">
                        <i class="fas fa-money-check"></i>
                        <p>Store SMS</p>
                    </a>
                </li>
                <li class="nav-item {{ request()->is('admin/users') ? 'active' : '' }}">
                    <a href="/admin/users">
                        <i class="fas fa-user"></i>
                        <p>All Users</p>
                    </a>
                </li>

                <!-- Settings -->
                <li class="nav-item nav-item {{ Str::contains(request()->path(), 'holidays') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#settings">
                        <i class="fas fa-cog"></i>
                        <p>Settings</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="settings">
                        <ul class="nav nav-collapse">
                            <li><a href="/admin/payment-methods"><span class="sub-item {{ Str::contains(request()->path(), 'payment-methods') ? 'active' : '' }}">Payment Setting</span></a></li>
                            <li><a href="/withdraws/settings"><span class="sub-item">Site Settings</span></a></li>
                            <li><a href="/admin/settings/referral"><span class="sub-item">SEO Settings</span></a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item {{ request()->is('admin/send-offer') ? 'active' : '' }}">
                    <a href="{{route('admin.offer.index')}}">
                        <i class="fas fa-broadcast-tower"></i>
                        <p>Send Offer Email</p>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
