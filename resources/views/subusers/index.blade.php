@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Subusers</h1>

    <a href="{{ route('subusers.create') }}" class="btn btn-primary mb-3">Create New Subuser</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subusers as $subuser)
                <tr>
                    <td>{{ $subuser->id }}</td>
                    <td>{{ $subuser->name }}</td>
                    <td>{{ $subuser->email }}</td>
                    <td>{{ $subuser->role?->name ?? '—' }}</td>
                    <td>
                        <a href="{{ route('subusers.show', $subuser->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('subusers.edit', $subuser->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('subusers.destroy', $subuser->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this subuser?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection