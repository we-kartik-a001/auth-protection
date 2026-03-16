@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Role</h1>

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        {{-- Table Dropdown --}}
        <div class="mb-3">
            <label>Select Table</label>
            <select name="table_name" class="form-select" required>
                <option value="">Select Table</option>
                @foreach($tableNames as $table)
                    <option value="{{ $table }}">{{ $table }}</option>
                @endforeach
            </select>
        </div>

        {{-- CRUD Operations --}}
        <div class="mb-3">

            <label>Permissions</label>

            @foreach($permissions as $permission)

            <div class="form-check">

                <input
                type="checkbox"
                name="permissions[]"
                value="{{ $permission->id }}"
                class="form-check-input">

                <label class="form-check-label">
                {{ $permission->name }}
                </label>

                </div>

            @endforeach

        </div>


        <button type="submit" class="btn btn-primary">Create Role</button>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">Back</a>

    </form>
</div>
@endsection
