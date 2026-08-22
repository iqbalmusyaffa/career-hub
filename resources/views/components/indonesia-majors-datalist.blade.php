@php
    $majorsJson = file_exists(resource_path('json/indonesia_majors.json')) 
        ? json_decode(file_get_contents(resource_path('json/indonesia_majors.json')), true) 
        : null;
@endphp

<datalist id="indonesia-majors-list">
    @if($majorsJson && isset($majorsJson['data']))
        @foreach($majorsJson['data'] as $cat)
            @foreach($cat['majors'] as $mj)
                <option value="{{ $mj }}">{{ $cat['category'] }}</option>
            @endforeach
        @endforeach
    @endif
</datalist>
