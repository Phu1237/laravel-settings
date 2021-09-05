<input name="{{ $key }}" id="{{ $key }}" type="{{ $meta->type ?? 'text' }}" class="{{ $class }}" {{ $this->style != null ? 'style="'.$style.'"' : ''}} {!! !empty($meta->placeholder) ? 'placeholder="'.$meta->placeholder.'"' : '' !!}
value="{{ $value ?? '' }}"
{{ isset($meta->required) && $meta->required === true ? 'required' : '' }}
{{ $locked === true ? 'readonly' : '' }}
>
