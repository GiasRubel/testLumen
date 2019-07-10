<?php


namespace App\Http\Controllers;


use App\Message;
use App\Product;
use App\User;
use function foo\func;
use Illuminate\Support\Facades\Auth;

class UserMessageController extends ApiController
{
    public function index()
    {
        $messages = Message::where(['receiver_id' => Auth::id(), 'parent_id' => 0])
            ->with(['user' => function ($user) {
                $user->select('id', 'username');
            }, 'user.profile' => function ($profile) {
                $profile->select('user_id', 'display_name', 'image');
            }, 'messageReplys' => function ($reply) {
                $reply->with(['user' => function ($user) {
                    $user->select('id', 'username');
                }, 'user.profile' => function ($profile) {
                    $profile->select('user_id', 'display_name', 'image');
                }]);
            }])
            ->get();
        return $this->showAll($messages);
    }

    /**
     * All interested/message user list depend on product author
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function userList()
    {
        $users = User::whereHas('messages', function ($messages) {
            $messages->where(['receiver_id' => Auth::id(), 'messageable_type' => 'App\Product']);
        })
            ->whereNotIn('id', [Auth::id()])
            ->with('profile')
            ->filter($this->filter)
            ->get()
            ->unique();

        return $this->showAll($users);
    }

    /**
     * Sender wise message list
     * @param $sender_id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function messageList($sender_id)
    {
        $messages = Product::whereHas('messages', function ($message) use ($sender_id) {
            $message->where(function ($query) use ($sender_id) {
                $query->where('parent_id', 0)
                    ->where('sender_id', $sender_id)
                    ->where('receiver_id', Auth::id());
            })
                ->orWhere(function ($query) use ($sender_id) {
                    $query->where('parent_id', 0)
                        ->where('sender_id', Auth::id())
                        ->where('receiver_id', $sender_id);
                });
        }
        )->with(['messages' => function ($message) use ($sender_id) {
            $message->where(function ($query) use ($sender_id) {
                $query->where('parent_id', 0)
                    ->where('sender_id', $sender_id)
                    ->where('receiver_id', Auth::id());
            })
                ->orWhere(function ($query) use ($sender_id) {
                    $query->where('parent_id', 0)
                        ->where('sender_id', Auth::id())
                        ->where('receiver_id', $sender_id);
                });
        },'messages.user.profile', 'messages.messageReplies','messages.messageReplies.user.profile'])
            ->filter($this->filter)
            ->get();


        return $this->showAll($messages);
    }
}