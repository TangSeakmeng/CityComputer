<link rel="stylesheet" href="{{ url('frontend/css/slideshow.css') }}">

<div style="padding-left: 10px; padding-top: 10px;">
    <div class="slideshow-container">

        @foreach($result3 as $key=>$item)
            <div class="mySlides fade">
                <div class="numbertext">{{ $key + 1 }} / {{ sizeof($result3) }}</div>
                <div class="imageContainer">
                    <img src="{{ asset('/uploaded_images/slideshow/' . trim($item->imagePath)) }}">
                </div>
            </div>
        @endforeach

    </div>
</div>

<script>
    let slideIndex = 0;
    showSlides();

    function showSlides() {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        slideIndex++;
        if (slideIndex > slides.length) {slideIndex = 1}
        slides[slideIndex-1].style.display = "block";
        setTimeout(showSlides, 2000); // Change image every 2 seconds
    }
</script>
