@extends('frontend.layout.layout')

@section('link_css')
    <link rel="stylesheet" href="{{ asset('/frontend/css/home.css') }}">
@endsection

@section('content')
    <div class="homeContainer">
        @php
            $temp_brand = "";
            foreach ($result as $item_product) {
                if($temp_brand == $item_product->brand_name) {
                    $temp_brand = $item_product->brand_name;
                    $product_image = url('uploaded_images/products/' . trim($item_product->image_path));
                    $sale_label = url('uploaded_images/sale_statuses/' . trim($item_product->sale_status_label));

                    if($item_product->discount_price == 0) {
                        if($item_product->sale_status_label == "") {
                            echo "
                            <div class='productContainer'>
                                <div class='forOverFlowHidden'>
                                    <div class='imageContainer'><img src='{$product_image}'></div>
                                    <h1>{$item_product->sale_price}$</h1>
                                    <h4>{$item_product->product_name}</h4>
                                    <div class='descriptionContainer'>{$item_product->description}</div>
                                </div>
                            </div>
                        ";
                        } else {
                            echo "
                            <div class='productContainer'>
                                <img src='{$sale_label}' class='productLabel'>
                                <div class='forOverFlowHidden'>
                                    <div class='imageContainer'><img src='{$product_image}'></div>
                                    <h1>{$item_product->sale_price}$</h1>
                                    <h4>{$item_product->product_name}</h4>
                                    <div class='descriptionContainer'>{$item_product->description}</div>
                                </div>
                            </div>
                        ";
                        }
                    } else {
                        if($item_product->sale_status_label == "") {
                            echo "
                                <div class='productContainer'>
                                    <div class='forOverFlowHidden'>
                                        <div class='imageContainer'><img src='{$product_image}'></div>
                                        <h1>{$item_product->discount_price}$ <span>{$item_product->sale_price}$</span></h1>
                                        <h4>{$item_product->product_name}</h4>
                                        <div class='descriptionContainer'>{$item_product->description}</div>
                                    </div>
                                </div>
                            ";
                        } else {
                            echo "
                                <div class='productContainer'>
                                    <img src='{$sale_label}' class='productLabel'>
                                    <div class='forOverFlowHidden'>
                                        <div class='imageContainer'><img src='{$product_image}'></div>
                                        <h1>{$item_product->discount_price}$ <span>{$item_product->sale_price}$</span></h1>
                                        <h4>{$item_product->product_name}</h4>
                                        <div class='descriptionContainer'>{$item_product->description}</div>
                                    </div>
                                </div>
                            ";
                        }
                    }
                } else {
                    if($temp_brand != "") {
                        echo "</div>";
                    }

                    $brand_image = url('uploaded_images/brands/' . trim($item_product->brand_image_path));
                    $product_image = url('uploaded_images/products/' . trim($item_product->image_path));
                    $sale_label = url('uploaded_images/sale_statuses/' . trim($item_product->sale_status_label));

                    echo "
                        <div class='brandLabel'>
                            <h4>{$item_product->brand_name}</h4>
                        </div>
                    ";

                    echo "<div class='productsContainer'>";

                    if($item_product->discount_price == 0) {
                        if($item_product->sale_status_label == "") {
                            echo "
                            <div class='productContainer'>
                                <div class='forOverFlowHidden'>
                                    <div class='imageContainer'><img src='{$product_image}'></div>
                                    <h1>{$item_product->sale_price}$</h1>
                                    <h4>{$item_product->product_name}</h4>
                                    <div class='descriptionContainer'>{$item_product->description}</div>
                                </div>
                            </div>
                        ";
                        } else {
                            echo "
                            <div class='productContainer'>
                                <img src='{$sale_label}' class='productLabel'>
                                <div class='forOverFlowHidden'>
                                    <div class='imageContainer'><img src='{$product_image}'></div>
                                    <h1>{$item_product->sale_price}$</h1>
                                    <h4>{$item_product->product_name}</h4>
                                    <div class='descriptionContainer'>{$item_product->description}</div>
                                </div>
                            </div>
                        ";
                        }
                    } else {
                        if($item_product->sale_status_label == "") {
                            echo "
                                <div class='productContainer'>
                                    <div class='forOverFlowHidden'>
                                        <div class='imageContainer'><img src='{$product_image}'></div>
                                        <h1>{$item_product->discount_price}$ <span>{$item_product->sale_price}$</span></h1>
                                        <h4>{$item_product->product_name}</h4>
                                        <div class='descriptionContainer'>{$item_product->description}</div>
                                    </div>
                                </div>
                            ";
                        } else {
                            echo "
                                <div class='productContainer'>
                                    <img src='{$sale_label}' class='productLabel'>
                                    <div class='forOverFlowHidden'>
                                        <div class='imageContainer'><img src='{$product_image}'></div>
                                        <h1>{$item_product->discount_price}$ <span>{$item_product->sale_price}$</span></h1>
                                        <h4>{$item_product->product_name}</h4>
                                        <div class='descriptionContainer'>{$item_product->description}</div>
                                    </div>
                                </div>
                            ";
                        }
                    }

                    $temp_brand = $item_product->brand_name;
                }
            }
        @endphp
    </div>
    </div>
@endsection
