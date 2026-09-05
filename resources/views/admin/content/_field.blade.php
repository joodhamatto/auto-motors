@php
    $label = str($field)->replaceEnd('_fr', '')->replaceEnd('_en', '')->replace('_', ' ')->title();
    $current = old($field, $item->{$field} ?? ($field === 'display_order' ? 0 : null));
    $width = $columnClass ?? (in_array($type, ['textarea', 'gallery', 'checkbox'], true) ? 'col-12' : 'col-md-6');
@endphp
@if($type === 'textarea')
    <div class="{{ $width }}"><label class="form-label" for="{{ $field }}">{{ $label }}</label><textarea class="form-control @error($field) is-invalid @enderror" id="{{ $field }}" name="{{ $field }}" rows="5">{{ $current }}</textarea>@error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
@elseif($type === 'checkbox')
    <div class="{{ $width }}"><div class="form-check form-switch"><input type="hidden" name="{{ $field }}" value="0"><input class="form-check-input" id="{{ $field }}" type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $item->{$field} ?? true))><label class="form-check-label" for="{{ $field }}">Active on public website</label></div></div>
@elseif($type === 'product_category' || $type === 'vehicle_category')
    @php $options = $type === 'product_category' ? $productCategories : $vehicleCategories; @endphp
    <div class="{{ $width }}"><label class="form-label" for="{{ $field }}">{{ $label }}</label><select class="form-select @error($field) is-invalid @enderror" id="{{ $field }}" name="{{ $field }}" required><option value="">Select…</option>@foreach($options as $option)<option value="{{ $option->id }}" @selected((string) $current === (string) $option->id)>{{ $option->name_fr }} / {{ $option->name_en }}</option>@endforeach</select>@error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
@elseif($type === 'image')
    <div class="{{ $width }}"><label class="form-label" for="{{ $field }}">{{ $label }} <small>(JPEG, PNG, WebP · max 4 MB)</small></label><input class="form-control @error($field) is-invalid @enderror" id="{{ $field }}" type="file" name="{{ $field }}" accept="image/jpeg,image/png,image/webp">@error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror @if($current)<img class="image-preview" src="{{ str_starts_with($current, 'uploads/') ? asset('storage/'.$current) : asset($current) }}" alt="Current image">@endif</div>
@elseif($type === 'gallery')
    <div class="{{ $width }}"><label class="form-label" for="gallery">Additional product images <small>(up to 8)</small></label><input class="form-control @error('gallery') is-invalid @enderror" id="gallery" type="file" name="gallery[]" multiple accept="image/jpeg,image/png,image/webp">@error('gallery')<div class="invalid-feedback">{{ $message }}</div>@enderror @if($item && $item->images->count())<div class="gallery-admin">@foreach($item->images as $image)<div><img src="{{ asset('storage/'.$image->path) }}" alt="Product image"><button form="delete-image-{{ $image->id }}" class="btn btn-sm btn-danger">Delete</button></div>@endforeach</div>@endif</div>
@else
    <div class="{{ $width }}"><label class="form-label" for="{{ $field }}">{{ $label }}</label><input class="form-control @error($field) is-invalid @enderror" id="{{ $field }}" type="{{ $type }}" name="{{ $field }}" value="{{ $current }}" @if($type === 'number') step="{{ $field === 'price' ? '0.01' : '1' }}" @endif>@error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
@endif
