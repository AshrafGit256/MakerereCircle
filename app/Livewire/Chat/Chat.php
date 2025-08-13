<?php

namespace App\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Media;
use App\Notifications\MessageSentNotification;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Chat extends Component
{
    use WithFileUploads;


    public Conversation $conversation;


    public $receiver;
    public $body;
    public $media; // image or audio attachment

    public $loadedMessages;
    public $paginate_var= 10;


    function listenBroadcastedMessage($event)  {

       // dd('reached');

        $this->dispatch('scroll-bottom');

        $newMessage= Message::find($event['message_id']);



        #push message

        $this->loadedMessages->push($newMessage);

        #mark as read

        $newMessage->read_at= now();
        $newMessage->save();
        
    }


    function sendMessage()  {

        // Require either text or an attachment
        if (empty(trim((string)$this->body)) && empty($this->media)) {
            $this->addError('body', 'Type a message or attach a file');
            return;
        }

        // Validate attachment if present (images and common audio types)
        if ($this->media) {
            $this->validate([
                'media' => 'file|mimes:png,jpg,jpeg,webp,mp3,wav,m4a,ogg,webm|max:102400',
            ]);
        }

        $createdMessage= Message::create([
            'conversation_id'=>$this->conversation->id,
            'sender_id'=>auth()->id(),
            'receiver_id'=>$this->receiver->id,
            'body'=>$this->body
        ]);

        // Save attachment to Media, linked to this message
        if ($this->media) {
            $mime = str_contains($this->media->getMimeType(), 'audio') ? 'audio'
                   : (str_contains($this->media->getMimeType(), 'image') ? 'image' : 'file');

            $path = $this->media->store('chat', 'public');
            $url = url(Storage::url($path));

            Media::create([
                'url' => $url,
                'mime' => $mime,
                'mediable_id' => $createdMessage->id,
                'mediable_type' => Message::class,
            ]);
        }

        #scroll to bottom
        $this->dispatch('scroll-bottom');

        $this->reset('body', 'media');

        #push the message
        $this->loadedMessages->push($createdMessage->fresh('media'));

        #update the conversation model - for sorting in chatlist
        $this->conversation->updated_at=now();
        $this->conversation->save();

        #dispatch event 'refresh ' to chatlist 
        $this->dispatch('refresh')->to(ChatList::class);

        #broadcast new message
        $this->receiver->notify(new MessageSentNotification(
            auth()->user(),
            $createdMessage,
            $this->conversation
        ));
    }




    #[On('loadMore')]
    function loadMore()  {

        //dd('reached');

        #increment
        $this->paginate_var +=10;

        #call loadMessage
        $this->loadMessages();

        #dispatch event- update height
        $this->dispatch('update-height');
        
    }


    function loadMessages()  {

         #get count
         $count= Message::where('conversation_id',$this->conversation->id)->count();

         #skip and query

         $this->loadedMessages= Message::with('media')
                                ->where('conversation_id',$this->conversation->id)
                                ->skip($count- $this->paginate_var)
                                ->take($this->paginate_var)
                                ->get();


          return $this->loadedMessages;


    }

    function mount()  {

        $this->receiver= $this->conversation->getReceiver();

        $this->loadMessages();

        

    }

    public function render()
    {
        return view('livewire.chat.chat');
    }
}
