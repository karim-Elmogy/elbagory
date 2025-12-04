<footer class="main-footer">
    <div class="container">
        <div class="row g-4">
            <!-- عن المتجر -->
            <div class="col-lg-4 col-md-6 footer-section">
                <div class="footer-logo mb-3">
                    @php
                        $storeName = \App\Models\Setting::get('store_name', 'متجر إلكتروني');
                        $storeLogo = \App\Models\Setting::get('store_logo', 'logo.png');
                        $logoPath = file_exists(public_path($storeLogo)) ? $storeLogo : (file_exists(public_path('logo.png')) ? 'logo.png' : null);
                    @endphp
                    @if($logoPath)
                        <img src="{{ asset($logoPath) }}" alt="{{ $storeName }}" class="footer-logo-img">
                    @endif
                </div>
                <h5 class="footer-title">عن المتجر</h5>
                <p class="footer-description">{{ $storeName }} - متجر إلكتروني متكامل.</p>
                <div class="social-icons">
                    <a href="#" class="social-icon facebook" title="فيسبوك">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-icon twitter" title="تويتر">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-icon instagram" title="إنستجرام">
                        <i class="fab fa-instagram"></i>
                    </a>
                    @php
                        $whatsappNumber = \App\Models\Setting::get('whatsapp_number', '201234567890');
                        $whatsappNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);
                        $whatsappMessage = urlencode('مرحباً، أريد الاستفسار عن المنتجات');
                        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMessage}";
                    @endphp
                    <a href="{{ $whatsappUrl }}" target="_blank" class="social-icon whatsapp" title="واتساب">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
            
            <!-- روابط سريعة -->
            <div class="col-lg-2 col-md-3 col-sm-6 footer-section">
                <h5 class="footer-title">روابط سريعة</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> الرئيسية</a></li>
                    <li><a href="{{ route('products.index') }}"><i class="fas fa-box"></i> جميع المنتجات</a></li>
                    <li><a href="{{ route('products.index', ['featured' => 1]) }}"><i class="fas fa-star"></i> منتجات مميزة</a></li>
                    <li><a href="{{ route('categories.index') }}"><i class="fas fa-th-large"></i> الأقسام</a></li>
                </ul>
            </div>
            
            <!-- معلومات -->
            <div class="col-lg-2 col-md-3 col-sm-6 footer-section">
                <h5 class="footer-title">معلومات</h5>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-info-circle"></i> من نحن</a></li>
                    <li><a href="#"><i class="fas fa-question-circle"></i> الأسئلة الشائعة</a></li>
                    <li><a href="#"><i class="fas fa-file-alt"></i> الشروط والأحكام</a></li>
                    <li><a href="#"><i class="fas fa-shield-alt"></i> سياسة الخصوصية</a></li>
                </ul>
            </div>
            
            <!-- معلومات التواصل -->
            <div class="col-lg-4 col-md-6 footer-section">
                <h5 class="footer-title">معلومات التواصل</h5>
                <ul class="footer-contact">
                    <li>
                        <i class="fas fa-phone"></i>
                        <div>
                            <strong>الهاتف</strong>
                            <span>{{ \App\Models\Setting::get('store_phone', '(+20) 123 456 7890') }}</span>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <div>
                            <strong>البريد الإلكتروني</strong>
                            <span><a href="mailto:{{ \App\Models\Setting::get('store_email', 'info@example.com') }}">{{ \App\Models\Setting::get('store_email', 'info@example.com') }}</a></span>
                        </div>
                    </li>
                    <li>
                        <i class="fab fa-whatsapp"></i>
                        <div>
                            <strong>واتساب</strong>
                            <span><a href="{{ $whatsappUrl }}" target="_blank">{{ $whatsappNumber }}</a></span>
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>العنوان</strong>
                            <span>القاهرة، مصر</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="copyright mb-0">
                        &copy; {{ date('Y') }} <strong>{{ $storeName }}</strong>. جميع الحقوق محفوظة.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <span>صُنع بـ <i class="fas fa-heart text-danger"></i> في مصر</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
