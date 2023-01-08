@if($showGrouping)
    <div class="btn-group" role="group">
        <a href="{{route("payment.created")}}" class="btn {{request()->is("payment/created") ? 'btn-primary' : 'btn-outline-primary'}} ">Seznam plateb</a>
        <a href="{{route("payment.group")}}" class="btn {{request()->is("payment/group") ? 'btn-primary' : 'btn-outline-primary'}}">Skupiny plateb</a>
    </div>
    <div class="d-block mb-2"></div>
@endif

