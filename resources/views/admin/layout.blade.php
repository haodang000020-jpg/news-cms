<!DOCTYPE html>
<html>
<head>
    <title>News CMS Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2>News CMS Admin</h2>

    <hr>
@if(session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif
    @yield('content')

</div>

</body>
</html>