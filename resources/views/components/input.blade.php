<input name="{{ $key }}"
       id="{{ $key }}"
       type="{{ !empty($meta->type) ? $meta->type : 'text' }}"
       class="{{ $class }}"
       {!! !empty($meta->placeholder) ? 'placeholder="'.$meta->placeholder.'"' : '' !!}
       value="{{ $value ?? '' }}"
    {{ isset($meta->required) && $meta->required === true ? 'required' : '' }}
    {{ isset($locked) && $locked === true ? 'readonly' : '' }}
>
