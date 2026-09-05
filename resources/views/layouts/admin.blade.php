<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow">
    <title>@yield('title') | AUTO MOTORS CMS</title>
    <script>try{if(localStorage.getItem('autoMotors.adminSidebarCollapsed')==='true')document.documentElement.classList.add('admin-sidebar-collapsed')}catch(e){}</script>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="admin-body">
<nav class="admin-mobile navbar navbar-light" aria-label="Admin navigation"><a class="navbar-brand" href="{{ route('admin.dashboard') }}"><img src="{{ asset('images/auto-motors-logo-transparent.png') }}" alt="AUTO MOTORS CMS" width="114" height="52"></a><button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-expanded="false" aria-label="Open navigation"><span class="navbar-toggler-icon"></span></button></nav>
<aside class="admin-sidebar offcanvas-xl offcanvas-start" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
    <div class="offcanvas-header"><strong id="adminSidebarLabel">Navigation</strong><button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar" aria-label="Close navigation"></button></div>
    <div class="sidebar-brand">
        <a class="sidebar-logo" href="{{ route('admin.dashboard') }}" title="Auto Motors dashboard" aria-label="Auto Motors dashboard"><img class="sidebar-full-logo" src="{{ asset('images/auto-motors-logo-transparent.png') }}" alt="AUTO MOTORS SARL"><img class="sidebar-compact-mark" src="{{ asset('images/auto-motors-mark-transparent.png') }}" alt="" aria-hidden="true"></a>
        <span class="sidebar-brand-label">Content manager</span>
        <button class="sidebar-collapse" id="sidebarCollapse" type="button" aria-label="Collapse sidebar" aria-pressed="false" title="Collapse sidebar"><i class="bi bi-chevron-left" aria-hidden="true"></i></button>
    </div>
    <nav class="sidebar-nav" aria-label="Administration">
        <span class="sidebar-section-label">Workspace</span>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard')?'active':'' }}" title="Dashboard" aria-label="Dashboard" data-sidebar-tooltip @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif><i class="bi bi-grid" aria-hidden="true"></i><span class="sidebar-link-label">Dashboard</span></a>
        <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings*')?'active':'' }}" title="Site settings" aria-label="Site settings" data-sidebar-tooltip @if(request()->routeIs('admin.settings*')) aria-current="page" @endif><i class="bi bi-sliders" aria-hidden="true"></i><span class="sidebar-link-label">Site settings</span></a>
        <span class="sidebar-section-label">Content</span>
        @foreach(['services'=>['bi-tools','Services'],'categories'=>['bi-tags','Product categories'],'products'=>['bi-box-seam','Products'],'vehicle-categories'=>['bi-diagram-3','Vehicle categories'],'vehicles'=>['bi-truck','Vehicles'],'advantages'=>['bi-patch-check','Advantages'],'faqs'=>['bi-question-circle','FAQs']] as $key=>[$icon,$label])
            <a href="{{ route('admin.content.index',$key) }}" class="{{ request('resource')===$key?'active':'' }}" title="{{ $label }}" aria-label="{{ $label }}" data-sidebar-tooltip @if(request('resource')===$key) aria-current="page" @endif><i class="bi {{ $icon }}" aria-hidden="true"></i><span class="sidebar-link-label">{{ $label }}</span></a>
        @endforeach
    </nav>
    <div class="sidebar-footer">
        <a href="{{ route('home') }}" target="_blank" rel="noopener" title="View website" aria-label="View website" data-sidebar-tooltip><i class="bi bi-box-arrow-up-right" aria-hidden="true"></i><span class="sidebar-link-label">View website</span></a>
        <form method="post" action="{{ route('logout') }}">@csrf<button type="submit" title="Sign out" aria-label="Sign out" data-sidebar-tooltip><i class="bi bi-box-arrow-left" aria-hidden="true"></i><span class="sidebar-link-label">Sign out</span></button></form>
    </div>
</aside>
<main class="admin-main"><div class="admin-top"><h1>@yield('title')</h1><span class="admin-user"><i class="bi bi-person-circle"></i> {{ auth()->user()->name }}</span></div>
@if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div>@endif
@if($errors->any())<div class="alert alert-danger"><strong>Please correct the highlighted fields.</strong><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@yield('content')</main>
</body></html>
