@extends('layouts.app')

@section('content')
<div id="residents-dashboard-root"></div>
@endsection

@section('scripts')
<script>
    window.bins = @json($bins);
    window.userAlerts = @json($userAlerts);
    window.routes = {
        logout: '{{ route("logout") }}'
    };
    window.csrfToken = '{{ csrf_token() }}';
</script>
@endsection