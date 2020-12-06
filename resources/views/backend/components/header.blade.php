<link rel="stylesheet" href="{{ url('backend/css/header.css') }}">

<div class="aboveHeaderContainer">
    <p>Username: {{ Auth::user()->name }}, Email: {{ Auth::user()->email }}</p>
</div>

<div class="headerContainer">
    <div class="headerContainer_logo">
        <a href="/admin/">
            <img src="{{ url('assets/logos/CS-logo.png') }}">
        </a>
    </div>

    <div class="headerContainer_navigation">
        <ul>
            <a href="/admin/"><li>Home</li></a>
            <a href="/admin/products"><li>Product</li></a>
            <a href="/admin/sell_operation/"><li>Sell</li></a>
            <a href="/admin/import_products/"><li>Import</li></a>
            <li>
                Setting
                <ul>
                    <a href="/admin/brands"><li>Brand</li></a>
                    <a href="/admin/categories"><li>Category</li></a>
                    <a href="/admin/saleStatuses"><li>Sale Status</li></a>
                    <a href="/admin/suppliers"><li>Supplier</li></a>
                    <a href="/admin/exchange_rate"><li>Exchange Rate</li></a>
                    <a href="/admin/slideshow"><li>Slideshow</li></a>

                    @if (Auth::user()->is_admin == 1)
                        <a href="/admin/users"><li>User</li></a>
                    @endif
                </ul>
            </li>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <li>{{ __('Logout') }}</li>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </ul>
    </div>
</div>

