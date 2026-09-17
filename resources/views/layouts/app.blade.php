<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'E-Learning')</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; background:#f4f6f8; margin:0; color:#222; }
        nav { background:#1f2937; padding:14px 24px; display:flex; gap:20px; align-items:center; }
        nav a { color:#fff; text-decoration:none; font-size:14px; }
        nav a:hover { text-decoration:underline; }
        nav span.brand { color:#fff; font-weight:bold; margin-right:20px; }
        .container { max-width:900px; margin:30px auto; background:#fff; padding:24px 28px; border-radius:8px; box-shadow:0 1px 4px rgba(0,0,0,.08); }
        h1 { font-size:22px; margin-top:0; }
        table { width:100%; border-collapse:collapse; margin-top:12px; }
        th, td { border:1px solid #e2e2e2; padding:8px 10px; font-size:14px; text-align:left; vertical-align:top; }
        th { background:#f0f2f5; }
        .btn { display:inline-block; padding:6px 12px; border-radius:4px; text-decoration:none; font-size:13px; border:none; cursor:pointer; }
        .btn-primary { background:#2563eb; color:#fff; }
        .btn-success { background:#16a34a; color:#fff; }
        .btn-danger { background:#dc2626; color:#fff; }
        .btn-secondary { background:#6b7280; color:#fff; }
        form.inline { display:inline; }
        .alert { padding:10px 14px; border-radius:4px; margin-bottom:14px; font-size:14px; }
        .alert-success { background:#dcfce7; color:#166534; }
        .field { margin-bottom:14px; }
        .field label { display:block; margin-bottom:4px; font-size:13px; font-weight:bold; }
        .field input[type=text], .field textarea, .field select, .field input[type=file] {
            width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; font-size:14px;
        }
        .soal-card { border:1px solid #ddd; border-radius:6px; padding:14px; margin-bottom:14px; background:#fafafa; position:relative; }
        .soal-card h4 { margin-top:0; }
        .btn-remove { position:absolute; top:10px; right:10px; }
        .badge { display:inline-block; padding:2px 8px; border-radius:10px; font-size:12px; }
        .badge-pretest { background:#dbeafe; color:#1e40af; }
        .badge-posttest { background:#fef3c7; color:#92400e; }
    </style>
</head>
<body>
    <nav>
        <span class="brand">📚 E-Learning (Testing DB)</span>
        <a href="{{ route('admin.materi.index') }}">Admin: Materi</a>
        <a href="{{ route('admin.soal.index') }}">Admin: Bank Soal</a>
        <a href="{{ route('siswa.materi.index') }}">Siswa: Daftar Materi</a>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </div>
</body>
</html>
