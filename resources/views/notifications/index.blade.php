@extends('layouts.app')

@section('title', 'الإشعارات')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 align-items-center" style="background: transparent;">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="fw-bold text-primary">الرئيسية</a>
                </li>
                <li class="mx-1 text-muted">/</li>
                <li class="breadcrumb-item active text-dark fw-bold">الإشعارات</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5">
    <!-- Notifications Header -->
    <div class="notifications-page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="notifications-page-title mb-2">
                    <i class="fas fa-bell text-primary"></i>
                    الإشعارات
                </h2>
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle"></i>
                    إدارة جميع إشعاراتك
                </p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                @php
                    $unreadCount = $notifications->where('is_read', false)->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="badge bg-danger" style="font-size: 14px; padding: 8px 15px;">
                        <i class="fas fa-envelope"></i>
                        {{ $unreadCount }} غير مقروء
                    </span>
                    <form action="{{ route('notifications.readAll') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="fas fa-check-double"></i>
                            تحديد الكل كمقروء
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @if($notifications->isEmpty())
        <div class="empty-notifications">
            <div class="empty-notifications-icon">
                <i class="fas fa-bell-slash"></i>
            </div>
            <h3 class="empty-notifications-title">لا توجد إشعارات</h3>
            <p class="empty-notifications-text">لا توجد إشعارات حالياً</p>
        </div>
    @else
        <div class="notifications-list">
            @foreach($notifications as $notification)
                <div class="notification-card {{ $notification->is_read ? 'read' : 'unread' }}" 
                     data-notification-id="{{ $notification->id }}">
                    <div class="notification-icon-wrapper">
                        @if($notification->type == 'success')
                            <div class="notification-icon bg-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        @elseif($notification->type == 'warning')
                            <div class="notification-icon bg-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                        @elseif($notification->type == 'error')
                            <div class="notification-icon bg-danger">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        @else
                            <div class="notification-icon bg-info">
                                <i class="fas fa-info-circle"></i>
                            </div>
                        @endif
                    </div>
                    <div class="notification-content">
                        <div class="notification-header">
                            <h5 class="notification-title">
                                {{ $notification->title }}
                            </h5>
                            @if(!$notification->is_read)
                                <span class="notification-badge">جديد</span>
                            @endif
                        </div>
                        <p class="notification-message">{{ $notification->message }}</p>
                        <div class="notification-footer">
                            <span class="notification-time">
                                <i class="fas fa-clock"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                            <div class="notification-actions">
                                @if(!$notification->is_read)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-inline notification-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary" title="تحديد كمقروء">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                @if($notification->link)
                                    <a href="{{ $notification->link }}" class="btn btn-sm btn-primary" title="عرض التفاصيل">
                                        <i class="fas fa-external-link-alt"></i>
                                        عرض
                                    </a>
                                @endif
                                <button type="button" 
                                        class="btn btn-sm btn-outline-danger" 
                                        title="حذف"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteNotificationModal"
                                        data-notification-id="{{ $notification->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $notifications->links() }}
            </div>
        @endif
    @endif
</div>

<!-- Delete Notification Modal -->
<div class="modal fade" id="deleteNotificationModal" tabindex="-1" aria-labelledby="deleteNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="delete-notification-icon mb-3">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h5 class="modal-title mb-3" id="deleteNotificationModalLabel">تأكيد الحذف</h5>
                <p class="text-muted mb-4">
                    هل أنت متأكد من حذف هذا الإشعار؟
                </p>
                <form id="deleteNotificationForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> إلغاء
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle delete notification modal
        $('#deleteNotificationModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const notificationId = button.data('notification-id');
            
            const modal = $(this);
            modal.find('#deleteNotificationForm').attr('action', '{{ route("notifications.destroy", ":id") }}'.replace(':id', notificationId));
        });

        // Handle form submission
        $('#deleteNotificationForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const formAction = form.attr('action');
            
            $.ajax({
                url: formAction,
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    $('#deleteNotificationModal').modal('hide');
                    // Reload page to show updated notifications
                    location.reload();
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
    });
</script>
@endpush
@endsection

