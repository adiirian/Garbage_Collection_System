@extends('layouts.app')

@section('content')
<div id="collector-management-root"></div>
@endsection

@section('scripts')
<script>
    window.collectors = @json($collectors);
    window.assignments = @json($assignments);
    window.areaNames = @json($areaNames);
    window.routes = {
        adminDashboard: '{{ route("admin.dashboard") }}',
        updateCollector: '{{ url("admin/collectors") }}/{id}',
        applyPenalty: '{{ url("admin/collectors") }}/{id}/penalty',
        getPenalties: '{{ url("admin/collectors") }}/{id}/penalties',
        getAssignments: '{{ route("admin.assignments.index") }}',
        createAssignment: '{{ route("admin.assignments.store") }}',
        updateAssignment: '{{ url("admin/assignments") }}/{id}',
        deleteAssignment: '{{ url("admin/assignments") }}/{id}'
    };
    window.csrfToken = '{{ csrf_token() }}';
</script>
@endsection