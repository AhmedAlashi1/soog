@foreach($categories as $cat)
    @if($idSubCat == $cat->id)
        <option selected value="{{$cat->id}}">{{$cat->title_ar}}</option>
    @else
        <option value="{{$cat->id}}">{{$cat->title_ar}}</option>
    @endif
{{--    <option value="{{$cat->id}}">{{$cat->title_ar}}</option>--}}
@endforeach
