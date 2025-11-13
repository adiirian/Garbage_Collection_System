@extends('layouts.app')

@section('content')
<div id="admin-dashboard-root"></div>
@endsection

@section('scripts')
<script>
    window.bins = @json($bins);
    window.openAlerts = @json($openAlerts);
    window.binSummary = @json($binSummary);
    window.alertStats = @json($alertStats);
    window.todayCollections = @json($todayCollections);
    window.routes = {
        adminAnalytics: '{{ route("admin.analytics") }}',
        adminCollectorManagement: '{{ route("admin.collector-management") }}',
        logout: '{{ route("logout") }}'
    };
    window.csrfToken = '{{ csrf_token() }}';
</script>
@endsection