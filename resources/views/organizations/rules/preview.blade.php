@extends('layouts.master')

@section('content')
    <div>
        <a class="btn btn-outline-primary" href="{{ route('rules.show') }}">Zurück zu Regeln</a>
    </div>
    @foreach($sync as $user)
    <div class="card mb-2">
        <div class="card-header">
            <i class="fa-regular fa-user"></i> {{ $user['firstName'] }} {{ $user['lastName'] }} (<i class="fa-solid fa-key"></i> {{ $user['externalDbId'] }})
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-xl-4">
                    <i class="fa-solid fa-envelope" title="E-Mail"></i> {{ $user['email'] }}
                </div>
                <div class="col-sm-12 col-md-6 col-xl-4">
                    <i class="fa-solid fa-mobile-screen-button" title="aPager Pro Adresse"></i> {{ $user['aPagerPro'] }}
                </div>
                <div class="col-sm-12 col-md-6 col-xl-4">
                    <i class="fa-solid fa-phone" title="Telefonnummer"></i> {{ $user['mobil'] }}
                </div>
                <div class="col-sm-12 col-md-6 col-xl-4">
                    <i class="fa-solid fa-walkie-talkie" title="ISSI"></i> {{ $user['issi'] }}
                </div>
                <div class="col-sm-12 col-md-6 col-xl-4">
                    <i class="fa-regular fa-comment" title="XMPP"></i> {{ $user['xmpp'] }}
                </div>
                <div class="col-sm-12 col-md-6 col-xl-4">
                    Notiz: {{ $user['note'] }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-6 col-xl-4">
                    Alarmgruppen: {{ implode(', ', $user['alarmGroups'] ?? []) }}
                </div>
                <div class="col-sm-12 col-md-6 col-xl-4">
                    OS Gruppen: {{ implode(', ', $user['osGroups'] ?? []) }}
                </div>
                <div class="col-sm-12 col-md-6 col-xl-4">
                    OS Gruppen: {{ implode(', ', $user['osFunctions'] ?? []) }}
                </div>
                <div class="col-sm-12 col-md-6 col-xl-4">
                    Provisionierung: {{ $user['provisioning'] }}
                </div>
            </div>
        </div>
    </div>
    @endforeach

@endsection
