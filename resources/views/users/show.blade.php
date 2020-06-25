@extends('layouts.app')

@section('content')

    <profile
        :user-id="{{ $user->id }}"
    />

@endsection