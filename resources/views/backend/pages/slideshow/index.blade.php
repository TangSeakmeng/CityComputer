@extends('backend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ url('backend/css/pages/settings/slideshow_style.css') }}">
@endsection

@section('content')
    <div id="slideShowContainer">
        <h1 class="display-4">Slideshow</h1>

        @if ($notification = Session::get('success'))
            <div class="alert alert-success alert-block mt-4">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $notification }}</strong>
            </div>
        @endif

        @if ($notification = Session::get('error'))
            <div class="alert alert-danger alert-block mt-4">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $notification }}</strong>
            </div>
        @endif

        <div class="mt-4">
            <div>
                <form action="/admin/slideshow/" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="slideShowImage">Upload Image</label>
                        <input type="file" class="form-control-file" id="slideShowImage" name="slideShowImage" required>
                    </div>

                    <button class="btn btn-primary mt-4" type="submit">Upload Now</button>
                </form>
            </div>

            <div class="mt-4 p-3" style="background-color: silver; border-radius: 20px;">
                <label style="color: white">Upload Images</label>

                <div class="uploadedImagesContainer">
                    @foreach($data as $item)
                        <div class="uploadedImageContainer">
                            <div class="imageContainer">
                                <img src="{{ asset('uploaded_images/slideshow/' . trim($item->imagePath)) }}">
                            </div>
                            <a href="/admin/delete_slideshow/{{ $item->id }}"><button class="btn btn-danger w-100 mt-2">Delete</button></a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
