@foreach($getChat as $value)
    @php $mine = $value->sender_id == Auth::id(); @endphp
    <li class="clearfix {{ $mine ? 'text-right' : '' }}">
      <div class="message-data{{ $mine ? ' text-right' : '' }}">
        @if(! $mine)
          <img src="{{ $value->getSender->getImage() }}" style="height:45px;width:45px" alt="avatar">
        @endif
        <span class="message-data-time">
          {{ \Carbon\Carbon::parse($value->created_date)->diffForHumans() }}
        </span>
        @if($mine)
          <img src="{{ $value->getSender->getImage() }}" style="height:45px;width:42px" alt="avatar">
        @endif
      </div>

      <div class="message {{ $mine ? 'other-message float-right' : 'my-message' }}" style="text-align: left;">
        {!! nl2br(e($value->message)) !!}
      </div>
    </li>
@endforeach
