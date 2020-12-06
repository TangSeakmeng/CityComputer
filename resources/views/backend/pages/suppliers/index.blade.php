@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/suppliers/styles.css') }}">
@endsection

@section('content')
    <div id="suppliersContainer">
        <h1 class="display-4">Supplier</h1>

        <a href="/admin/suppliers/create">
            <button class="btn btn-primary mt-2">Add Supplier</button>
        </a>

        <div id="suppliersTableContainer" class="mt-4">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Address</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Email</th>
                    <th scope="col" width="300px">Note</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Updated At</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>

                @foreach($suppliers as $supplier)
                    <tr>
                        <th scope="row">{{ $supplier->id }}</th>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->address }}</td>
                        <td>{{ $supplier->contact }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->note }}</td>
                        <td>{{ $supplier->created_at }}</td>
                        <td>{{ $supplier->updated_at }}</td>
                        <td>
                            <a href="/admin/suppliers/{{ $supplier->id }}/edit">
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
