@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/saleStatuses/styles.css') }}">
@endsection

@section('content')
    <div id="saleStatusesContainer">
        <h1 class="display-4">Sale Statuses</h1>

        <a href="/admin/saleStatuses/create">
            <button class="btn btn-primary mt-2">Add Sale Status</button>
        </a>

        <div id="saleStatusTableContainer" class="mt-4">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Label</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Updated At</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>

                @foreach($saleStatuses as $saleStatus)
                    <tr>
                        <th scope="row">{{ $saleStatus ->id }}</th>
                        <td>{{ $saleStatus->name }}</td>
                        <td>
                            @if($saleStatus->image_path != '')
                                <img src="{{URL::to('uploaded_images/sale_statuses/' . (trim($saleStatus->image_path)))}}" class="imagePreview">
                            @else
                                <img src="{{URL::to('uploaded_images/sale_statuses/not-found.png')}}" class="imagePreview">
                            @endif
                        </td>
                        <td>{{ $saleStatus->created_at }}</td>
                        <td>{{ $saleStatus->updated_at }}</td>
                        <td>
                            @if($saleStatus->id != 1)
                                <a href="/admin/saleStatuses/{{ $saleStatus->id }}/edit">
                                    <button class="btn btn-warning">Edit</button>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
