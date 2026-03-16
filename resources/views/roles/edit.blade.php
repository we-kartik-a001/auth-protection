@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Edit Role</h1>

    <form action="{{ route('roles.update',$role->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Role Name --}}
        <div class="mb-3">
            <label>Name</label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ $role->name }}"
                required>

        </div>


        {{-- Description --}}
        <div class="mb-3">

            <label>Description</label>

            <textarea
                name="description"
                class="form-control">{{ $role->description }}</textarea>

        </div>

        {{-- Table Dropdown --}}
        <div class="mb-3">

            <label>Select Table</label>

            <select name="table_name" class="form-control">

                <option value="">Select Table</option>

                @foreach($tableNames as $table)

                <option value="{{ $table }}"
                    {{ $table == $tableName ? 'selected' : '' }}>

                    {{ $table }}

                </option>

                @endforeach

            </select>


        </div>


        {{-- Permissions --}}
        <div class="mb-3">
            <label>Permissions</label>

            <div class="row">
                @foreach($permissions as $permission)

                <div class="col-md-3">
                    <div class="form-check">

                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="permissions[]"
                            value="{{ $permission->id }}"
                            id="perm{{ $permission->id }}"
                            {{ in_array($permission->id, $rolePermissions ?? []) ? 'checked' : '' }}>

                        <label
                            class="form-check-label"
                            for="perm{{ $permission->id }}">
                            {{ $permission->name }}
                        </label>

                    </div>
                </div>

                @endforeach
            </div>
        </div>


        <button type="submit" class="btn btn-primary">
            Update Role
        </button>

        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
            Back
        </a>

    </form>

</div>
@endsection