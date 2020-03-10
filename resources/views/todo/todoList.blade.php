@extends('layouts.layout')

@section('title', 'todo')

@section('body-class', 'todo')

@section('content')
    <div class="container mt-32 ">
        <div id="app">
            <todo-list></todo-list>

        </div>
    </div>
@endsection

@section('extra-js')
    <script src="{{ asset('js/app.js') }}" ></script>
@endsection


