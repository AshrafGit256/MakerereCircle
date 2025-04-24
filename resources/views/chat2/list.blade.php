@extends('admin.layouts.app')

@section('style')
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>

<link href="{{ asset('assets/emojionearea/emojionearea.min.css') }}" rel="stylesheet"/>

<link href="{{ asset('css/chat-list.css') }}" rel="stylesheet"/>
@endsection

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">My Chat</h1>
                </div><!-- /.col -->

            </div><!-- /.row -->
        </div>
    </section><!-- /.container-fluid -->

    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card chat-app">
                        <div id="plist" class="people-list">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                </div>
                                <input type="text" class="form-control" placeholder="Search...">
                            </div>
                            <ul class="list-unstyled chat-list mt-2 mb-0">
                                @include('chat2._user')
                            </ul>
                        </div>
                        <div class="chat">
                            @if(!empty($getReceiver))
                            @include('chat2._messages')
                            @else

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@section('script')

<script src="{{ asset('assets/emojionearea/emojionearea.min.js') }}"></script>>
<script>
$(document).ready(function () {

    $(".emojionearea").emojioneArea();

    // Submit on Enter key
    $('#ClearMessage').keypress(function (e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            $('#submit_message').submit();
        }
    });

    // Submit form via AJAX
    $(document).on('submit', '#submit_message', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: "{{ url('submit_message') }}",
            data: new FormData(this),
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (data) {
                    $('#AppendMessage').append(data.success); // Append new message
                    $('#ClearMessage').val(''); // Clear input
                    scrolldown();
                    $(".emojionearea").emojioneArea({});
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    });

    // Auto scroll to bottom
    function scrolldown() {
        $('.chat-history').animate({
            scrollTop: $('.chat-history')[0].scrollHeight
        }, 500);
    }

    // Scroll when page loads
    scrolldown();
});
</script>

@endsection