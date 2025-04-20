<div class="chat-header clearfix">
    @include('chat2._header')
</div>
<div class="chat-history">
    @include('chat2._chat')
</div>
<div class="chat-message clearfix">
    <form action="" id="submit_message" method="post" class="mb-0">
        <input type="hidden" value="{{ $getReceiver->id }}" name="receiver_id">
        {{ csrf_field() }}
        <textarea name="message" id="ClearMessage" required class="form-control"></textarea>
        <div class="row">
            <div class="col-md-6 hidden-sm">
                <a href="javascript:void(0);" class="btn btn-outline-primary" style="margin-top: 10px;"><i class="fa fa-image"></i></a>
            </div>
            <div class="col-md-6" style="text-align: right;">
                <button class="btn btn-primary" style="margin-top: 10px;" type="submit">Send</button>
            </div>
        </div>
    </form>
</div>