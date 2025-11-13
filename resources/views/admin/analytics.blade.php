@extends('layouts.app')

@section('content')
<div id="analytics-dashboard-root"></div>
@endsection

@section('scripts')
<script>
    window.binSummary = @json($binSummary);
    window.alertStats = @json($alertStats);
    window.collectionEfficiency = @json($collectionEfficiency);
    window.binCollectionRates = @json($binCollectionRates);
    window.routes = {
        adminDashboard: '{{ route("admin.dashboard") }}'
    };
</script>
@endsection