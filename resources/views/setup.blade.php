<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Welcome | Auto Motors Setup</title>@vite(['resources/css/app.css','resources/js/app.js'])</head>
<body class="setup-page"><main class="setup-shell">
<aside class="setup-welcome"><img src="{{ asset('images/auto-motors-logo.png') }}" alt="Auto Motors"><div><p class="eyebrow">Auto Motors · First-run setup</p><h1>Secure your admin area.</h1><p>Create the first administrator account. Company and website details are already available and can be managed later from Site Settings.</p></div><div class="setup-security"><i class="bi bi-shield-check"></i><span><strong>Private and secure</strong>Your password is encrypted before it is stored, and this installer locks as soon as setup is complete.</span></div></aside>
<section class="setup-form-panel">
<div class="setup-progress" aria-label="Setup progress"><div class="active"><span>1</span><small>Create account</small></div><div><span>2</span><small>Sign in</small></div></div>
<div class="setup-heading"><p class="eyebrow">Welcome aboard</p><h2>Create administrator</h2><p>Enter the credentials you will use to securely manage this website.</p></div>
@if($errors->any())<div class="alert alert-danger" role="alert"><strong>Please check the highlighted fields.</strong><span>{{ $errors->first() }}</span></div>@endif
<form method="post" action="{{ route('setup.store') }}" novalidate>@csrf
<fieldset><legend><span>01</span> Administrator account</legend><p class="fieldset-help">All fields are required. These credentials provide full access to website management.</p><div class="row g-3">
<div class="col-md-6"><label class="form-label" for="admin_name">Full name</label><input class="form-control @error('admin_name') is-invalid @enderror" id="admin_name" name="admin_name" value="{{ old('admin_name') }}" autocomplete="name" maxlength="120" required autofocus>@error('admin_name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-6"><label class="form-label" for="admin_email">Email address</label><input class="form-control @error('admin_email') is-invalid @enderror" id="admin_email" type="email" name="admin_email" value="{{ old('admin_email') }}" autocomplete="username" maxlength="255" required>@error('admin_email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-6"><label class="form-label" for="password">Password</label><input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" autocomplete="new-password" minlength="12" required><div class="form-text">12+ characters with uppercase, lowercase, a number, and a symbol.</div>@error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
<div class="col-md-6"><label class="form-label" for="password_confirmation">Confirm password</label><input class="form-control" id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" minlength="12" required></div>
</div></fieldset>
<div class="setup-submit"><span><i class="bi bi-lock"></i> Secure one-time setup</span><button class="btn btn-brand" type="submit">Complete setup <i class="bi bi-arrow-right"></i></button></div>
</form></section></main></body></html>
