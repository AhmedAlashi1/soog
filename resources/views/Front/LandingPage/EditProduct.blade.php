@extends("Front.layout.master")
@section("content")
{{--    {{dd($product)}}--}}
    <div class="content" id="content">
        <div class="sub-content">
            @if(session('success'))
                <h4 class=" text-success">
                    {{ session('success') }}
                </h4>
            @elseif(session('error'))
                <h4 class=" text-danger">
                    {{ session('error') }}
                </h4>
            @endif
            <div class="container">
                <div class="PostAd">
                    <h6 class="CairoSemiBold mb-4">
                        <i class="fal fa-edit me-2"></i>
                        <span>تعديل إعلانك</span>
                    </h6>
                    <form action="{{route('StoreEditProduct')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="ProductId" value="{{$product->id}}">
                        <input type="hidden" name="UserId" value="{{Auth::guard('user')->user()->id}}">
                        <div class="row w-100 m-auto">
                            <div class="ItemAd col-12 CairoSemiBold">
                                <label class="mb-2">اسم الإعلان </label>
                                <input type="text" class="form-control delEffect" name="title"
                                       placeholder="(Porsche AG)بورشه " value="{{$product->title_ar}}">
                            </div>
                            <div class="ItemAd col-12 CairoSemiBold">
                                <label class="mb-2">اسم الإعلان بالانجليزي </label>
                                <input type="text" class="form-control delEffect" name="Entitle"
                                       placeholder="(Porsche AG)بورشه "value="{{$product->title_en}}">
                            </div>
                            <div class="ItemAd col-12 CairoSemiBold mt-4">
                                <label class="mb-2"> السعر </label>
                                <input type="text" class="form-control delEffect"
                                       name="price" placeholder="السعر" value="{{$product->price}}">
                            </div>
                            <div class="col-md-6 col-12 CairoSemiBold mt-4">
                                <label class="mb-2"> الدولة </label>
                                <div class="tt-select">
                                    <select class="form-select delEffect country" name="country_id">
                                        @foreach($countries as $country)
                                            @if($product->country_id == $country->id)
                                                <option selected value="{{$country->id}}">{{$country->title_ar}}</option>
                                            @else
                                                <option value="{{$country->id}}">{{$country->title_ar}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12 CairoSemiBold mt-4">
                                <label class="mb-2">المدينة</label>
                                <div class="tt-select">
                                    <select class="form-select delEffect city" name="governorates_id">

                                    </select>
                                </div>

                            </div>
                            <div class="ItemAd col-12 CairoSemiBold mt-4">
                                <label class="mb-2">وصف الإعلان </label>
                                <textarea class="form-control delEffect" name="DescribeAd" rows="4"
                                          placeholder="هذا النص هو مثال لنص يمكن">{{$product->note_ar}}</textarea>
                            </div>
                            <div class="ItemAd col-12 CairoSemiBold mt-4">
                                <label class="mb-2">وصف الإعلان بالانجليزي</label>
                                <textarea class="form-control delEffect" name="EnDescribeAd" rows="4"
                                          placeholder="This text is an example of text that can"> {{$product->note_en}}</textarea>
                            </div>
                            <div class="col-md-6 col-12 CairoSemiBold mt-4">
                                <label class="mb-2"> القسم الرئيسي </label>
                                <div class="tt-select">
                                    <select class="form-select delEffect category" name="Pcat_id">
                                        @foreach($categories as $cat)
                                            @if($Activecategorie->parent_id == $cat->id)
                                                <option selected value="{{$cat->id}}">{{$cat->title_ar}}</option>
                                            @else
                                                <option value="{{$cat->id}}">{{$cat->title_ar}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12 CairoSemiBold mt-4">
                                <label class="mb-2">القسم الفرعي</label>
                                <div class="tt-select">
                                    <select class="form-select delEffect sub-category " id="sub-category" name="cat_id">

                                    </select>
                                </div>
                            </div>
                            <div class="inputs-filter">

                            </div>
                            <div class="ItemAd col-12 CairoSemiBold mt-4">
                                <label class="mb-2"> اضف حتى سعة عشر صور ومقطع فيديو 30 ث </label>
                                <div class="Imgs">
                                    <div class="row" id="ImgAds">
                                        <div class="col-2 addImgsAd">
                                            <div class="StyledAddImgs p-1">
                                                <input type="file" class="form-control delEffect w-25 d-none"
                                                       id="upload" name="images[]"
                                                       multiple="multiple"
                                                       accept="image/jpeg, image/png, image/jpg video/mp4,video/x-m4v,video/*">
                                                <label class="CustomFile text-center d-flex flex-column align-items-center justify-content-center"
                                                       for="upload">
                                                    <i class="fal fa-image"></i>
                                                    <p class="m-0">+ صور </p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ImgDisplay d-flex" id="ImgDisplay"></div>
                                    @if($product->charityImages != null || empty($product->charityImages))
                                        <label class="mb-2 mt-4"> الصور القديمة للإعلان </label>
                                        <div class="ImgDisplay d-flex" id="OldImgDisplay">
                                                @foreach($product->charityImages as $ImgPro)
                                                    <div class="image" data-img="{{$ImgPro->id}}">
                                                        <img src="{{asset('assets/tmp/'.$ImgPro->image)}}" alt="image">
                                                        <span onclick="deleteEditImage(this)">&times;</span>
                                                    </div>
                                                @endforeach
                                        </div>
                                        <div id="ImgDeleted"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="ItemAd col-12 CairoSemiBold mt-4">
                                <button type="submit" class="btn BtnSubmit CairoSemiBold delEffect">تأكيد التعديل</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            function StartSubCategory () {
                var category = jQuery('.category').val();
                var id = {{$product->cat_id}};
                $.ajax({
                    url: "{{ route('catFilter') }}",
                    type: "GET",
                    data: {
                        'category': category,
                        'idSubCat':id,
                        'Type' : 2
                    },
                }).done(function (data) {
                    jQuery('.sub-category').html(data.value);
                    GetSubCategory();
                }).fail(function (data) {
                });
            }
            function GetSubCategory () {
                var sub_category = jQuery('#sub-category').val();
                var id = {{$product->id}};
                $.ajax({
                    url: "{{ route('inputFilter') }}",
                    type: "GET",
                    data: {
                        'sub_category': sub_category,
                        'Type': 2,
                        'id' :id
                    },
                }).done(function (data) {
                    $('.inputs-filter').html(data.value);
                }).fail(function (data) {
                    return false;
                });
            }
            function GetCountry() {
                var country = jQuery('.country').val();
                var city = {{$product->governorates_id}};
                $.ajax({
                    url: "{{ route('cityFilter') }}",
                    type: "GET",
                    data: {
                        'country': country,
                        'Type' : 2,
                        'idcity':city

                    },
                }).done(function (data) {
                    jQuery('.city').html(data.value);

                }).fail(function (data) {
                });

            }

            $('.country').change(GetCountry);
            GetCountry();

            $('.category').change(StartSubCategory);
            StartSubCategory();

            //GetSubCategory();
            $('.sub-category').change(GetSubCategory);
        });

        // var x, i, j, selElmnt, a, b, c;
        // x = document.getElementsByClassName("tt-select");
        // for (i = 0; i < x.length; i++) {
        //     selElmnt = x[i].getElementsByTagName("select")[0];
        //     a = document.createElement("DIV");
        //     a.setAttribute("class", "select-selected");
        //     a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
        //     x[i].appendChild(a);
        //     b = document.createElement("DIV");
        //     b.setAttribute("class", "select-items select-hide");
        //     for (j = 0; j < selElmnt.length; j++) {
        //         /*for each option in the original select element,
        //         create a new DIV that will act as an option item:*/
        //         c = document.createElement("DIV");
        //         c.innerHTML = selElmnt.options[j].innerHTML;
        //         c.addEventListener("click", function(e) {
        //             var y, i, k, s, h;
        //             s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        //             h = this.parentNode.previousSibling;
        //             for (i = 0; i < s.length; i++) {
        //                 if (s.options[i].innerHTML == this.innerHTML) {
        //                     s.selectedIndex = i;
        //                     h.innerHTML = this.innerHTML;
        //                     y = this.parentNode.getElementsByClassName("same-as-selected");
        //                     for (k = 0; k < y.length; k++) {
        //                         y[k].removeAttribute("class");
        //                     }
        //                     this.setAttribute("class", "same-as-selected");
        //                     break;
        //                 }
        //             }
        //             h.click();
        //         });
        //         b.appendChild(c);
        //     }
        //     x[i].appendChild(b);
        //     a.addEventListener("click", function(e) {
        //         e.stopPropagation();
        //         closeAllSelect(this);
        //         this.nextSibling.classList.toggle("select-hide");
        //         this.classList.toggle("select-arrow-active");
        //     });
        // }
        // function closeAllSelect(elmnt) {
        //     var x, y, i, arrNo = [];
        //     x = document.getElementsByClassName("select-items");
        //     y = document.getElementsByClassName("select-selected");
        //     for (i = 0; i < y.length; i++) {
        //         if (elmnt == y[i]) {
        //             arrNo.push(i)
        //         } else {
        //             y[i].classList.remove("select-arrow-active");
        //         }
        //     }
        //     for (i = 0; i < x.length; i++) {
        //         if (arrNo.indexOf(i)) {
        //             x[i].classList.add("select-hide");
        //         }
        //     }
        // }
        // document.addEventListener("click", closeAllSelect);

        let imagesArray = []
        let imagesDeletedArray = []
        let VideoArray = []
        let VideoArrayTest = []
        const InputImg = document.getElementById('upload');
        const ParentChildsImgs = document.getElementById('ImgDisplay');
        InputImg.addEventListener("change", () => {
            const files = InputImg.files;
            for (let i = 0; i < files.length; i++) {
                if(files[i].type.match('image.*')) {
                    imagesArray.push(files[i]);
                }else if(files[i].type.match('video.*')) {
                    VideoArray.push(files[i]);
                }else {
                    continue;
                }
            }
            displayImages();
        })

        function displayImages() {
            let images = ""
            let Vid = ""
            imagesArray.forEach((image, index) => {
                images += `<div class="image">
                <img src="${URL.createObjectURL(image)}" alt="image">
                <span onclick="deleteImage(${index})">&times;</span>
              </div>`
            })

            VideoArray.forEach((video, index) => {
                images += `<div class="image">
                <video>
                  <source src="${URL.createObjectURL(video)}" type="${video.type}">
                  </video>
                <span onclick="deleteVid(${index})">&times;</span>
              </div>`
            })
            ParentChildsImgs.innerHTML = images + Vid;
        }
        function deleteImage(index) {
            imagesArray.splice(index, 1);
            displayImages();
        }
        function deleteVid(index) {
            VideoArray.splice(index, 1);
            displayImages();
        }

        function deleteEditImage(btn) {
            if(confirm('هل أنت متأكد من حذف صورة المنتج ؟')){
                var ParentDeletedImg = document.getElementById("ImgDeleted");
                var parent = btn.parentElement.getAttribute('data-img');
                imagesDeletedArray.push(parent);
                let itemDeleted = "";
                imagesDeletedArray.forEach((index) => {
                    itemDeleted+= "<input type='hidden' name='deletedImgs[]' value='"+index+"'>";
                });
                ParentDeletedImg.innerHTML = itemDeleted;
                btn.parentElement.remove();
            }else{
                return false;
            }
        }

    </script>
@endsection
