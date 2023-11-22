@props([
    'id',
    'name',
    'type' => 'text',
    'value' => '',
    'class' => ''
])

<label for="{{$id}}">{{$slot}}</label>
<input type="{{$type}}" id="{{$id}}" name="{{$name}}" value="{{$value}}" {{ $attributes->merge(['class' => 'form-control']) }}>
