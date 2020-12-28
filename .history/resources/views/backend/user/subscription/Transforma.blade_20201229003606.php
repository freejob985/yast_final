<?php
use Carbon\Carbon;
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
?>

<form method="post" action=" {{ route('user.Transforma') }}" class="p-5">
    @csrf
    <div class="form-group" style=" display: none;">
        <textarea class="form-control" rows="5" id="comment" placeholder="Notes"
            name="Notes">Notes</textarea>
    </div>
    <label class="radio-inline">
        <img src="https://www.flaticon.com/svg/static/icons/svg/2398/2398987.svg">

        <input type="radio" name="Type" value="Bank transfer" attr=".url{{$plan->id}}">Bank
        transfer</label>
    <label class="radio-inline" style="display: none;">
        <img style="display: none;"
            src="https://mashbac.com/wp-content/uploads/2018/07/method-3.png">
        <input type="radio" style="display: none;" name="Type" value="Vodafone Cash"
            attr=".url{{$plan->id}}" checked>Vodafone Cash</label>
    <input type="hidden" id="custId" name="Code" value="{{generateRandomString()}}">
    <input type="hidden" id="custId" name="Package" value="{{ $plan->plan_name }}">
    <input type="hidden" id="custId" name="price" value="{{ $plan->plan_price }}">
    <input type="hidden" id="custId" name="User" value="{{Auth::user()->name}}">
    <input type="hidden" id="custId" name="id_u" value="{{Auth::user()->id}}">
    <a href="{{ $plan->link }}" target="_blank" role="button"> <img
            src="https://icon-library.com/images/visa-mastercard-icon/visa-mastercard-icon-9.jpg"class="img-responsive" alt="Cinque Terre" target="_blank"></a>
    <input type="radio" name="Type" value="visa mastercard" attr=".url{{$plan->id}}"
        checked>visa mastercard</label>
    <label style="font-size: 1px;">برجاء الدخول لسداد الفاتورة</label>
    <a href="{{ $plan->link }}" class="btn btn-info btn-xs" role="button">رابط الفاتورة</a>
    <input type="hidden" id="custIds" name="url" value="" class="url{{$plan->id}}"
        placeholder="برجاء ارسال الفاتوره">
    <input type="hidden" id="custId" name="subscription_end_date" value="<?php
            $mutable = Carbon::now();
            $modifiedMutable = $mutable->add((int)$plan->plan_period, 'day'); 
        echo   $mutable->isoFormat('D-M-Y');?>">
    <input type="hidden" class="custId" name="plan_period" value="{{$plan->plan_period}}">
    <br>
    <hr>
    <div class="row form-group justify-content-between">
        <div class="col-12">
            <button type="submit" class="btn btn-success py-2 px-4 text-white"
                {{ $subscription->plan->plan_type == \App\Plan::PLAN_TYPE_PAID ? 'disabled' : '' }}>
                {{ __('backend.plan.select-plan') }}
            </button>
        </div>
    </div>

</form>