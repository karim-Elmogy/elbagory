<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'متجر إلكتروني')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Cairo', sans-serif;
        }
        
        .alert-dismissible .btn-close {
         right:unset !important;
         left: 0px !important;
        }
        
        /* Noon‑like color system */
        :root {
            --accent-yellow: #ffd500;
            --accent-yellow-dark: #f2c500;
            --primary-color: #404553;
            --primary-light: #5c6478;
            --primary-dark: #2b3040;
            --secondary-color: #6c757d;
            --success-color: #00a88e;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f7f7fa;
            --dark-color: #2b3040;
            --gradient-primary: linear-gradient(135deg, #404553 0%, #2b3040 100%);
            --gradient-secondary: linear-gradient(135deg, #ffd500 0%, #ffea80 100%);
            --gradient-success: linear-gradient(135deg, #00a88e 0%, #00c2a5 100%);
        }
        
        body {
            background: #f7f7fa;
            padding-top: 140px;
            overflow-x: hidden;
            line-height: 1.6;
            color: #333;
        }
        
        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Fade In Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }
        
        .animate-slide-in {
            animation: slideInRight 0.6s ease-out;
        }
        
        /* Header Styles */
        .main-header {
            background: var(--accent-yellow);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .top-bar {
            background: var(--primary-dark);
            color: #ffffff;
            padding: 10px 0;
            font-size: 13px;
        }
        
        .top-bar a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 12px;
            transition: opacity 0.2s;
        }
        
        .top-bar a:hover {
            opacity: 0.8;
        }
        
        .header-content {
            padding: 12px 0;
        }
        
        .logo {
            font-size: 26px;
            font-weight: 800;
            color: var(--primary-dark);
            text-decoration: none;
        }
        
        .logo img {
            object-fit: contain;
        }
        
        .logo span {
            margin-right: 10px;
        }
        
        .search-box {
            position: relative;
        }
        
        .search-box input {
            border-radius: 4px;
            padding: 12px 50px 12px 20px;
            border: none;
            background: #ffffff;
            font-size: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .search-box input:focus {
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            outline: none;
        }
        
        .search-box input::placeholder {
            color: #999;
        }
        
        .search-box button {
            position: absolute;
            left: 4px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: var(--primary-dark);
            padding: 8px 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-box button:hover {
            color: var(--primary-color);
        }
        
        .cart-icon {
            position: relative;
            font-size: 24px;
            color: var(--primary-dark);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .cart-icon:hover {
            color: var(--primary-color);
            transform: scale(1.1) rotate(5deg);
        }
        
        .cart-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #ff4444;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border: 2px solid var(--accent-yellow);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .wishlist-btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .wishlist-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(220, 53, 69, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.4s, height 0.4s;
        }
        
        .wishlist-btn:hover::before {
            width: 100px;
            height: 100px;
        }
        
        .wishlist-btn:hover {
            transform: scale(1.15) rotate(10deg);
            border-color: #dc3545;
        }
        
        .wishlist-btn i.text-danger {
            animation: pulse 1.5s infinite;
        }
        
        .wishlist-btn.btn-outline-danger {
            border-color: #dc3545;
        }
        
        .wishlist-btn.btn-outline-danger i.text-muted {
            color: #6c757d !important;
        }
        
        .wishlist-btn.btn-outline-danger:hover i.text-muted {
            color: #dc3545 !important;
        }
        
        /* Navigation */
        .main-nav {
            background: #ffffff;
            padding: 0;
            border-top: 1px solid #e5e5e5;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .main-nav .nav {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .main-nav .nav-link {
            color: var(--primary-dark) !important;
            padding: 12px 16px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s ease;
            position: relative;
            border-bottom: 2px solid transparent;
        }
        
        .main-nav .nav-link i {
            margin-left: 6px;
            font-size: 14px;
        }
        
        .main-nav .nav-link:hover {
            color: var(--primary-color) !important;
            background: rgba(64, 69, 83, 0.03);
            border-bottom-color: var(--primary-color);
        }
        
        .main-nav form button.nav-link {
            width: 100%;
            text-align: right;
        }
        
        .main-nav form button.nav-link:hover {
            background: rgba(255,255,255,0.1);
        }
        
        /* Product Card */
        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-bottom: 25px;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            border: 1px solid #f0f0f0;
        }
        
        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.4s ease;
            transform-origin: right;
            z-index: 1;
        }
        
        .product-card:hover::before {
            transform: scaleX(1);
        }
        
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.15);
            border-color: var(--primary-color);
        }
        
        .product-image-wrapper {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 25px;
            height: 260px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            z-index: 0;
        }
        
        .product-card:hover .product-image {
            transform: scale(1.1);
        }
        
        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 2;
            background: var(--gradient-primary);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .product-info {
            padding: 22px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background: white;
        }
        
        .product-title {
            font-size: 17px;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 10px;
            line-height: 1.5;
            min-height: 51px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.3s ease;
        }
        
        .product-title a {
            color: var(--primary-dark);
            text-decoration: none;
        }
        
        .product-card:hover .product-title a {
            color: var(--primary-color);
        }
        
        .product-category {
            margin-bottom: 12px;
        }
        
        .product-category .badge {
            background: #f0f0f0;
            color: var(--primary-dark);
            font-size: 11px;
            padding: 5px 10px;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .product-price {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .product-price .old-price {
            font-size: 15px;
            color: #999;
            text-decoration: line-through;
            font-weight: 400;
        }
        
        .product-stock-alert {
            background: #fff3cd;
            color: #856404;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .product-stock-alert i {
            font-size: 14px;
        }
        
        .product-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }
        
        .btn-add-cart {
            flex: 1;
            padding: 13px;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        
        .btn-add-cart::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-add-cart:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(64, 69, 83, 0.35);
            color: white;
        }
        
        .btn-add-cart:active {
            transform: translateY(0);
        }
        
        .btn-wishlist {
            width: 45px;
            height: 45px;
            padding: 0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: 2px solid #e0e0e0;
            background: white;
        }
        
        .btn-wishlist:hover {
            border-color: var(--danger-color);
            background: #fff5f5;
            transform: scale(1.1);
        }
        
        .btn-wishlist i.text-danger {
            color: var(--danger-color) !important;
        }

        /* Orders Page */
        .order-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }
        
        .order-card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            transform: translateY(-4px);
        }
        
        .order-header {
            background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
            padding: 20px 25px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .order-number {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .order-number i {
            margin-left: 8px;
        }
        
        .order-date {
            font-size: 14px;
            margin-top: 8px;
        }
        
        .order-date i {
            margin-left: 5px;
        }
        
        .order-status {
            font-size: 13px;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .order-status i {
            margin-left: 6px;
        }
        
        .order-body {
            padding: 25px;
        }
        
        .order-info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8f9fc;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .order-info-item:hover {
            background: #f0f2f7;
            transform: translateX(-4px);
        }
        
        .order-info-item i {
            font-size: 20px;
            width: 30px;
            text-align: center;
        }
        
        .order-info-item .label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
        }
        
        .order-info-item .value {
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .order-footer {
            padding: 20px 25px;
            background: #f8f9fc;
            border-top: 1px solid #f0f0f0;
            display: flex;
            justify-content: flex-end;
        }
        
        .order-footer .btn {
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .order-footer .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(64, 69, 83, 0.3);
        }
        
        .empty-orders {
            background: white;
            border-radius: 16px;
            padding: 60px 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .empty-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .empty-icon i {
            font-size: 60px;
            color: #ccc;
        }

        /* Order Detail Page */
        .order-detail-header {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .order-detail-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .order-detail-title i {
            margin-left: 10px;
        }
        
        .order-detail-date {
            font-size: 14px;
            margin-top: 8px;
        }
        
        .order-detail-date i {
            margin-left: 5px;
        }
        
        .order-detail-status {
            font-size: 15px;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
        }
        
        .order-detail-status i {
            margin-left: 8px;
        }
        
        .order-detail-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .order-detail-card-header {
            background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
            padding: 20px 25px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .order-detail-card-header h5 {
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .order-detail-card-header i {
            margin-left: 8px;
        }
        
        .order-detail-card-body {
            padding: 25px;
        }
        
        .order-items-list {
            margin-bottom: 25px;
        }
        
        .order-item-card {
            display: flex;
            gap: 15px;
            padding: 20px;
            background: #f8f9fc;
            border-radius: 12px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }
        
        .order-item-card:hover {
            background: #f0f2f7;
            transform: translateX(-4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        .order-item-image {
            width: 100px;
            height: 100px;
            flex-shrink: 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .order-item-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 8px;
        }
        
        .order-item-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .order-item-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 10px;
        }
        
        .order-item-name a {
            color: var(--primary-dark);
            transition: color 0.3s ease;
        }
        
        .order-item-name a:hover {
            color: var(--primary-color);
        }
        
        .order-item-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            font-size: 13px;
            color: #666;
        }
        
        .order-item-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .order-item-meta i {
            font-size: 12px;
        }
        
        .order-item-total {
            display: flex;
            align-items: center;
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-color);
            min-width: 120px;
            justify-content: flex-end;
        }
        
        .order-summary {
            background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #f0f0f0;
        }
        
        .order-summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
            font-size: 15px;
        }
        
        .order-summary-item:last-of-type {
            border-bottom: none;
        }
        
        .order-summary-item span:first-child {
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .order-summary-item span:first-child i {
            font-size: 14px;
        }
        
        .order-summary-item span:last-child {
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .order-summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 0 0;
            margin-top: 15px;
            border-top: 2px solid var(--primary-color);
            font-size: 20px;
            font-weight: 700;
        }
        
        .order-summary-total span:first-child {
            color: var(--primary-dark);
        }
        
        .order-summary-total span:last-child {
            color: var(--primary-color);
            font-size: 24px;
        }
        
        .order-info-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .order-info-header {
            background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
            padding: 20px 25px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .order-info-header h5 {
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .order-info-header i {
            margin-left: 8px;
        }
        
        .order-info-body {
            padding: 20px 25px;
        }
        
        .order-info-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .order-info-item:last-child {
            border-bottom: none;
        }
        
        .order-info-item i {
            font-size: 20px;
            width: 30px;
            text-align: center;
            margin-top: 2px;
        }
        
        .order-info-item .label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .order-info-item .value {
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .order-actions-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 20px;
        }
        
        .order-actions-card .btn {
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .order-actions-card .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        /* Account Page */
        .account-header {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .account-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .account-title i {
            margin-left: 10px;
        }
        
        .account-type-badge .badge {
            border-radius: 25px;
        }
        
        .account-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .account-card-header {
            background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
            padding: 20px 25px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .account-card-header h5 {
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .account-card-header i {
            margin-left: 8px;
        }
        
        .account-card-body {
            padding: 25px;
        }
        
        .account-input {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .account-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(64, 69, 83, 0.1);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-label i {
            font-size: 14px;
        }
        
        .wholesale-section {
            background: #f8f9fc;
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .section-divider {
            text-align: center;
            position: relative;
            margin: 20px 0;
        }
        
        .section-divider::before,
        .section-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #e0e0e0;
        }
        
        .section-divider::before {
            right: 0;
        }
        
        .section-divider::after {
            left: 0;
        }
        
        .section-divider span {
            background: #f8f9fc;
            padding: 0 15px;
            position: relative;
            color: var(--primary-dark);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .account-form-actions {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
        }
        
        .account-form-actions .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .account-form-actions .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(64, 69, 83, 0.3);
        }
        
        .account-sidebar-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .account-sidebar-header {
            background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
            padding: 20px 25px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .account-sidebar-header h5 {
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .account-sidebar-header i {
            margin-left: 8px;
        }
        
        .account-sidebar-body {
            padding: 20px 25px;
        }
        
        .recent-orders-list {
            margin-bottom: 15px;
        }
        
        .recent-order-item {
            display: block;
            padding: 15px;
            background: #f8f9fc;
            border-radius: 10px;
            margin-bottom: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid #f0f0f0;
        }
        
        .recent-order-item:hover {
            background: #f0f2f7;
            transform: translateX(-4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            text-decoration: none;
        }
        
        .recent-order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .recent-order-number {
            font-weight: 600;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .recent-order-number i {
            font-size: 14px;
        }
        
        .recent-order-total {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 16px;
        }
        
        .recent-order-date {
            font-size: 13px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .recent-order-date i {
            font-size: 12px;
        }
        
        .empty-sidebar {
            text-align: center;
            padding: 30px 20px;
        }
        
        .empty-sidebar i {
            font-size: 50px;
            color: #ccc;
            margin-bottom: 15px;
        }
        
        .quick-stats {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .quick-stat-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f8f9fc;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .quick-stat-item:hover {
            background: #f0f2f7;
            transform: translateX(-4px);
        }
        
        .quick-stat-item i {
            font-size: 24px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .quick-stat-item .stat-label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
        }
        
        .quick-stat-item .stat-value {
            display: block;
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-dark);
        }

        /* Wishlist Page */
        .wishlist-header {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .wishlist-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .wishlist-title i {
            margin-left: 10px;
        }
        
        .wishlist-count-badge .badge {
            border-radius: 25px;
        }
        
        .wishlist-product-card {
            position: relative;
        }
        
        .wishlist-product-card .product-image-wrapper {
            position: relative;
        }
        
        .wishlist-remove-overlay {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 3;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .wishlist-product-card:hover .wishlist-remove-overlay {
            opacity: 1;
        }
        
        .wishlist-remove-form {
            margin: 0;
        }
        
        .btn-remove-wishlist {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid var(--danger-color);
            color: var(--danger-color);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            padding: 0;
        }
        
        .btn-remove-wishlist:hover {
            background: var(--danger-color);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        }
        
        .btn-remove-wishlist i {
            font-size: 16px;
        }
        
        .product-stock-info {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            margin-bottom: 12px;
            padding: 6px 10px;
            background: #f0f9ff;
            border-radius: 6px;
            color: #059669;
        }
        
        .product-stock-info.text-danger {
            background: #fef2f2;
            color: var(--danger-color);
        }
        
        .product-stock-info i {
            font-size: 12px;
        }
        
        .empty-wishlist {
            background: white;
            border-radius: 16px;
            padding: 80px 20px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .empty-wishlist-icon {
            width: 150px;
            height: 150px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px dashed #fca5a5;
        }
        
        .empty-wishlist-icon i {
            font-size: 70px;
            color: #fca5a5;
            animation: pulse 2s infinite;
        }
        
        .empty-wishlist-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 15px;
        }
        
        .empty-wishlist-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }
        
        .empty-wishlist .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .empty-wishlist .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(64, 69, 83, 0.3);
        }
        
        /* Delete Wishlist Modal */
        #deleteWishlistModal .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
        }
        
        #deleteWishlistModal .modal-header {
            padding: 20px 25px 0;
        }
        
        #deleteWishlistModal .modal-body {
            padding: 0 25px 25px;
        }
        
        .delete-wishlist-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #fca5a5;
        }
        
        .delete-wishlist-icon i {
            font-size: 40px;
            color: var(--danger-color);
        }
        
        #deleteWishlistModal .modal-title {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 22px;
        }
        
        #deleteWishlistModal .btn {
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        #deleteWishlistModal .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        }
        
        #deleteWishlistModal .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        /* Products Page */
        .products-page-header {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .products-page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .products-page-title i {
            margin-left: 10px;
        }
        
        .products-count-badge .badge {
            border-radius: 25px;
        }
        
        .products-sidebar {
            position: sticky;
            top: 100px;
        }
        
        .sidebar-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .sidebar-card-header {
            background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
            padding: 20px 25px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .sidebar-card-header h5 {
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .sidebar-card-header i {
            margin-left: 8px;
        }
        
        .sidebar-card-body {
            padding: 20px 25px;
        }
        
        .categories-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .category-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            background: #f8f9fc;
            border-radius: 10px;
            text-decoration: none;
            color: var(--primary-dark);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .category-item:hover {
            background: #f0f2f7;
            transform: translateX(-4px);
            text-decoration: none;
            color: var(--primary-color);
        }
        
        .category-item.active {
            background: var(--gradient-primary);
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(64, 69, 83, 0.2);
        }
        
        .category-item.active:hover {
            color: white;
        }
        
        .category-item i:first-child {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }
        
        .category-item span {
            flex: 1;
            font-weight: 500;
        }
        
        .category-item i.fa-check {
            font-size: 14px;
        }
        
        .empty-products {
            background: white;
            border-radius: 16px;
            padding: 80px 20px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .empty-products-icon {
            width: 150px;
            height: 150px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px dashed #93c5fd;
        }
        
        .empty-products-icon i {
            font-size: 70px;
            color: #93c5fd;
        }
        
        .empty-products-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 15px;
        }
        
        .empty-products-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }
        
        .empty-products .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .empty-products .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(64, 69, 83, 0.3);
        }

        /* Categories Page */
        .categories-page-header {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .categories-page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .categories-page-title i {
            margin-left: 10px;
        }
        
        .categories-count-badge .badge {
            border-radius: 25px;
        }
        
        .category-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #f0f0f0;
        }
        
        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.4s ease;
            transform-origin: right;
            z-index: 1;
        }
        
        .category-card:hover::before {
            transform: scaleX(1);
        }
        
        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.15);
            border-color: var(--primary-color);
        }
        
        .category-image-wrapper {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .category-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .category-card:hover .category-image {
            transform: scale(1.1);
        }
        
        .category-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        }
        
        .category-image-placeholder i {
            font-size: 80px;
            color: var(--primary-color);
            opacity: 0.6;
        }
        
        .category-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .category-card:hover .category-overlay {
            opacity: 1;
        }
        
        .btn-view-category {
            padding: 12px 24px;
            background: white;
            color: var(--primary-color);
            border: 2px solid white;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn-view-category:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.05);
        }
        
        .category-info {
            padding: 22px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background: white;
        }
        
        .category-name {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 10px;
            line-height: 1.4;
        }
        
        .category-name a {
            color: var(--primary-dark);
            transition: color 0.3s ease;
        }
        
        .category-card:hover .category-name a {
            color: var(--primary-color);
        }
        
        .category-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            line-height: 1.6;
            flex-grow: 1;
        }
        
        .category-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
            padding: 12px;
            background: #f8f9fc;
            border-radius: 10px;
        }
        
        .category-stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .category-stat-item i {
            font-size: 16px;
        }
        
        .category-card .btn-primary {
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .category-card .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(64, 69, 83, 0.3);
        }
        
        .empty-categories {
            background: white;
            border-radius: 16px;
            padding: 80px 20px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .empty-categories-icon {
            width: 150px;
            height: 150px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px dashed #93c5fd;
        }
        
        .empty-categories-icon i {
            font-size: 70px;
            color: #93c5fd;
        }
        
        .empty-categories-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 15px;
        }
        
        .empty-categories-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }
        
        .empty-categories .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .empty-categories .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(64, 69, 83, 0.3);
        }

        /* Notifications Page */
        .notifications-page-header {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .notifications-page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .notifications-page-title i {
            margin-left: 10px;
        }
        
        .notifications-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .notification-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 20px 25px;
            display: flex;
            gap: 20px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
        }
        
        .notification-card::before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--gradient-primary);
            border-radius: 16px 0 0 16px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .notification-card.unread {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
        }
        
        .notification-card.unread::before {
            opacity: 1;
        }
        
        .notification-card:hover {
            transform: translateX(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
        
        .notification-icon-wrapper {
            flex-shrink: 0;
        }
        
        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .notification-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .notification-header {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        
        .notification-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
            flex: 1;
        }
        
        .notification-card.read .notification-title {
            font-weight: 500;
            color: #666;
        }
        
        .notification-badge {
            background: var(--gradient-primary);
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .notification-message {
            font-size: 15px;
            color: #666;
            margin: 0;
            line-height: 1.6;
        }
        
        .notification-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 5px;
        }
        
        .notification-time {
            font-size: 13px;
            color: #999;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .notification-time i {
            font-size: 12px;
        }
        
        .notification-actions {
            display: flex;
            gap: 8px;
        }
        
        .notification-actions .btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.3s ease;
        }
        
        .notification-actions .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .notification-form {
            margin: 0;
        }
        
        .empty-notifications {
            background: white;
            border-radius: 16px;
            padding: 80px 20px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .empty-notifications-icon {
            width: 150px;
            height: 150px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px dashed #93c5fd;
        }
        
        .empty-notifications-icon i {
            font-size: 70px;
            color: #93c5fd;
        }
        
        .empty-notifications-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 15px;
        }
        
        .empty-notifications-text {
            font-size: 16px;
            color: #666;
        }
        
        /* Delete Notification Modal */
        #deleteNotificationModal .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
        }
        
        #deleteNotificationModal .modal-header {
            padding: 20px 25px 0;
        }
        
        #deleteNotificationModal .modal-body {
            padding: 0 25px 25px;
        }
        
        .delete-notification-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #fca5a5;
        }
        
        .delete-notification-icon i {
            font-size: 40px;
            color: var(--danger-color);
        }
        
        #deleteNotificationModal .modal-title {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 22px;
        }
        
        #deleteNotificationModal .btn {
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        #deleteNotificationModal .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
        }
        
        #deleteNotificationModal .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        /* Product Show Page */
        .product-detail-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
            padding: 25px;
            margin-bottom: 40px;
        }

        .product-detail-title {
            font-size: 26px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 10px;
        }

        .product-detail-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }

        .product-detail-meta .badge {
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 12px;
        }

        .product-detail-price-box {
            background: #f8f9fc;
            border-radius: 14px;
            padding: 16px 18px;
            margin-bottom: 18px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .product-detail-price-main {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary-dark);
        }

        .product-detail-price-sub {
            font-size: 14px;
            color: #666;
        }

        .product-detail-stock-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            background: #e8f5e9;
            color: #2e7d32;
            margin-bottom: 10px;
        }

        .product-detail-stock-pill.low {
            background: #fff3cd;
            color: #856404;
        }

        .product-detail-meta-list {
            list-style: none;
            padding: 0;
            margin: 0 0 16px 0;
            font-size: 14px;
            color: #555;
        }

        .product-detail-meta-list li {
            margin-bottom: 4px;
        }

        .product-detail-actions {
            margin-top: 10px;
        }

        .product-detail-actions .quantity-group {
            max-width: 160px;
        }

        .product-detail-actions .form-label {
            font-size: 13px;
            margin-bottom: 4px;
        }

        .product-detail-actions .btn-add-cart {
            height: 48px;
        }

        .product-detail-actions .btn-wishlist {
            height: 48px;
        }
        
        /* Footer */
        .main-footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            color: #e0e0e0;
            padding: 40px 0 0;
            margin-top: 60px;
            position: relative;
            overflow: hidden;
        }
        
        .main-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
        }
        
        .footer-section {
            margin-bottom: 25px;
        }
        
        .footer-logo-img {
            height: 50px;
            width: auto;
            object-fit: contain;
            filter: brightness(0) invert(1);
            margin-bottom: 15px;
        }
        
        .footer-title {
            font-size: 17px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 18px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 40px;
            height: 2px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }
        
        .footer-description {
            color: #b0b0b0;
            line-height: 1.6;
            margin-bottom: 18px;
            font-size: 13px;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
        }
        
        .footer-links a {
            color: #d0d0d0;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .footer-links a i {
            width: 18px;
            font-size: 14px;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .footer-links a:hover {
            color: #ffffff;
            padding-right: 8px;
        }
        
        .footer-links a:hover i {
            color: var(--success-color);
            transform: translateX(-3px);
        }
        
        .footer-contact {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-contact li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .footer-contact li:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(-3px);
        }
        
        .footer-contact li i {
            font-size: 18px;
            color: var(--primary-color);
            margin-top: 2px;
            min-width: 20px;
        }
        
        .footer-contact li div {
            flex: 1;
        }
        
        .footer-contact li strong {
            display: block;
            color: #ffffff;
            font-size: 12px;
            margin-bottom: 3px;
            font-weight: 600;
        }
        
        .footer-contact li span {
            display: block;
            color: #b0b0b0;
            font-size: 13px;
        }
        
        .footer-contact li a {
            color: var(--success-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer-contact li a:hover {
            color: #ffffff;
            text-decoration: underline;
        }
        
        .social-icons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            font-size: 16px;
        }
        
        .social-icon::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.4s, height 0.4s;
        }
        
        .social-icon:hover::before {
            width: 100%;
            height: 100%;
        }
        
        .social-icon.facebook {
            background: #1877f2;
        }
        
        .social-icon.twitter {
            background: #1da1f2;
        }
        
        .social-icon.instagram {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        }
        
        .social-icon.whatsapp {
            background: #25d366;
        }
        
        .social-icon:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            color: #ffffff;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px 0;
            margin-top: 25px;
        }
        
        .copyright {
            color: #b0b0b0;
            font-size: 14px;
        }
        
        .copyright strong {
            color: #ffffff;
        }
        
        .footer-bottom p {
            color: #b0b0b0;
            font-size: 14px;
        }
        
        /* Pagination Styles */
        .pagination {
            justify-content: center;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        
        .pagination .page-link {
            color: var(--primary-color);
            border: 1px solid #dee2e6;
            padding: 10px 15px;
            margin: 0 3px;
            border-radius: 5px;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .pagination .page-link:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
            cursor: not-allowed;
            opacity: 0.6;
        }
        
        .pagination .page-item.disabled .page-link:hover {
            background-color: #fff;
            color: #6c757d;
            border-color: #dee2e6;
        }
        
        .pagination-info {
            text-align: center;
            color: #6c757d;
            margin: 15px 0;
            font-size: 0.9rem;
        }
        
        
        
        /* Breadcrumb */
        .breadcrumb-section {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(248,249,250,0.9) 100%);
            padding: 20px 0;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            animation: fadeInDown 0.6s ease-out;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .breadcrumb-item a {
            transition: color 0.3s ease;
        }
        
        .breadcrumb-item a:hover {
            color: var(--primary-color) !important;
        }
        
        /* Cart Page */
        .cart-page-header {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .cart-page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .cart-page-title i {
            margin-left: 10px;
        }
        
        .cart-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .cart-table th {
            background: var(--primary-color);
            color: white;
            border: none;
            font-weight: 600;
        }
        
        .cart-table td {
            vertical-align: middle;
        }
        
        .cart-product-info h6 {
            font-weight: 600;
            margin-bottom: 4px;
            color: var(--primary-dark);
        }
        
        .cart-product-info small {
            font-size: 12px;
        }
        
        .cart-quantity-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .cart-quantity-input {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: #f8f9fc;
        }
        
        .cart-quantity-input:focus {
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(64, 69, 83, 0.1);
            outline: none;
        }
        
        .cart-quantity-btn {
            border-radius: 8px;
            padding: 8px 12px;
            min-width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .cart-quantity-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(64, 69, 83, 0.3);
        }
        
        .cart-quantity-btn i {
            font-size: 20px;
        }
        
        .cart-summary-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .cart-summary-header {
            background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
            padding: 20px 25px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .cart-summary-header h5 {
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .cart-summary-body {
            padding: 20px 25px;
        }
        
        .cart-summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .cart-summary-row span:first-child {
            color: #666;
        }
        
        .cart-summary-row span:last-child {
            font-weight: 600;
        }
        
        .cart-summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 2px solid #f0f0f0;
            font-size: 18px;
            font-weight: 700;
        }
        
        .cart-summary-total span:last-child {
            color: var(--primary-color);
            font-size: 22px;
        }
        
        .coupon-form .form-control {
            border-radius: 10px;
        }
        
        .coupon-form .btn {
            border-radius: 10px;
        }
        
        .empty-cart {
            background: white;
            border-radius: 16px;
            padding: 80px 20px;
            text-align: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }
        
        .empty-cart-icon {
            width: 150px;
            height: 150px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #f0f9ff 0%, #ffffff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px dashed #93c5fd;
        }
        
        .empty-cart-icon i {
            font-size: 70px;
            color: #93c5fd;
        }
        
        .empty-cart-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 15px;
        }
        
        .empty-cart-text {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }
        
        .empty-cart .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .empty-cart .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(64, 69, 83, 0.3);
        }
        
        /* Alert */
        .alert {
            border-radius: 8px;
            border: none;
            animation: slideInRight 0.5s ease-out;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 14px 18px;
        }
        
        .alert-success {
            background: #d4edda;
            border-left: 4px solid var(--success-color);
            color: #155724;
        }
        
        .alert-info {
            background: #d1ecf1;
            border-left: 4px solid var(--info-color);
            color: #0c5460;
        }
        
        .alert-warning {
            background: #fff3cd;
            border-left: 4px solid var(--warning-color);
            color: #856404;
        }
        
        .alert-danger {
            background: #f8d7da;
            border-left: 4px solid var(--danger-color);
            color: #721c24;
        }
        
        /* Badge */
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 12px;
            transition: all 0.3s ease;
        }
        
        .badge:hover {
            transform: translateY(-1px);
        }
        
        .bg-secondary {
            background: var(--gradient-secondary) !important;
        }
        
        /* Mobile Menu Toggle */
        .mobile-nav-toggle {
            display: none;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 0 0 8px 0;
            font-size: 1.3rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            z-index: 1001;
            position: fixed;
            top: 0;
            left: 0;
        }
        
        .mobile-nav-toggle:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }
        
        .mobile-nav-toggle.active {
            background: var(--danger-color);
        }
        
        .mobile-nav-toggle.active i::before {
            content: "\f00d";
        }
        
        .mobile-nav-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .mobile-nav-overlay.active {
            display: block;
            opacity: 1;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .mobile-nav-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            
            .header-content .col-md-3:first-child {
                flex: 0 0 auto;
                width: auto;
            }
            
            .header-content .col-md-6 {
                flex: 1 1 auto;
                max-width: 100%;
            }
            
            .header-content .col-md-3:last-child {
                flex: 0 0 auto;
                width: auto;
            }
            
            .logo span {
                display: none;
            }
            
            .main-nav {
                position: relative;
            }
            
            .main-nav .nav {
                position: fixed;
                top: 0;
                right: -100%;
                width: 280px;
                max-width: 85%;
                height: 100vh;
                background: white;
                flex-direction: column;
                align-items: stretch;
                padding: 60px 0 20px;
                margin: 0;
                box-shadow: -2px 0 10px rgba(0,0,0,0.1);
                transition: right 0.3s ease;
                z-index: 999;
                overflow-y: auto;
            }
            
            .main-nav .nav.active {
                right: 0;
            }
            
            .main-nav .nav-item {
                width: 100%;
                margin: 0;
            }
            
            .main-nav .nav-link {
                padding: 15px 20px;
                border-bottom: 1px solid #f0f0f0;
                border-right: none;
                display: flex;
                align-items: center;
                gap: 12px;
                color: var(--primary-dark) !important;
                font-weight: 500;
                transition: all 0.2s ease;
            }
            
            .main-nav .nav-link:hover {
                background: rgba(64, 69, 83, 0.05);
                color: var(--primary-color) !important;
                padding-right: 25px;
            }
            
            .main-nav .nav-link i {
                width: 20px;
                text-align: center;
                font-size: 16px;
            }
            
            .main-nav form button.nav-link {
                color: var(--danger-color) !important;
                border-bottom: 1px solid #f0f0f0;
            }
            
            .main-nav form button.nav-link:hover {
                background: rgba(220, 53, 69, 0.05);
                color: var(--danger-color) !important;
            }
        }
        
        @media (max-width: 768px) {
            body {
                padding-top: 120px;
            }
            
            .top-bar {
                font-size: 12px;
                padding: 5px 0;
            }
            
            .top-bar span,
            .top-bar a {
                font-size: 11px;
                margin: 0 5px;
            }
            
            .header-content {
                padding: 10px 0;
            }
            
            .logo img {
                height: 40px;
            }
            
            .search-box input {
                padding: 8px 40px 8px 15px;
                font-size: 14px;
            }
            
            .search-box button {
                padding: 6px 12px;
            }
            
            .cart-icon {
                font-size: 20px;
            }
            
            .btn-sm {
                font-size: 0.8rem;
                padding: 5px 10px;
            }
            
            .product-image-wrapper {
                height: 220px;
                padding: 20px;
            }
            
            .product-image {
                height: 100%;
            }
            
            .product-card {
                margin-bottom: 20px;
            }
            
            .product-info {
                padding: 18px;
            }
            
            .product-title {
                font-size: 15px;
                min-height: 45px;
            }
            
            .product-price {
                font-size: 20px;
            }
            
            .btn-add-cart {
                padding: 10px;
                font-size: 13px;
            }
            
            .btn-add-cart span {
                display: none;
            }
            
            .btn-wishlist {
                width: 40px;
                height: 40px;
            }
            
            .main-footer {
                padding: 40px 0 0;
                margin-top: 60px;
            }
            
            .footer-section {
                margin-bottom: 35px;
            }
            
            .footer-title {
                font-size: 16px;
                margin-bottom: 20px;
            }
            
            .footer-contact li {
                padding: 12px;
                margin-bottom: 15px;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding-top: 100px;
            }
            
            .top-bar {
                display: none;
            }
            
            .header-content .row {
                flex-wrap: nowrap;
            }
            
            .logo {
                font-size: 18px;
            }
            
            .logo img {
                height: 35px;
            }
            
            .search-box {
                margin: 0 5px;
            }
            
            .search-box input {
                font-size: 13px;
                padding: 6px 35px 6px 12px;
            }
            
            .cart-icon {
                font-size: 18px;
            }
            
            .cart-badge {
                width: 16px;
                height: 16px;
                font-size: 9px;
            }
            
            /* Hero Banner */
            .hero-banner-section {
                margin-bottom: 20px;
            }
            
            .hero-banner {
                padding: 20px !important;
                min-height: 200px !important;
            }
            
            .hero-banner h2 {
                font-size: 20px !important;
            }
            
            .hero-banner p {
                font-size: 14px !important;
            }
            
            /* Products */
            .product-image-wrapper {
                height: 180px;
                padding: 15px;
            }
            
            .product-info {
                padding: 15px;
            }
            
            .product-title {
                font-size: 14px;
                min-height: 40px;
            }
            
            .product-price {
                font-size: 18px;
            }
            
            .product-actions {
                flex-direction: column;
                gap: 8px;
            }
            
            .btn-add-cart {
                width: 100%;
                font-size: 12px;
                padding: 8px;
            }
            
            .btn-wishlist {
                width: 100%;
            }
            
            /* Products Page */
            .products-page-header {
                padding: 15px;
                margin-bottom: 20px;
            }
            
            .products-page-title {
                font-size: 22px;
            }
            
            .products-sidebar {
                position: static;
                margin-bottom: 20px;
            }
            
            .products-sidebar .card {
                margin-bottom: 15px;
            }
            
            /* Product Detail */
            .product-detail-header {
                padding: 15px;
            }
            
            .product-detail-title {
                font-size: 22px;
            }
            
            .product-detail-card {
                margin-bottom: 15px;
            }
            
            .product-detail-card-body {
                padding: 15px;
            }
            
            .product-price-box {
                padding: 15px;
            }
            
            .product-action-area {
                padding: 15px;
            }
            
            .product-action-area .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            /* Cart */
            .cart-page-header {
                padding: 15px;
            }
            
            .cart-page-title {
                font-size: 20px;
            }
            
            .cart-table {
                margin-bottom: 15px;
            }
            
            .cart-table table {
                width: 100%;
                border: 0;
            }
            
            .cart-table thead {
                display: none;
            }
            
            .cart-item-row {
                display: block;
                margin-bottom: 12px;
                border: 1px solid #f0f0f0;
                border-radius: 12px;
                padding: 10px 12px;
                box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            }
            
            .cart-table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 6px 4px;
                font-size: 13px;
                border-top: none;
            }
            
            .cart-table td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #666;
                margin-left: 10px;
                font-size: 12px;
            }
            
            .cart-table td:first-child {
                align-items: flex-start;
            }
            
            .cart-product-info h6 {
                font-size: 14px;
            }
            
            .cart-product-info small {
                font-size: 12px;
            }
            
            .cart-quantity-wrapper {
                justify-content: flex-end;
            }
            
            .cart-product-image {
                width: 60px;
                height: 60px;
            }
            
            .cart-quantity-input {
                width: 60px !important;
                font-size: 13px;
            }
            
            .cart-summary-card {
                margin-top: 10px;
            }
            
            .cart-summary-body {
                padding: 15px;
            }
            
            .empty-cart {
                padding: 40px 20px;
            }
            
            .empty-cart-icon {
                width: 120px;
                height: 120px;
            }
            
            .empty-cart-icon i {
                font-size: 50px;
            }
            
            .empty-cart-title {
                font-size: 22px;
            }
            
            /* Orders */
            .orders-page-header {
                padding: 15px;
            }
            
            .orders-page-title {
                font-size: 22px;
            }
            
            .order-card {
                margin-bottom: 15px;
            }
            
            .order-header {
                padding: 15px;
            }
            
            .order-number {
                font-size: 18px;
            }
            
            .order-body {
                padding: 15px;
            }
            
            .order-info-item {
                padding: 10px;
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .order-footer {
                padding: 15px;
            }
            
            .order-footer .btn {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .order-detail-header {
                padding: 15px;
            }
            
            .order-detail-title {
                font-size: 22px;
            }
            
            .order-detail-card-header {
                padding: 15px;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .order-detail-card-body {
                padding: 15px;
            }
            
            .order-item-card {
                flex-direction: column;
                padding: 15px;
            }
            
            .order-item-image {
                width: 100%;
                height: 200px;
            }
            
            .order-item-total {
                min-width: auto;
                width: 100%;
                justify-content: flex-start;
                margin-top: 10px;
                padding-top: 10px;
                border-top: 1px solid #e0e0e0;
            }
            
            .order-summary {
                padding: 15px;
            }
            
            .order-info-card {
                margin-bottom: 15px;
            }
            
            .order-info-header {
                padding: 15px;
            }
            
            .order-info-body {
                padding: 15px;
            }
            
            /* Account */
            .account-header {
                padding: 15px;
            }
            
            .account-title {
                font-size: 22px;
            }
            
            .account-card-header {
                padding: 15px;
            }
            
            .account-card-body {
                padding: 15px;
            }
            
            .account-sidebar {
                margin-top: 20px;
            }
            
            .account-sidebar-header {
                padding: 15px;
            }
            
            .account-sidebar-body {
                padding: 15px;
            }
            
            .recent-order-item {
                padding: 12px;
            }
            
            .quick-stat-item {
                padding: 12px;
            }
            
            /* Wishlist */
            .wishlist-header {
                padding: 15px;
            }
            
            .wishlist-title {
                font-size: 22px;
            }
            
            .wishlist-product-card {
                margin-bottom: 15px;
            }
            
            /* Categories */
            .categories-page-header {
                padding: 15px;
            }
            
            .categories-page-title {
                font-size: 22px;
            }
            
            .category-card {
                margin-bottom: 15px;
            }
            
            /* Notifications */
            .notifications-page-header {
                padding: 15px;
            }
            
            .notifications-page-title {
                font-size: 22px;
            }
            
            .notification-card {
                margin-bottom: 15px;
            }
            
            .notification-content {
                padding: 15px;
            }
            
            /* Auth Pages */
            .auth-page {
                padding: 20px 0;
            }
            
            .auth-card {
                margin: 0 10px;
            }
            
            .auth-title {
                font-size: 22px;
            }
            
            .auth-subtitle {
                font-size: 13px;
            }
            
            /* Breadcrumb */
            .breadcrumb-section {
                padding: 15px 0;
            }
            
            /* Container */
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            /* Buttons */
            .btn {
                font-size: 14px;
                padding: 8px 16px;
            }
            
            .btn-lg {
                font-size: 16px;
                padding: 10px 20px;
            }
            
            /* Cards */
            .card {
                margin-bottom: 15px;
            }
            
            .card-header {
                padding: 15px;
            }
            
            .card-body {
                padding: 15px;
            }
            
            /* Tables */
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .table {
                font-size: 13px;
            }
            
            .table th,
            .table td {
                padding: 8px 5px;
            }
            
            /* Modals */
            .modal-dialog {
                margin: 10px;
            }
            
            .modal-content {
                border-radius: 10px;
            }
            
            .modal-header {
                padding: 15px;
            }
            
            .modal-body {
                padding: 15px;
            }
            
            .modal-footer {
                padding: 15px;
            }
            
            /* Footer */
            .main-footer {
                padding: 30px 0 0;
                margin-top: 50px;
            }
            
            .footer-section {
                margin-bottom: 30px;
            }
            
            .footer-logo-img {
                height: 50px;
            }
            
            .footer-title {
                font-size: 16px;
                margin-bottom: 18px;
            }
            
            .footer-description {
                font-size: 13px;
            }
            
            .footer-links a,
            .footer-contact li span {
                font-size: 13px;
            }
            
            .footer-contact li {
                padding: 12px;
                margin-bottom: 12px;
            }
            
            .footer-contact li i {
                font-size: 18px;
            }
            
            .social-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            
            .footer-bottom {
                padding: 20px 0;
                margin-top: 30px;
            }
            
            .copyright,
            .footer-bottom p {
                font-size: 12px;
            }
            
            .footer-bottom .row {
                text-align: center;
            }
            
            .footer-bottom .col-md-6:last-child {
                margin-top: 10px;
            }
            
            /* Floating Buttons */
            .floating-buttons {
                right: 15px;
                bottom: 15px;
            }
            
            .floating-btn {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
            
            .floating-btn .tooltip {
                display: none;
            }
            
            /* Empty States */
            .empty-wishlist,
            .empty-notifications,
            .empty-categories {
                padding: 40px 20px;
            }
            
            .empty-wishlist-icon,
            .empty-notifications-icon,
            .empty-categories-icon {
                width: 100px;
                height: 100px;
            }
            
            .empty-wishlist-icon i,
            .empty-notifications-icon i,
            .empty-categories-icon i {
                font-size: 40px;
            }
            
            .empty-wishlist-title,
            .empty-notifications-title,
            .empty-categories-title {
                font-size: 20px;
            }
            
            /* Pagination */
            .pagination {
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .pagination .page-link {
                padding: 8px 12px;
                font-size: 13px;
            }
            
            /* Alerts */
            .alert {
                padding: 12px 15px;
                font-size: 13px;
            }
            
            /* Badges */
            .badge {
                font-size: 11px;
                padding: 4px 8px;
            }
            
            /* Spacing */
            .mb-4 {
                margin-bottom: 20px !important;
            }
            
            .mb-5 {
                margin-bottom: 25px !important;
            }
            
            .mt-4 {
                margin-top: 20px !important;
            }
            
            .mt-5 {
                margin-top: 25px !important;
            }
            
            .py-5 {
                padding-top: 25px !important;
                padding-bottom: 25px !important;
            }
            
            .my-5 {
                margin-top: 25px !important;
                margin-bottom: 25px !important;
            }
            
            .product-image {
                height: 100%;
            }
            
            .product-info {
                padding: 15px;
            }
            
            .product-title {
                font-size: 14px;
                min-height: 40px;
            }
            
            .product-price {
                font-size: 18px;
            }
            
            .product-actions {
                flex-direction: column;
                gap: 8px;
            }
            
            .btn-add-cart {
                width: 100%;
                padding: 10px;
                font-size: 13px;
            }
            
            .btn-add-cart span {
                display: inline;
            }
            
            .btn-wishlist {
                width: 100%;
            }
            
            .main-footer {
                padding: 30px 0 0;
                margin-top: 50px;
            }
            
            .footer-section {
                margin-bottom: 30px;
            }
            
            .footer-logo-img {
                height: 50px;
            }
            
            .footer-title {
                font-size: 16px;
                margin-bottom: 18px;
            }
            
            .footer-description {
                font-size: 13px;
            }
            
            .footer-links a,
            .footer-contact li span {
                font-size: 13px;
            }
            
            .footer-contact li {
                padding: 12px;
                margin-bottom: 12px;
            }
            
            .footer-contact li i {
                font-size: 18px;
            }
            
            .social-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            
            .footer-bottom {
                padding: 20px 0;
                margin-top: 30px;
            }
            
            .copyright,
            .footer-bottom p {
                font-size: 12px;
            }
            
            .footer-bottom .row {
                text-align: center;
            }
            
            .footer-bottom .col-md-6:last-child {
                margin-top: 10px;
            }
        }
        
        /* Floating Action Buttons */
        .floating-buttons {
            position: fixed;
            right:  20px;
            bottom: 20px;
            z-index: 999;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .floating-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            animation: fadeIn 0.6s ease-out;
        }
        
        .floating-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.4s, height 0.4s;
        }
        
        .floating-btn:hover::before {
            width: 100%;
            height: 100%;
        }
        
        .floating-btn:hover {
            transform: scale(1.15) rotate(5deg);
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
            color: white;
        }
        
        .floating-btn i {
            position: relative;
            z-index: 1;
        }
        
        .floating-btn.whatsapp {
            background: #25D366;
        }
        
        .floating-btn.wholesale {
            background: var(--primary-color);
        }
        
        .floating-btn .tooltip {
            position: absolute;
            right: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--accent-yellow);
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.3s, visibility 0.3s;
            font-size: 14px;
            z-index: 1000;
        }
        
        .floating-btn .tooltip::after {
            content: '';
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: var(--accent-yellow-dark);
        }
        
        .floating-btn:hover .tooltip {
            opacity: 1;
            visibility: visible;
        }
        
        /* Loading Animation */
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        
        .loading {
            display: inline-block;
            animation: spin 1s linear infinite;
        }
        
        /* Smooth Page Transitions */
        main {
            animation: fadeIn 0.5s ease-out;
        }
        
        /* Button Improvements */
        .btn {
            transition: all 0.3s ease;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(64, 69, 83, 0.3);
            color: white;
        }
        
        .btn-success {
            background: var(--gradient-success);
            border: none;
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 168, 142, 0.3);
            color: white;
        }
        
        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }
        
        /* Card Improvements */
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        
        .card-header {
            background: var(--gradient-primary);
            color: white;
            border-radius: 0 !important;
            border: none;
            font-weight: 600;
            padding: 12px 16px;
        }
        
        /* Form Improvements */
        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid #dee2e6;
            padding: 10px 15px;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(64, 69, 83, 0.1);
            outline: none;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .input-group-text {
            background: #f8f9fa;
            border-color: #dee2e6;
            color: var(--primary-dark);
        }
        
        /* Table Improvements */
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table thead {
            background: var(--gradient-primary);
            color: white;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background: rgba(64, 69, 83, 0.05);
        }
        
        /* Hero Banner */
        .hero-banner-section {
            margin-top: 20px;
        }
        
        .hero-banner {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .hero-banner:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .hero-banner-section .carousel-control-prev,
        .hero-banner-section .carousel-control-next {
            width: 40px;
            height: 40px;
            background: rgba(0,0,0,0.3);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.8;
        }
        
        .hero-banner-section .carousel-control-prev:hover,
        .hero-banner-section .carousel-control-next:hover {
            opacity: 1;
        }
        
        .hero-banner-section .carousel-control-prev {
            right: 15px;
            left: auto;
        }
        
        .hero-banner-section .carousel-control-next {
            left: 15px;
            right: auto;
        }
        
        .hero-banner-section .carousel-indicators {
            margin-bottom: 15px;
        }
        
        .hero-banner-section .carousel-indicators button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.5);
            border: none;
        }
        
        .hero-banner-section .carousel-indicators button.active {
            background-color: white;
        }
        
        /* Benefits Section */
        .benefit-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .benefit-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.12);
            transform: translateY(-4px);
        }
        
        .benefit-card i {
            font-size: 32px;
            color: var(--primary-color);
            min-width: 40px;
        }
        
        .benefit-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 4px;
        }
        
        .benefit-subtitle {
            font-size: 13px;
            color: #666;
        }
        
        /* Products Section */
        .products-section {
            margin-top: 40px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0;
        }
        
        .view-all-btn {
            background: var(--primary-dark);
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .view-all-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateX(-4px);
        }
        
        @media (max-width: 768px) {
            .floating-buttons {
                left: 15px;
                bottom: 15px;
            }
            
            .floating-btn {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
            
            .floating-btn .tooltip {
                display: none;
            }
            
            .hero-banner {
                padding: 24px !important;
                min-height: 160px !important;
            }
            
            .hero-content h2 {
                font-size: 1.5rem !important;
            }
            
            .section-title {
                font-size: 22px;
            }
            
            .view-all-btn {
                padding: 8px 16px;
                font-size: 12px;
            }
        }
        
        /* Ads Carousel */
        .ads-carousel-section {
            margin-top: 20px;
        }
        
        .ads-carousel-section .carousel-item {
            transition: transform 0.6s ease-in-out;
        }
        
        .ads-carousel-section .ad-banner {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }
        
        .ads-carousel-section .ad-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 3s infinite;
        }
        
        .ads-carousel-section .ad-banner:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }
        
        .ads-carousel-section .ad-banner h2,
        .ads-carousel-section .ad-banner p {
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .ads-carousel-section .carousel-control-prev,
        .ads-carousel-section .carousel-control-next {
            width: 50px;
            height: 50px;
            background: rgba(0,0,0,0.5);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.7;
        }
        
        .ads-carousel-section .carousel-control-prev:hover,
        .ads-carousel-section .carousel-control-next:hover {
            opacity: 1;
        }
        
        .ads-carousel-section .carousel-control-prev {
            right: 20px;
            left: auto;
        }
        
        .ads-carousel-section .carousel-control-next {
            left: 20px;
            right: auto;
        }
        
        .ads-carousel-section .carousel-indicators {
            margin-bottom: 20px;
        }
        
        .ads-carousel-section .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.5);
            border: 2px solid white;
        }
        
        .ads-carousel-section .carousel-indicators button.active {
            background-color: white;
        }
        
        @media (max-width: 768px) {
            .ads-carousel-section .ad-banner {
                padding: 30px 20px !important;
                min-height: 150px !important;
            }
            
            .ads-carousel-section .ad-banner h2 {
                font-size: 1.8rem !important;
            }
            
            .ads-carousel-section .ad-banner p {
                font-size: 1rem !important;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @include('partials.header')
    
    <main>
        @yield('content')
    </main>
    
    @include('partials.footer')
    
    <!-- Floating Action Buttons -->
    <div class="floating-buttons">
        @php
            $whatsappNumber = \App\Models\Setting::get('whatsapp_number', '201234567890');
            // تنظيف الرقم من المسافات والأحرف الخاصة
            $whatsappNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);
            $whatsappMessage = urlencode('مرحباً، أريد الاستفسار عن المنتجات');
            $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappMessage}";
        @endphp
        <a href="{{ $whatsappUrl }}" target="_blank" class="floating-btn whatsapp" title="تواصل معنا عبر واتساب">
            <i class="fab fa-whatsapp"></i>
            <span class="tooltip">تواصل معنا</span>
        </a>
        @auth
            @php
                $customer = auth()->user()->customers()->first();
                $isWholesale = $customer && $customer->isWholesale();
            @endphp
            @if($isWholesale)
                <a href="{{ route('orders.wholesale') }}" class="floating-btn wholesale" title="طلبات الجملة">
                    <i class="fas fa-warehouse"></i>
                    <span class="tooltip">طلبات الجملة</span>
                </a>
            @endif
        @endauth
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <script>
        // Update cart count
        function updateCartCount() {
            $.get('{{ route("cart.index") }}', function(data) {
                // This will be handled by server-side rendering
            });
        }
        
        // Add to cart
        $('.btn-add-cart').on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (url) {
                window.location.href = url;
            }
        });

        // Mobile Navigation Toggle
        $(document).ready(function() {
            const mobileNavToggle = $('#mobileNavToggle');
            const mainNav = $('#mainNav');
            const mobileNavOverlay = $('#mobileNavOverlay');
            
            // Toggle mobile menu
            mobileNavToggle.on('click', function(e) {
                e.stopPropagation();
                mainNav.toggleClass('active');
                mobileNavOverlay.toggleClass('active');
                mobileNavToggle.toggleClass('active');
            });
            
            // Close mobile menu when clicking overlay
            mobileNavOverlay.on('click', function() {
                mainNav.removeClass('active');
                mobileNavOverlay.removeClass('active');
                mobileNavToggle.removeClass('active');
            });
            
            // Close mobile menu when clicking on a link
            mainNav.find('.nav-link').on('click', function() {
                mainNav.removeClass('active');
                mobileNavOverlay.removeClass('active');
                mobileNavToggle.removeClass('active');
            });
            
            // Close mobile menu when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.main-nav').length && 
                    !$(e.target).closest(mobileNavToggle).length) {
                    mainNav.removeClass('active');
                    mobileNavOverlay.removeClass('active');
                    mobileNavToggle.removeClass('active');
                }
            });
            
            // Close on escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' || e.keyCode === 27) {
                    mainNav.removeClass('active');
                    mobileNavOverlay.removeClass('active');
                    mobileNavToggle.removeClass('active');
                }
            });
        });

        // Wishlist toggle
        $(document).on('click', '.wishlist-btn', function(e) {
            e.preventDefault();
            const btn = $(this);
            const productSlug = btn.data('product-slug');
            const icon = btn.find('i');

            $.ajax({
                url: '{{ route("wishlist.toggle", ":slug") }}'.replace(':slug', productSlug),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        if (response.isInWishlist) {
                            icon.removeClass('text-muted').addClass('text-danger');
                            btn.attr('title', 'حذف من المفضلة');
                        } else {
                            icon.removeClass('text-danger').addClass('text-muted');
                            btn.attr('title', 'إضافة للمفضلة');
                        }
                        
                        // تحديث عدد المفضلة في الـ header
                        location.reload(); // إعادة تحميل الصفحة لتحديث العدد
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        window.location.href = xhr.responseJSON.redirect;
                    } else {
                        alert('حدث خطأ. يرجى المحاولة مرة أخرى.');
                    }
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>

