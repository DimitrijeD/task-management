@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div id="app">
            <task-management
                :user="{{ auth()->user() }}"
            ></task-management>
        </div>
    </div>
</div>
@endsection
