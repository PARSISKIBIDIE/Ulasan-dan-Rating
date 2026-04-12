<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Ulasan dan Rating</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <style>
        /* Global responsive helpers */
        img, iframe, video { max-width: 100%; height: auto; display: block; }
        .main-content { min-height: calc(100vh - 70px); }
        .table-responsive { overflow-x: auto; }
        table { width: 100%; }

        /* Notification container */
        #notif-container { position: fixed; top: 20px; right: 20px; z-index: 99999; }
        .notif-item { min-width: 260px; margin-bottom: 8px; padding: 12px 16px; border-radius: 8px; color: #fff; box-shadow: 0 6px 18px rgba(0,0,0,0.12); opacity: 0; transform: translateY(-8px); transition: all 220ms ease; font-weight:600; }
        .notif-item.show { opacity: 1; transform: translateY(0); }
        .notif-success { background: #28a745; }
        .notif-error { background: #dc3545; }
        .notif-warning { background: #ff9800; color: #111; }
        .notif-info { background: #007bff; }

        /* Small-screen tweaks */
        @media (max-width: 576px) {
            body { padding-left: 8px; padding-right: 8px; }
            #notif-container { right: 8px; left: 8px; }
            .navbar .text-white.me-3 { display: none; }
            .footer .col-md-4 { flex: 0 0 100%; max-width: 100%; }
            .card-body { padding: 1rem; }
        }

        @media (max-width: 768px) {
            table { display: block; width: 100%; overflow-x: auto; white-space: nowrap; }
            .table-responsive { -webkit-overflow-scrolling: touch; }
        }

    </style>

    <script>
        window.showNotification = function(message, type='success', timeout=3500){
            if (!message) return;
            var container = document.getElementById('notif-container');
            if (!container) { container = document.createElement('div'); container.id = 'notif-container'; document.body.appendChild(container); }
            var el = document.createElement('div');
            var t = (type === 'error' || type === 'danger') ? 'error' : (type === 'warn' || type === 'warning' ? 'warning' : (type === 'info' ? 'info' : 'success'));
            el.className = 'notif-item notif-' + t;
            el.innerText = message;
            container.appendChild(el);
            // animate in
            setTimeout(function(){ el.classList.add('show'); }, 10);
            // remove after timeout
            setTimeout(function(){ el.classList.remove('show'); setTimeout(function(){ try{ container.removeChild(el); }catch(e){} if (container.children.length === 0) { try{ container.remove(); }catch(e){} } }, 240); }, timeout);
        }
    </script>
</head>
<body style="display: flex; flex-direction: column; min-height: 100vh; margin:0; padding:0; background: #fff3e6;">

    @include('layouts.navbar')

    <div style="flex: 1; padding-bottom: 40px;">
        @yield('content')
    </div>

    @if(session('user_id'))
        @include('layouts.footer')
    @endif

<script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>

@if(session()->has('success') || session()->has('error') || session()->has('warning') || session()->has('info'))
<script>
    document.addEventListener('DOMContentLoaded', function(){
        @if(session()->has('success'))
            showNotification({!! json_encode(session('success')) !!}, 'success');
        @endif
        @if(session()->has('error'))
            showNotification({!! json_encode(session('error')) !!}, 'error');
        @endif
        @if(session()->has('warning'))
            showNotification({!! json_encode(session('warning')) !!}, 'warning');
        @endif
        @if(session()->has('info'))
            showNotification({!! json_encode(session('info')) !!}, 'info');
        @endif
    });
</script>
@endif

@stack('scripts')

</body>
</html>