@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/users/styles.css') }}">
@endsection

@section('content')
    <div id="usersContainer">
        <h1 class="display-4">User</h1>

        <a href="/admin/users/create">
            <button class="btn btn-primary mt-2">Add User</button>
        </a>

        <div id="usersTableContainer" class="mt-4">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Updated At</th>
                    <th scope="col">Last Login</th>
                    <th scope="col">Activate</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($users as $user)
                    <tr>
                        <th scope="row">{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>{{ $user->updated_at }}</td>
                        <td>{{ $user->last_login }}</td>
                        <td>
                            <div class="form-check text-center">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="checkboxActivated"
                                       id="checkboxActivated{{ $user->id }}"
                                       onchange="updateUserPublished({{ $user->id }})"
                                    {{ $user->activate == true ? 'checked' : '' }}
                                />
                            </div>
                        </td>
                        <td>
                            <a href="/admin/users/{{ $user->id }}/edit">
                                <button class="btn btn-warning">Edit</button>
                            </a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('link_js')
    <script>
        function updateUserPublished(userId) {
            let result = document.querySelector('#checkboxActivated' + userId).checked;
            result = result === true ? 1 : 0;
            let xhr = new XMLHttpRequest();
            let formData = new FormData();

            formData.append("activate", result);

            xhr.onload = (format, data) => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    const response = JSON.parse(xhr.responseText);
                }
                else {
                    const response = JSON.parse(xhr.responseText);
                    alert(response.message);
                }
            };

            xhr.open("POST", "/admin/users/updateUserActivate/" + userId, true);
            xhr.setRequestHeader('x-csrf-token', '{{csrf_token()}}');
            xhr.send(formData);
        }
    </script>
@endsection
