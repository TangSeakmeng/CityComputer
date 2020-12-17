<link rel="stylesheet" href="{{ url('frontend/css/header.css') }}">

<div class="aboveHeaderContainer">
    <p style="font-family: 'Hanuman', serif;">ផ្ទះលេខ : 01BEo,ផ្លូវលេខ 138,សង្កាត់ផ្សារដេប៉ូll,ខណ្ឌ ទួលគោក, រាជធានីភ្នំពេញ</p>
</div>

<div class="headerContainer">
    <div class="headerContainer_logo">
        <a href="/"><img src="{{ url('assets/logos/CS-logo.png') }}"></a>
    </div>

    <div class="headerContainer_search">
        <div class="formWrapper">
            <input type="text" placeholder="enter product name" id="txtKeyword">
            <button onclick="searchProduct()">
                Search
            </button>
        </div>
    </div>
</div>

<div class="belowHeaderContainer">
    <div class="categoriesNavigation">
        <span class="material-icons categoryIcon">List</span>
        <span>Categories</span>
    </div>

    <div class="navigationContainer">
        <div>
            <ul>
                <a href="/"><li>Home</li></a>
                <a href="/contact_us"><li>Contact Us</li></a>
            </ul>
        </div>
    </div>
</div>

<script>
    function searchProduct() {
        let keyword = document.querySelector('#txtKeyword').value;

        if(keyword != "") {
            let route = `/searchProduct/${keyword}`;
            window.location = route;
        }
    }
</script>


