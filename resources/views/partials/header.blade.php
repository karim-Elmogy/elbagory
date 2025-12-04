<header class="main-header">
    <!-- Main header (logo + search + actions) -->
    <div class="header-content">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <a href="{{ route('home') }}" class="logo d-flex align-items-center gap-2">
                        @php
                            $storeLogo = \App\Models\Setting::get('store_logo', 'logo.png');
                            $storeName = \App\Models\Setting::get('store_name', 'متجر إلكتروني');
                            $logoPath = file_exists(public_path($storeLogo)) ? $storeLogo : (file_exists(public_path('logo.png')) ? 'logo.png' : null);
                        @endphp
                        @if($logoPath)
                            <img src="{{ asset($logoPath) }}" alt="{{ $storeName }}" style="height: 50px; width: auto; max-width: 150px; object-fit: contain;">
                        @else
                            <i class="fas fa-store"></i>
                        @endif
                        <span>{{ $storeName }}</span>
                    </a>
                </div>
                <div class="col-md-6 d-none d-md-block">
                    <form action="{{ route('products.index') }}" method="GET" class="search-box">
                        <input type="text" name="search" class="form-control" placeholder="إيه اللي بتدور عليه؟" value="{{ request('search') }}">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="col-md-3 text-end">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <div class="d-none d-lg-flex flex-column align-items-end me-3">
                            <span class="small text-muted">التوصيل إلى</span>
                            <span class="fw-semibold">
                                <i class="fas fa-location-dot"></i>
                                {{ \App\Models\Setting::get('default_city', 'القاهرة') }}
                            </span>
                        </div>
                        @auth
                            <a href="{{ route('notifications.index') }}" class="cart-icon text-decoration-none text-center position-relative" title="الإشعارات">
                                <i class="fas fa-bell d-block"></i>
                                @php
                                    $unreadNotifications = \App\Models\Notification::getUnreadCount(auth()->id());
                                @endphp
                                @if($unreadNotifications > 0)
                                    <span class="cart-badge">{{ $unreadNotifications }}</span>
                                @endif
                                <small class="d-block mt-1" style="font-size: 11px;">الإشعارات</small>
                            </a>
                            <a href="{{ route('wishlist.index') }}" class="cart-icon text-decoration-none text-center" title="المفضلة">
                                <i class="fas fa-heart d-block"></i>
                                @php
                                    $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
                                @endphp
                                @if($wishlistCount > 0)
                                    <span class="cart-badge">{{ $wishlistCount }}</span>
                                @endif
                                <small class="d-block mt-1" style="font-size: 11px;">المفضلة</small>
                            </a>
                        @endauth
                        <a href="{{ route('cart.index') }}" class="cart-icon text-decoration-none text-center" title="السلة">
                            <i class="fas fa-shopping-cart d-block"></i>
                            @php
                                $cart = session('cart', []);
                                $cartCount = count($cart);
                            @endphp
                            @if($cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                            <small class="d-block mt-1" style="font-size: 11px;">سلة التسوق</small>
                        </a>
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
                                <i class="fas fa-user"></i>
                                <span>سجل / دخول</span>
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu Button (Fixed Top Left) -->
    <button class="mobile-nav-toggle d-md-none" id="mobileNavToggle" aria-label="القائمة">
        <i class="fas fa-bars"></i>
    </button>
    <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
    
    <!-- Category navigation -->
    <nav class="main-nav">
        <div class="container">
            <ul class="nav" id="mainNav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}"><i class="fas fa-home"></i> الرئيسية</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('products.index') }}"><i class="fas fa-box"></i> المنتجات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('categories.index') }}"><i class="fas fa-th-large"></i> كل الأقسام</a>
                </li>
                <!-- @php
                    $headerCategories = \App\Models\Category::orderBy('name')->take(7)->get();
                @endphp
                @foreach($headerCategories as $cat)
                    <li class="nav-item d-none d-md-block">
                        <a class="nav-link" href="{{ route('products.category', $cat->slug) }}">{{ $cat->name }}</a>
                    </li>
                @endforeach -->
                @auth
                    @php
                        $customer = auth()->user()->customers()->first();
                        $isWholesale = $customer && $customer->isWholesale();
                    @endphp
                    @if($isWholesale)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.wholesale') }}"><i class="fas fa-warehouse"></i> طلبات الجمله</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('wishlist.index') }}"><i class="fas fa-heart"></i> المفضلة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.index') }}"><i class="fas fa-shopping-bag"></i> طلباتي</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('customer.account') }}"><i class="fas fa-user"></i> حسابي</a>
                    </li>
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-cog"></i> لوحة التحكم</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                            @csrf
                            <button type="submit" class="nav-link border-0 bg-transparent text-white" style="padding: 15px 20px; font-weight: 500; cursor: pointer;">
                                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>
</header>
