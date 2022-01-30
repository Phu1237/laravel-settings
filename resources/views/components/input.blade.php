<input @foreach ($input_attributes as $key=> $value)
{{ $key }}="{{ $value }}"
@endforeach
>
