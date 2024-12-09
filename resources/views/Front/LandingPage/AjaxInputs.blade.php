@if($category->number_rooms != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> عدد الغرف </label>
        <input type="text" class="form-control delEffect" name="RoomCount"
               placeholder="عدد الغرف" value="@if($Type == 2){{$Product->number_rooms}}@endif">
    </div>
@else
    {{''}}
@endif
@if($category->swimming_pool != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> حمام السباحة </label>
{{--        <input type="number" class="form-control delEffect" name="CountSwimmingPool"--}}
{{--               placeholder="عدد برك سباحه">--}}
        <select name="CountSwimmingPool" class="form-control">
            @if($Type == 2)
                @if($Product->swimming_pool == 1)
                    <option selected value="1"> يوجد</option>
                    <option value="0">لا يوجد</option>
                @else
                    <option value="1"> يوجد</option>
                    <option selected value="0">لا يوجد</option>
                @endif
            @else
                <option value="1"> يوجد</option>
                <option value="0">لا يوجد</option>
            @endif
        </select>
    </div>
@else
    {{''}}
@endif
@if($category->Jim != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> صالة رياضة </label>
{{--        <input type="text" class="form-control delEffect" name="CountJim"--}}
{{--               placeholder="صالة رياضة">--}}
        <select name="CountJim" class="form-control ">
            @if($Type == 2)
                @if($Product->Jim == 1)
                    <option selected value="1"> يوجد</option>
                    <option value="0">لا يوجد</option>
                @else
                    <option value="1"> يوجد</option>
                    <option selected value="0">لا يوجد</option>
                @endif
            @else
                <option value="1"> يوجد</option>
                <option value="0">لا يوجد</option>
            @endif
        </select>
    </div>
@else
    {{''}}
@endif
@if($category->working_condition != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> حالة العمل </label>
{{--        <input type="text" class="form-control delEffect" name="working_condition"--}}
{{--               placeholder="حالة العمل">--}}
        <select name="working_condition" class="form-control " >
            @if($Type == 2)
                @if($Product->working_condition == 1)
                    <option value="0"> مستعمل </option>
                    <option selected value="1">جديد</option>
                @else
                    <option selected value="0"> مستعمل </option>
                    <option value="1">جديد</option>
                @endif
            @else
                <option value="0"> مستعمل </option>
                <option value="1">جديد</option>
            @endif
        </select>
    </div>
@else
    {{''}}
@endif
@if($category->year != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> السنة </label>
        <input type="text" class="form-control delEffect" name="year"
               placeholder="السنة" value="@if($Type == 2){{$Product->year}}@endif">
    </div>
@else
    {{''}}
@endif
@if($category->cere != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> قير </label>
{{--        <input type="text" class="form-control delEffect" name="cere"--}}
{{--               placeholder="القير">--}}
        <select name="cere" class="form-control ">
            @if($Type == 2)
                @if($Product->cere == 1)
                    <option value="0"> اتوماتك</option>
                    <option selected value="1">عادي</option>
                @else
                    <option selected value="0">اتوماتك</option>
                    <option value="1">عادي</option>
                @endif
            @else
                <option value="0">اتوماتك</option>
                <option value="1">عادي</option>
            @endif
        </select>
    </div>
@else
    {{''}}
@endif
@if($category->number_cylinders != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> عدد الاسطوانات </label>
        <input type="number" class="form-control delEffect" name="number_cylinders"
               placeholder="عدد الاسطوانات" value="@if($Type == 2){{$Product->number_cylinders}}@endif">
    </div>
@else
    {{''}}
@endif
@php
    $brand=\App\Models\Item::where('type',1)->get();
@endphp
@if($category->brand != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> نوع الماركة </label>
{{--        <input type="text" class="form-control delEffect" name="brand"--}}
{{--               placeholder="ماركة">--}}
        <select name="brand" class="form-control ">
            @if($Type == 2)
                @foreach($brand as $b)
                    @if($Product->brand_id == $b->id)
                        <option selected value="{{$b->id}} "> {{$b->title_ar}} </option>
                    @else
                        <option value="{{$b->id}} "> {{$b->title_ar}} </option>
                    @endif
                @endforeach
            @else
                @foreach($brand as $b)
                    <option value="{{$b->id}} "> {{$b->title_ar}} </option>
                @endforeach
            @endif
        </select>
    </div>
@else
    {{''}}
@endif
@if($category->salary != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> الراتب </label>
        <input type="text" class="form-control delEffect" name="salary"
               placeholder="الراتب" value="@if($Type == 2){{$Product->salary}}@endif">
    </div>
@else
    {{''}}
@endif
@php
    $educational_level=\App\Models\Item::where('type',2)->get();
@endphp
@if($category->educational_level != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> المستوى التعليمي </label>
{{--        <input type="text" class="form-control delEffect" name="educational_level"--}}
{{--               placeholder=" المستوى التعليمي">--}}
        <select name="educational_level" class="form-control ">
            @if($Type == 2)
                @foreach($educational_level as $e)
                    @if($Product->educational_level_id == $e->id)
                        <option selected value="{{$e->id}} "> {{$e->title_ar}} </option>
                    @else
                        <option value="{{$e->id}} "> {{$e->title_ar}} </option>
                    @endif
                @endforeach
            @else
                @foreach($educational_level as $e)
                    <option value="{{$e->id}} "> {{$e->title_ar}} </option>
                @endforeach
            @endif
        </select>
    </div>
@else
    {{''}}
@endif
@php
    $specialization=\App\Models\Item::where('type',3)->get();
@endphp
@if($category->specialization != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> التخصص </label>
{{--        <input type="text" class="form-control delEffect" name="specialization"--}}
{{--               placeholder="التخصص">--}}
        <select name="specialization" class="form-control ">
            @if($Type == 2)
                @foreach($specialization as $s)
                    @if($Product->specialization_id == $s->id)
                        <option selected value="{{$s->id}} "> {{$s->title_ar}} </option>
                    @else
                        <option value="{{$s->id}} "> {{$s->title_ar}} </option>
                    @endif
                @endforeach
            @else
                @foreach($specialization as $s)
                    <option value="{{$s->id}} "> {{$s->title_ar}} </option>
                @endforeach
            @endif
        </select>
    </div>
@else
    {{''}}
@endif
@if($category->biography != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> سيرة شخصية </label>
        <input type="file" class="form-control delEffect" name="biography"
               placeholder="سيرة شخصية">
    </div>
@else
    {{''}}
@endif
@php
    $animal_type=\App\Models\Item::where('type',5)->get();
@endphp
@if($category->animal_type != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> نوع الحيوان </label>
{{--        <input type="text" class="form-control delEffect" name="animal_type"--}}
{{--               placeholder="نوع الحيوان">--}}
        <select name="animal_type" class="form-control ">
            @if($Type == 2)
                @foreach($animal_type as $a)
                    @if($Product->animal_type_id == $a->id)
                        <option selected value="{{$a->id}} "> {{$a->title_ar}} </option>
                    @else
                        <option value="{{$a->id}} "> {{$a->title_ar}} </option>
                    @endif
                @endforeach
            @else
                @foreach($animal_type as $a)
                    <option value="{{$a->id}} "> {{$a->title_ar}} </option>
                @endforeach
            @endif
        </select>
    </div>
@else
    {{''}}
@endif
@php
    $fashion_type=\App\Models\Item::where('type',6)->get();
@endphp
@if($category->fashion_type != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> نوع الملابس </label>
{{--        <input type="text" class="form-control delEffect" name="fashion_type"--}}
{{--               placeholder="نوع الملابس">--}}
        <select name="fashion_type" class="form-control ">
            @if($Type == 2)
                @foreach($fashion_type as $f)
                    @if($Product->fashion_type_id == $f->id)
                        <option selected value="{{$f->id}} "> {{$f->title_ar}} </option>
                    @else
                        <option value="{{$f->id}} "> {{$f->title_ar}} </option>
                    @endif
                @endforeach
            @else
                @foreach($fashion_type as $f)
                    <option value="{{$f->id}} "> {{$f->title_ar}} </option>
                @endforeach
            @endif
        </select>
    </div>
@else
    {{''}}
@endif
@php
    $subjects=\App\Models\Item::where('type',4)->get();
@endphp
@if($category->subjects != null)
    <div class="ItemAd col-12 CairoSemiBold mt-4">
        <label class="mb-2"> الموضوع </label>
        <select name="subjects" class="form-control ">
            @if($Type == 2)
                @foreach($subjects as $su)
                    @if($Product->subjects_id == $su->id)
                        <option selected value="{{$su->id}} "> {{$su->title_ar}} </option>
                    @else
                        <option value="{{$su->id}} "> {{$su->title_ar}} </option>
                    @endif
                @endforeach
            @else
                @foreach($subjects as $su)
                    <option value="{{$su->id}} "> {{$su->title_ar}} </option>
                @endforeach
            @endif
        </select>
    </div>
@else
    {{''}}
@endif
