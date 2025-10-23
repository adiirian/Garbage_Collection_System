@extends('layouts.app')

@section('content')
<script>
    window.user = @json($user);
    window.isAuthenticated = @json($isAuthenticated);
    window.routes = @json($routes);
</script>
<div id="welcome-root"></div>
@endsection