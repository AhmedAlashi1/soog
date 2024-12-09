@foreach($categories as $cat)
    <option value="{{$cat->id}}">{{$cat->title_ar}}</option>
@endforeach
