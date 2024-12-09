@extends("Front.layout.master")
@section("content")
<div class="content" id="content">
    <div class="sub-content">
        <div class="container">
            <div class="Chats">
                <div class="row">
                    <div class="col-md-4 col-12 mb-md-0 mb-3">
                        <div class="PersonChats">
                            <div class="personItem active d-flex align-items-center">
                                <img src="img/Public/ss.jpg">
                                <div class="infoUser ms-3">
                                    <p class="nameUser m-0 CairoSemiBold">محمود أحمد</p>
                                    <p class="messeage m-0 CairoSemiBold">مرحبا احتاج ...</p>
                                </div>
                            </div>
                            <div class="personItem d-flex align-items-center">
                                <img src="img/Public/ss.jpg">
                                <div class="infoUser ms-3">
                                    <p class="nameUser m-0 CairoSemiBold">محمود أحمد</p>
                                    <p class="messeage m-0 CairoSemiBold">مرحبا احتاج ...</p>
                                </div>
                            </div>
                            <div class="personItem d-flex align-items-center">
                                <img src="img/Public/ss.jpg">
                                <div class="infoUser ms-3">
                                    <p class="nameUser m-0 CairoSemiBold">محمود أحمد</p>
                                    <p class="messeage m-0 CairoSemiBold">مرحبا احتاج ...</p>
                                </div>
                            </div>
                            <div class="personItem d-flex align-items-center">
                                <img src="img/Public/ss.jpg">
                                <div class="infoUser ms-3">
                                    <p class="nameUser m-0 CairoSemiBold">محمود أحمد</p>
                                    <p class="messeage m-0 CairoSemiBold">مرحبا احتاج ...</p>
                                </div>
                            </div>
                            <div class="personItem d-flex align-items-center">
                                <img src="img/Public/ss.jpg">
                                <div class="infoUser ms-3">
                                    <p class="nameUser m-0 CairoSemiBold">محمود أحمد</p>
                                    <p class="messeage m-0 CairoSemiBold">مرحبا احتاج ...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-12">
                        <div class="PersonMessages d-flex flex-column">
                            <div class="PersonName d-flex align-items-center">
                                <img src="img/Public/ss.jpg">
                                <div class="infoUser ms-3">
                                    <p class="nameUser m-0 CairoSemiBold">محمود أحمد</p>
                                </div>
                            </div>
                            <div class="ChatSpace CairoSemiBold">
                                <div class="message_sent ms-auto messageItem mb-3">
                                    هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة
                                </div>
                                <div class="received_message me-auto messageItem mb-3">
                                    هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة
                                </div>
                                <div class="NewMessage mt-5 mb-1">
                                    <hr>
                                    <h6 class="m-auto">رسالة جديدة</h6>
                                </div>
                                <div class="message_sent ms-auto messageItem mb-3">
                                    هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة
                                </div>
                                <div class="received_message messageItem mb-3">
                                    هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة
                                </div>
                            </div>
                            <div class="WriteMessage CairoSemiBold">
                                <div class="write h-100">
                                    <form action="" method="post" class="input-group h-100">
                                        <input type="text" class="border-0 delEffect bg-none"
                                               name="message" placeholder="أكتب رسالة ...">
                                        <button type="submit"  class="border-0 bg-none ms-2">
                                            <i class="fab fa-telegram-plane"></i>
                                        </button>
                                        <label class=" ms-md-4 ms-2 d-flex align-items-center" for="fileUpload">
                                            <i class="fal fa-paperclip"></i>
                                            <input type="file" name="message" class="d-none" id="fileUpload">
                                        </label>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
