<style>
    a {
        text-decoration: none; 
        color: inherit;        
    }

    .name {
        color: white;          
        font-weight: bold;
    }
    .message-count {
        display: inline-block;
        min-width: 20px;
        height: 20px;
        line-height: 20px;
        padding: 0 6px;
        font-size: 12px;
        font-weight: bold;
        color: black;
        background-color: #25D366;
        /* WhatsApp green */
        border-radius: 50%;
        text-align: center;
        margin-left: 8px;
    }
</style>

@foreach($getChatUser as $user)
<li class="clearfix getChatWindows id={{ $user['user_id'] }}">
    
        <img src="{{ $user['profile_pic'] }}" alt="avatar">
        <div class="about">
            <div class="name">
                {{ $user['name'] }}
                @if($user['message_count'] > 0)
                <span id="ClearMessage{{ $user['user_id'] }}" class="message-count">{{ $user['message_count'] }}</span>
                @endif
            </div>
            <div class="status">
                <i class="fa fa-circle offline"></i>
                {{ \Carbon\Carbon::parse($user['created_date'])->diffForHumans() }}
            </div>
        </div>
    
</li>
@endforeach

<!-- <li class="clearfix active">
    <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">
    <div class="about">
        <div class="name">Aiden Chavez</div>
        <div class="status"> <i class="fa fa-circle online"></i> online </div>
    </div>
</li> -->