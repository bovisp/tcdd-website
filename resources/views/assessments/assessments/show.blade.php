@extends('layouts.app')

@section('content')

    <assessment-home
        :assessment="{{ json_encode($assessment) }}"
    />

@endsection