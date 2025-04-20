@foreach($getChat as $value)
@if($value->sender_id == Auth::user()->id)
<li class="clearfix">
    <div class="message-data text-right">
        <span class="message-data-time">{{ Carbon\Carbon::parse($value->created_date)->diffForHumans() }}</span>
        <img style="height: 45px; width: 42px;" src="{{ $value->getSender->getImage() }}" alt="avatar">
    </div>
    <div class="message other-message float-right">{!! $value->message !!}</div>
</li>

@else
<li class="clearfix">
    <div class="message-data">
        <img style="height: 45px; width: 45px;" src="{{ $value->getSender->getImage() }}" alt="avatar">
        <span class="message-data-time">{{ Carbon\Carbon::parse($value->created_date)->diffForHumans() }}</span>
    </div>
    <div class="message my-message">{!! $value->message !!}</div>
</li>
@endif
@endforeach