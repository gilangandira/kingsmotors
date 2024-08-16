@extends('layouts.main')

<head>
    <link rel="stylesheet" href="/style/home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

@section('container')

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">User List</div>
            <div class="card-body">
                <div class="p-2 g-col-6"><a href="{{ route('account.create') }}" class="btn btn-success">
                        Add User
                    </a></div>
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table vertical-align">
                        <thead>
                            <tr class="align-middle">
                                <th class="align-middle text-center" scope="col">#</th>
                                <th class="align-middle text-center" scope="col">Nama</th>
                                <th class="align-middle text-center" scope="col">Email</th>
                                <th class="align-middle text-center" scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="align-middle" scope="row">
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $user->name }}</td>
                                    <td class="align-middle">{{ $user->email }}</td>
                                    <!-- Edit Button -->
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#editUserModal{{ $user->id }}">
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                            data-target="#deleteUserModal{{ $user->id }}">
                                            Delete
                                        </button>
                                    </td>

                                </tr>
                                <!-- Edit User Modal -->
                                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editUserModalLabel{{ $user->id }}">Edit
                                                    User</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('account.update', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="userName{{ $user->id }}">User Name</label>
                                                        <input type="text" class="form-control" id="userName{{ $user->id }}"
                                                            name="name" value="{{ $user->name }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="userEmail{{ $user->id }}">Email</label>
                                                        <input type="email" class="form-control"
                                                            id="userEmail{{ $user->id }}" name="email"
                                                            value="{{ $user->email }}" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="userPassword{{ $user->id }}">Password Baru</label>
                                                        <input type="password" class="form-control"
                                                            id="userPassword{{ $user->id }}" name="password"
                                                            placeholder="Enter new password">
                                                        <small>Kosongkan jika tidak ingin mengubah password.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete User Modal -->
                                <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="deleteUserModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteUserModalLabel{{ $user->id }}">
                                                    Delete User</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="{{ route('account.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this User? All related items will
                                                        be reassigned to the selected User.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection