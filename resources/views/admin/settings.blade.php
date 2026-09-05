@extends('layouts.admin')
@section('title', 'Site & company settings')
@section('content')
<form method="post" enctype="multipart/form-data" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')
    @foreach($groups as $group => $settings)
        @php
            $localized = $settings->filter(fn ($setting) => in_array($setting->type, ['localized_text', 'localized_textarea'], true));
            $shared = $settings->reject(fn ($setting) => in_array($setting->type, ['localized_text', 'localized_textarea'], true));
        @endphp
        <section class="admin-panel mb-4 settings-group">
            <h2 class="settings-group-title">{{ str($group)->title() }}</h2>
            @if($localized->isNotEmpty())
                <div class="settings-language-grid">
                    @foreach(['fr' => ['FR', 'French (Default)'], 'en' => ['EN', 'English Translation']] as $locale => [$code, $language])
                        <div class="language-section {{ $locale === 'fr' ? 'language-primary' : 'language-secondary' }}">
                            <div class="language-heading"><span class="language-badge">{{ $code }}</span><div><h3>{{ $language }}</h3></div></div>
                            <div class="row g-4">
                                @foreach($localized as $setting)
                                    @php $field = $setting->key.'_'.$locale; @endphp
                                    <div class="col-12"><label class="form-label" for="{{ $field }}">{{ $setting->label }}</label>
                                        @if($setting->type === 'localized_textarea')
                                            <textarea class="form-control @error($field) is-invalid @enderror" rows="4" id="{{ $field }}" name="{{ $field }}" required>{{ old($field, $setting->{'value_'.$locale}) }}</textarea>
                                        @else
                                            <input class="form-control @error($field) is-invalid @enderror" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $setting->{'value_'.$locale}) }}" required>
                                        @endif
                                        @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            @if($shared->isNotEmpty())
                <div class="shared-fields {{ $localized->isNotEmpty() ? 'with-divider' : '' }}"><div class="row g-4">
                    @foreach($shared as $setting)
                        <div class="col-lg-6"><label class="form-label" for="{{ $setting->key }}">{{ $setting->label }}</label>
                            @if($setting->type === 'image')
                                <input class="form-control @error($setting->key) is-invalid @enderror" type="file" id="{{ $setting->key }}" name="{{ $setting->key }}" accept="image/jpeg,image/png,image/webp">@if($setting->value)<img class="image-preview" src="{{ str_starts_with($setting->value, 'uploads/') ? asset('storage/'.$setting->value) : asset($setting->value) }}" alt="Current {{ $setting->label }}">@endif
                            @elseif($setting->type === 'textarea')
                                <textarea class="form-control @error($setting->key) is-invalid @enderror" rows="4" id="{{ $setting->key }}" name="{{ $setting->key }}">{{ old($setting->key, $setting->value) }}</textarea>
                            @else
                                <input class="form-control @error($setting->key) is-invalid @enderror" type="{{ $setting->type === 'email' ? 'email' : 'text' }}" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ old($setting->key, $setting->value) }}">
                            @endif
                            @error($setting->key)<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    @endforeach
                </div></div>
            @endif
        </section>
    @endforeach
    <div class="sticky-save"><button class="btn btn-brand btn-lg">Save all settings</button></div>
</form>
@endsection
