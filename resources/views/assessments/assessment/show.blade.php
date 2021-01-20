@extends('layouts.app')

@section('content')

    <assessment-show
        :assessment-attempt="{{ json_encode($attempt) }}"
        :assessment="{{ json_encode($assessment) }}"
    />

@endsection