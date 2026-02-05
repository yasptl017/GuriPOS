<div>
    <form method="POST" action="{{route('address.store')}}">
        @csrf
        <div class="row">
            <div class="col-md-6 col-lg-12 col-xl-6">
                <div class="tf__check_single_form">
                    <input type="text" placeholder="{{__('Full Name')}}"
                           name="name">
                </div>
            </div>

            <div class="col-md-6 col-lg-12 col-xl-6">
                <div class="tf__check_single_form">
                    <input type="text" placeholder="{{__('user.Phone')}}"
                           name="phone">
                </div>
            </div>
            <div class="col-md-6 col-lg-12 col-xl-6">
                <div class="tf__check_single_form">
                    <input type="email" placeholder="{{__('user.Email')}}"
                           name="email">
                </div>
            </div>

            <x-address-input>
                <div class=" col-md-12 col-lg-12 col-xl-12 position-relative">
                    <div class="tf__check_single_form">
                        <input type="text" id="address-input" placeholder="Enter address" name="address"
                        >
                    </div>

                </div>
            </x-address-input>
            <div class="col-12">
                <button type="submit"
                        class="common_btn">{{__('user.Save Address')}}</button>
            </div>
        </div>
    </form>
</div>
