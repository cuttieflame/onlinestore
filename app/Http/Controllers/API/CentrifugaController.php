<?php
declare(strict_types = 1);

namespace App\Http\Controllers\API;

use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use denis660\Centrifugo\Centrifugo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class CentrifugaController extends Controller
{
    public function connect()
    {
        return new JsonResponse([
        'result' => [
            'user' => (string) Auth::user()->id,
            'channels' => ["personal:#".Auth::user()->id],
        ]
    ]);

    }
//${this.$route.params.id.replace(/[^0-9]/g,"")}
    public function example(Centrifugo $centrifugo,Request $request,$id)
    {
        $message = Message::create([
            'user_id' => Auth::user()->id,
            'message' => $request->input('message'),
            'room_id' => $id,
        ]);
        $room = Room::with('users')->find($id);

        $channels = [];
        foreach ($room->users as $user) {
            $channels[] = "personal:#" . $user->id;
        }

        $centrifugo->broadcast($channels, [
            "text" => $message->message,
            "createdAt" => $message->created_at->toDateTimeString(),
            "roomId" => $id,
            "senderId" => Auth::user()->id,
            "senderName" => Auth::user()->name,
        ]);
        return response()->json($centrifugo);
//        $centrifugo->publish('news', ['message' => 'Hello world']);
//
//        // Generate connection token
//        $token = $centrifugo->generateConnectionToken((string)Auth::id(), 0, [
//            'name' => Auth::user()->name,
//        ]);
//
//        // Generate private channel token
//        $apiSign = $centrifugo->generatePrivateChannelToken((string)Auth::id(), 'channel', time() + 5 * 60, [
//            'name' => Auth::user()->name,
//        ]);
//
//        //Get a list of currently active channels.
//        $centrifugo->channels();
//
//        //Get channel presence information (all clients currently subscribed on this channel).
//        $centrifugo->presence('news');

    }
}
