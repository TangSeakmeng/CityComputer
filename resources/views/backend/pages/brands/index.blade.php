@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/brands/styles.css') }}">
@endsection

@section('content')
    <div id="brandsContainer">
        <h1 class="display-4">Brands</h1>

        <a href="/admin/brands/create">
            <button class="btn btn-primary mt-2">Add Brand</button>
        </a>

        <div id="brandTableContainer" class="mt-4">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Image</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Updated At</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>

                @foreach($brands as $brand)
                    <tr>
                        <th scope="row">{{ $brand->id }}</th>
                        <td>{{ $brand->name }}</td>
                        <td><img src="{{URL::to('uploaded_images/brands/' . (trim($brand->imagePath)))}}" class="imagePreview"></td>
                        <td>{{ $brand->created_at }}</td>
                        <td>{{ $brand->updated_at }}</td>
                        <td>
                            <a href="/admin/brands/{{ $brand->id }}/edit">
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
