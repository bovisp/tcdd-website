@extends('layouts.app')

@section('content')

    <assessment-index
        :assessment="{{ json_encode($assessment) }}"
        :pages="{{ json_encode($pages) }}"
    />

@endsection