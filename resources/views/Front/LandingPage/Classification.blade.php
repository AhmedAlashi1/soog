@extends("Front.layout.master")
@section("content")
    <div class="content" id="content">
        <div class="sub-content">
            <div class="container">
                <div class="classfications classficationsPage mb-4">
                    <div class="headerShare mb-3 p-3 d-flex justify-content-between align-items-center CairoSemiBold">
                        <h4 class="m-0">الاقسام</h4>
                    </div>
                    <div class="row ">
                        <div class="item col-lg-2 col-md-2 col-sm-4 col-6  mb-lg-4 mb-2">
                            <a href="#" data-category-id="*"
                               class="text-decoration-none category-link category-link">
                                <div class="classficationImg d-flex justify-content-center align-items-center"
                                     style="background: #e0e0e0">
                                    <img src="{{url('/').'/assets/img/trolley.png'}}">
                                </div>
                                <p class="m-0 Describe CairoSemiBold text-center">
                                    جميع المنتجات
                                </p>
                            </a>
                        </div>
                        @foreach($data['categories'] as $category)
                            <div class="item col-lg-2 col-md-2 col-sm-4 col-6  mb-lg-4 mb-2">
                                <a href="#" data-category-id="{{ $category->id }}"
                                   class="text-decoration-none category-link category-link">
                                    <div class="classficationImg d-flex justify-content-center"
                                         style="background: {{$category->color}}; padding-top: 20%">
                                        <img src="{{url('/').'/assets/tmp/'.$category->image}}" width="39.935"
                                             height="41.027" style="width: 80px; height: 60px" id="vehicle-car-svgrepo-com">
                                        {{--                                <svg xmlns="http://www.w3.org/2000/svg" width="39.935" height="41.027" viewBox="0 0 39.935 41.027">--}}
                                        {{--                                    <path id="vehicle-car-svgrepo-com" d="M31.39,3a7.209,7.209,0,0,1,6.993,5.456l.854,3.416h2.025a1.664,1.664,0,0,1,1.648,1.438l.015.226a1.664,1.664,0,0,1-1.438,1.648l-.226.015H40.073l.461,1.833a4.988,4.988,0,0,1,2.4,4.266V40.146a3.882,3.882,0,0,1-3.882,3.882H35.717a3.882,3.882,0,0,1-3.882-3.882l0-2.765H14.1v2.765a3.882,3.882,0,0,1-3.882,3.882H6.882A3.882,3.882,0,0,1,3,40.146V21.3a4.988,4.988,0,0,1,2.4-4.266l.46-1.834h-1.2a1.664,1.664,0,0,1-1.648-1.438L3,13.536a1.664,1.664,0,0,1,1.438-1.648l.226-.015H6.689L7.545,8.46A7.209,7.209,0,0,1,14.538,3ZM10.772,37.38H6.325l0,2.765a.555.555,0,0,0,.555.555h3.336a.555.555,0,0,0,.555-.555Zm28.835,0H35.16l0,2.765a.555.555,0,0,0,.555.555h3.336a.555.555,0,0,0,.555-.555ZM37.944,19.636H7.991A1.664,1.664,0,0,0,6.327,21.3V34.053H39.608V21.3A1.664,1.664,0,0,0,37.944,19.636ZM19.079,27.4h7.767a1.664,1.664,0,0,1,.226,3.312l-.226.015H19.079a1.664,1.664,0,0,1-.226-3.312l.226-.015h0Zm14.974-4.436a2.218,2.218,0,1,1-2.218,2.218A2.218,2.218,0,0,1,34.053,22.963Zm-22.181,0a2.218,2.218,0,1,1-2.218,2.218A2.218,2.218,0,0,1,11.872,22.963ZM31.39,6.327H14.538a3.882,3.882,0,0,0-3.766,2.94l-1.76,7.041H36.921L35.155,9.265A3.882,3.882,0,0,0,31.39,6.327Z" transform="translate(-3 -3)" fill="#212121"/>--}}
                                        {{--                                </svg>--}}
                                    </div>
                                    <p class="m-0 Describe CairoSemiBold text-center">
                                        <?php
                                        $title = 'title_' . app()->getLocale();
                                        $note = 'note' . app()->getLocale();
                                        ?>
                                        {{$category->$title}}
                                    </p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="filter-content">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            loadCat({{$cat_id}});
            @if($data['Sub_Cat'] != null)
                loadData({{$data['Sub_Cat']}});
            @endif
        });

        $(document).ready(function() {
            $('.category-link').click(function(event) {
                event.preventDefault();
                var categoryId = $(this).data('category-id');
                if(categoryId == "*"){
                    loadCat({{$cat_id}});
                }else{
                    loadData(categoryId);
                }
            });
        });

        function loadCat(VarmainCat) {
            var mainCat = VarmainCat; // replace with your desired cat_id value
            $.ajax({
                url: "{{route('filter')}}",
                data: {
                    mainCat: mainCat
                },
                success: function(response) {
                    $('.filter-content').html(response.value);
                }
            });
        }
        function loadData(categoryId) {
            console.log("Done Make Filter !" + categoryId);
            var url = '{{route('filter')}}';
            var data = {};
            if (categoryId) {
                data.categoryId = categoryId;
            }
            $.ajax({
                url: url,
                method: 'GET',
                data: data,
                dataType: 'json',
                success: function(response) {
                    $('.filter-content').html(response.value);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
    </script>
@endsection
