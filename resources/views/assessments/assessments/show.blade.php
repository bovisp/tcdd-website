@extends('layouts.app')

@section('content')

    <assessment-take
        :assessment="{{ json_encode($assessment) }}"
    />

@endsection