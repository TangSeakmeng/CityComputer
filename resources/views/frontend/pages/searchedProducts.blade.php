@extends('frontend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ asset('/frontend/css/home.css') }}">
@endsection

@section('content')
    <div class="homeContainer">
        @php
            echo "<div class='brandLabel'><h4>Search Result</h4></div>";

            if(sizeof($result) == 0) {
                $image = url('assets/images/38061-search.gif');
                echo "<div class='noProductImageContainer'><img src={$image}><p>No Product Found.</p></div>";
            }
            else {
                echo "<div class='productsContainer'>";

                foreach ($result as $item_product) {
                    $product_image = url('uploaded_images/products/' . trim($item_product->image_path));
                    $sale_label = url('uploaded_images/sale_statuses/' . trim($item_product->sale_status_label));

                    if($item_product->discount_price == 0) {
                        if($item_product->sale_status_label == "") {
                            echo "
                                <div class='productContainer'>
                                    <div class='imageContainer'><img src='{$product_image}'></div>
                                    <h1>{$item_product->sale_price}$</h1>
                                    <h4>{$item_product->product_name}</h4>
                                    <div class='descriptionContainer'>{$item_product->description}</div>
                                </div>
                            ";
                        } else {
                            echo "
                                <div class='productContainer'>
                                    <img src='{$sale_label}' class='productLabel'>
                                    <div class='imageContainer'><img src='{$product_image}'></div>
                                    <h1>{$item_product->sale_price}$</h1>
                                    <h4>{$item_product->product_name}</h4>
                                    <div class='descriptionContainer'>{$item_product->description}</div>
                                </div>
                            ";
                        }
                    } else {
                        if($item_product->sale_status_label == "") {
                            echo "
                                <div class='productContainer'>
                                    <div class='imageContainer'><img src='{$product_image}'></div>
                                    <h1>{$item_product->discount_price}$ <span>{$item_product->sale_price}$</span></h1>
                                    <h4>{$item_product->product_name}</h4>
                                    <div class='descriptionContainer'>{$item_product->description}</div>
                                </div>
                            ";
                        } else {
                            echo "
                                <div class='productContainer'>
                                    <img src='{$sale_label}' class='productLabel'>
                                    <div class='imageContainer'><img src='{$product_image}'></div>
                                    <h1>{$item_product->discount_price}$ <span>{$item_product->sale_price}$</span></h1>
                                    <h4>{$item_product->product_name}</h4>
                                    <div class='descriptionContainer'>{$item_product->description}</div>
                                </div>
                            ";
                        }
                    }
                }
            }

            echo '</div>';
        @endphp
    </div>
    </div>
@endsection
