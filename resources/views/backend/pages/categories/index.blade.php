@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/categories/styles.css') }}">
@endsection

@section('content')
    <div id="categoriesContainer">
        <h1 class="display-4">Categories</h1>

        <a href="/admin/categories/create">
            <button class="btn btn-primary mt-2">Add Category</button>
        </a>

        <div id="categoriesTableContainer" class="mt-4">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">SubCategory Of</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Updated At</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>

                @foreach($categories as $category)
                    <tr>
                        <th scope="row">{{ $category->id }}</th>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->parent->name }}</td>
                        <td>{{ $category->created_at }}</td>
                        <td>{{ $category->updated_at }}</td>
                        <td>
                            <a href="/admin/categories/{{ $category->id }}/edit">
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
