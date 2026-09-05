@extends('layouts.admin')
@section('title', ($item ? 'Edit ' : 'Add ').rtrim($title, 's'))
@section('content')
@php
    $frenchFields = collect($fields)->filter(fn ($type, $field) => str_ends_with($field, '_fr'));
    $englishFields = collect($fields)->filter(fn ($type, $field) => str_ends_with($field, '_en'));
    $sharedFields = collect($fields)->reject(fn ($type, $field) => str_ends_with($field, '_fr') || str_ends_with($field, '_en'));
@endphp
<form class="admin-panel" method="post" enctype="multipart/form-data" action="{{ $item ? route('admin.content.update', [$resource, $item->id]) : route('admin.content.store', $resource) }}">
    @csrf
    @if($item) @method('PUT') @endif
    @if($frenchFields->isNotEmpty())
        <div class="language-section language-primary">
            <div class="language-heading"><span class="language-badge">FR</span><div><h2>French (Default)</h2></div></div>
            <div class="row g-4">@foreach($frenchFields as $field => $type) @include('admin.content._field', ['columnClass' => 'col-12']) @endforeach</div>
        </div>
        <div class="language-section language-secondary">
            <div class="language-heading"><span class="language-badge">EN</span><div><h2>English Translation</h2></div></div>
            <div class="row g-4">@foreach($englishFields as $field => $type) @include('admin.content._field', ['columnClass' => 'col-12']) @endforeach</div>
        </div>
    @endif
    @if($sharedFields->isNotEmpty())
        <div class="shared-fields {{ $frenchFields->isNotEmpty() ? 'with-divider' : '' }}">
            @if($frenchFields->isNotEmpty())<h2>General details</h2>@endif
            <div class="row g-4">@foreach($sharedFields as $field => $type) @include('admin.content._field', ['columnClass' => null]) @endforeach</div>
        </div>
    @endif
    <div class="form-actions"><a class="btn btn-light" href="{{ route('admin.content.index', $resource) }}">Cancel</a><button class="btn btn-brand" type="submit">Save changes</button></div>
</form>
@if($item && method_exists($item, 'images'))
    @foreach($item->images as $image)<form id="delete-image-{{ $image->id }}" method="post" action="{{ route('admin.product-images.destroy', [$item, $image]) }}" data-confirm="Delete this image?">@csrf @method('DELETE')</form>@endforeach
@endif
@endsection
