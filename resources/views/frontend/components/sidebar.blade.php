<link rel="stylesheet" href="{{ url('frontend/css/sidebar.css')  }}">

<div class="navSidebarContainer">
    <ul>
        <li>
            @foreach($result2 as $item)
                @if($item->parent_id == 1 && $item->category_id != 1)
                    <div class="navElement">
                        <a href="/productsByCategory/{{$item->category_id}}">
                            <span>
                                {{ $item->category_name }}
                            </span>
                        </a>
                        <span><img src="{{ url('assets/icons/right-arrow.png') }}"></span>
                        <div class="subNavElements">
                            <ul>
                                @foreach($result2 as $item2)
                                    @if($item2->parent_id == $item->category_id)
                                        <a href="/productsByCategory/{{$item2->category_id}}">
                                            <li class="subNavElement"><p>{{ $item2->category_name }}</p></li>
                                        </a>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            @endforeach
        </li>
    </ul>
</div>
