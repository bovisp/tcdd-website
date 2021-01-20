@extends('layouts.app')

@section('content')

    <assessment-show
        :attempt="{{ json_encode($attempt) }}"
    />

@endsection