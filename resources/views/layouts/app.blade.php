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

        /* Theme: Deep Indigo & Soft Blue */
        :root{ --color-indigo: #4F46E5; --color-indigo-dark: #3b3fbd; --page-bg: #F8F9FA; --color-soft-blue: #60A5FA; --card-filter-bg: #F3F4F6; }
        .bg-indigo { background-color: var(--color-indigo) !important; color: #ffffff !important; }
        .btn-indigo { background-color: var(--color-indigo); color: #ffffff; border: none; transition: background-color .12s ease-in-out, transform .06s ease; }
        .btn-indigo:hover, .btn-indigo:focus { background-color: var(--color-indigo-dark); color: #ffffff; }
        .text-indigo { color: var(--color-indigo) !important; }
        .text-soft-blue { color: var(--color-soft-blue) !important; }
        .card-rounded { border-radius: 1rem; }
        /* .card-accent-top removed: top accent bar disabled */
        .bg-soft-muted { background-color: var(--card-filter-bg) !important; }
        .btn-soft { background: var(--color-soft-blue); color: #fff; border: none; }
        .btn-soft:hover, .btn-soft:focus { background: #4fa9ff; color: #fff; }
        .btn-rounded { border-radius: .5rem !important; }
        .suggestion-btn { border-radius: 20px; padding: .25rem .6rem; border-color: var(--color-indigo); color: var(--color-indigo); background: #fff; }
        .suggestion-btn:hover { background: #eef2ff; color: var(--color-indigo-dark); }
        .star-rating label.active, .star-rating label.hover { color: var(--color-indigo); transform: translateY(-4px); }
        @media (max-width: 576px) { .card-rounded { border-radius: .6rem; } }

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
<body style="display: flex; flex-direction: column; min-height: 100vh; margin:0; padding:0; background: #F8F9FA;">

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

<script>
document.addEventListener('DOMContentLoaded', function(){
    function formatWIBFromTs(ts){
        if(!ts) return '';
        var seconds = parseInt(String(ts).trim(), 10);
        if (isNaN(seconds)) return '';
        var ms = seconds * 1000;
        // Add 7 hours to get WIB, then read UTC components to avoid client TZ influence
        var d = new Date(ms + (7 * 60 * 60 * 1000));
        var day = d.getUTCDate();
        var monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        var month = monthNames[d.getUTCMonth()];
        var year = d.getUTCFullYear();
        var hours = String(d.getUTCHours()).padStart(2,'0');
        var mins = String(d.getUTCMinutes()).padStart(2,'0');
        return day + ' ' + month + ' ' + year + ' ' + hours + ':' + mins + ' WIB';
    }

    function resolveToEpochSeconds(raw){
        if(!raw) return null;
        raw = String(raw).trim();
        // If numeric (10+ digits) assume epoch seconds
        if (/^\d{10,}$/.test(raw)) return parseInt(raw,10);
        // Try ISO parse
        var parsed = Date.parse(raw);
        if (!isNaN(parsed)) return Math.floor(parsed/1000);
        return null;
    }

    function updateReplyTimes(){
        document.querySelectorAll('time.reply-time').forEach(function(el){
            var ts = el.dataset.ts || el.getAttribute('datetime') || el.textContent || '';
            var secs = resolveToEpochSeconds(ts);
            if (!secs) return;
            var txt = formatWIBFromTs(secs);
            if (txt) el.textContent = txt;
        });
    }

    updateReplyTimes();
    // keep minutes up-to-date
    setInterval(updateReplyTimes, 30000);
});
</script>

</body>
</html>