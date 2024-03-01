@extends('layouts.master')

@section('content')
    <div>
        <a class="btn btn-outline-primary" href="{{ route('rules.show') }}">Zurück zu Regeln</a>
    </div>

    <table class="table table-striped">
        <tr>
            @foreach($fe2Fields as $field)
                <th>{{ $field->name }}</th>
            @endforeach
        </tr>
        @foreach($sync as $user)
            <tr>
                @foreach($fe2Fields as $field)
                    <td>
                    @if(is_array($user[$field->key] ?? ''))
                            {{ implode(', ', $user[$field->key]) }}
                        @else
                            {{ $user[$field->key] ?? '' }}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
@endsection
