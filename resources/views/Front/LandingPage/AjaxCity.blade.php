@foreach($gov as $dd)
    <option value="{{$dd->id}}">{{$dd->title_ar}}</option>
{{--    @if($Type == 1)--}}
{{--        <option value="{{$dd->id}}">{{$dd->title_ar}}</option>--}}
{{--    @elseif($Type == 2)--}}
{{--        @if($city_id == $dd->id)--}}
{{--            <option selected value="{{$dd->id}}">{{$dd->title_ar}}</option>--}}
{{--        @else--}}
{{--            <option value="{{$dd->id}}">{{$dd->title_ar}}</option>--}}
{{--        @endif--}}
{{--    @endif--}}
@endforeach
