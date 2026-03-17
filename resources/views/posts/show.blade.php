@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $post->title }}</h1>

    <div class="card">
        <div class="card-body">
            <p class="card-text">{{ $post->content }}</p>
            <p class="text-muted">By: {{ $post->user->name }} on {{ $post->created_at->format('M d, Y') }}</p>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back</a>
            @can('update', $post)
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning">Edit</a>
            @endcan
        </div>
    </div>
</div>
@endsection