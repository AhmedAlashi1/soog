<li class="nav-item dropdown position-relative CairoSemiBold">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        الأقسام
    </a>
    <ul class="dropdown-menu position-absolute">
        @foreach($categories as $cat)
            @if(count($cat->sub) > 0)
                <li class="nav-item dropstart">
                    <a class="dropdown-toggle text-decoration-none dropdown-item"
                       href="{{route('classification').'?cat_id='.$cat->id}}" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        {{$cat->title_ar}}
                    </a>
                    <ul class="dropdown-menu">
                        @foreach($cat->sub as $cat_sub)
                            <li>
                                <a class="dropdown-item" href="{{route('classification',$cat_sub->id).'?cat_id='.$cat->id}}">
                                    {{$cat_sub->title_ar}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @else
                <li><a class="dropdown-item" href="{{route('classification').'?cat_id='.$cat->id}}">{{$cat->title_ar}}</a></li>
            @endif
        @endforeach
    </ul>
</li>

