@extends('frontend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ asset('/frontend/css/contact_us.css') }}">
@endsection

@section('content')
    <div class="contactUsContainer">
        <div class="leftSideContainer">
            <h1>Contact Us</h1>

            <p style="font-size: 15px">Email: sreymean996199@gmail.com</p>
            <p style="font-size: 15px">Tel: 010 996199 / 068 996199 / 076 7777053</p>
            <p style="font-size: 15px;">Address: <span style="font-family: 'Hanuman', serif;">ផ្ទះលេខ : 01BEo,ផ្លូវលេខ 138,សង្កាត់ផ្សារដេប៉ូll,ខណ្ឌ ទួលគោក, រាជធានីភ្នំពេញ</span></p>
        </div>
        <div class="rightSideContainer">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3908.8112169362325!2d104.90324811533704!3d11.565387247295936!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31095112c710602b%3A0x2be0722af16dfd4f!2sCity%20Computer!5e0!3m2!1sen!2skh!4v1608056689819!5m2!1sen!2skh"
                    height="450"
                    frameborder="0"
                    style="border:0; width: 100%;"
                    allowfullscreen=""
                    aria-hidden="false"
                    tabindex="0">
            </iframe>
        </div>
    </div>
@endsection
