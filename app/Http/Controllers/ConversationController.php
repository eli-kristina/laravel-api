<?php

namespace App\Http\Controllers;

use Validator;

use App\Models\Conversations;
use App\Models\ConversationDetails;
use Illuminate\Http\Request;


class ConversationController extends Controller
{
    /**
     * Function to get list of chat history
     * 
     * GET: /api/conversations
     * HEADERS:
     * - Authorization  Bearer {token}
     */
    public function index(Request $request) {
        $user = $request->user();
        $is_staff = $this->checkIsStaff($user);
        $chats = Conversations::join('users', 'users.id', '=', 'conversations.to_user_id');
        
        if (!$is_staff) {
            $chats = $chats->where('from_user_id', $user->id)->orWhere('to_user_id', $user->id);
        }
            
        $chats = $chats->get(['conversations.id', 'conversations.from_user_id', 'conversations.to_user_id', 'users.email', 'conversations.last_message', 'conversations.created_at']);
        
        if (!empty($chats)) {
            $data = [];
            
            foreach($chats as $val) {
                $data[] = [
                    'conversation_id' => $val->id,
                    'from_user_id'  => $val->from_user_id,
                    'to_user_id'    => $val->to_user_id,
                    'email'         => $val->email,
                    'last_message'  => $val->last_message,
                    'created_at'    => $val->created_at
                ];
            }
            
            return response()->json(['error' => 0, 'message' => '', 'data' => $data], 200);
        }
        
        return response()->json(['error' => 1, 'message' => 'chat history not found'], 404);
    }
    
    /**
     * Function to get specific chat by id
     * 
     * GET: /api/conversations/{id}
     * HEADERS:
     * - Authorization  Bearer {token}
     */
    public function details($id, Request $request) {
        $user = $request->user();
        $is_staff = $this->checkIsStaff($user);
        
        $chats = ConversationDetails::where('conversation_id', $id);
        
        if (!$is_staff) {
            $chats = $chats->join('conversations', 'conversation_details.conversation_id', '=', 'conversations.id')
                    ->where('conversations.from_user_id', $user->id)->orWhere('conversations.to_user_id', $user->id);
        }
        
        $chats = $chats->get(['conversation_details.id', 'conversation_details.sender_user_id', 'conversation_details.message', 'conversation_details.created_at']);
        
        if (!empty($chats)) {
            $data = [];
            
            foreach($chats as $val) {
                $data[] = [
                    'id'                => $val->id,
                    'sender_user_id'    => $val->sender_user_id,
                    'message'           => $val->message,
                    'created_at'        => $val->created_at
                ];
            }
            
            return response()->json(['error' => 0, 'message' => '', 'data' => $data], 200);
        }
        
        return response()->json(['error' => 1, 'message' => 'chat details not found'], 404);
    }
    
    /**
     * Function to send message
     * - send message from customer to customer
     * - send message from customer to staff
     * - send message from staff to customer
     * - send message from staff to staff
     * 
     * POST: /api/conversations
     * HEADERS:
     * - Authorization  Bearer {token}
     * PARAMS:
     * - conversation_id    int (optional)
     * - to_user_id         int (required)
     * - message            string (required)
     */
    public function create(Request $request) {
        $user = $request->user();
        $post = $request->input();
        $id = 0;
        
        if ($this->validate_send_message($post)->fails()) {
            return response()->json(['error' => 1, 'message' => 'params invalid', 'error_params' => $this->validate_send_message($post)->errors()], 500);
        } else {
            if ($user->id == $post['to_user_id']) {
                return response()->json(['error' => 1, 'message' => 'message cannot sent to your self'], 500);
            }
            
            if (empty($post['conversation_id'])) {
                $exist = Conversations::where('from_user_id', $user->id)->where('to_user_id', $post['to_user_id'])->first('id');
                
                if (empty($exist)) {
                    $exist = Conversations::where('from_user_id', $post['to_user_id'])->where('to_user_id', $user->id)->first('id');
                }
            }
            
            if (empty($exist)) {
                $data = [
                    'from_user_id'  => $user->id,
                    'to_user_id'    => $post['to_user_id'],
                    'last_message'  => $post['message']
                ];
                
                $chats = Conversations::create($data);
                $id = $chats->id;
            } else {
                $id = $exist->id;
                $data = [
                    'last_message'  => $post['message']
                ];
                
                Conversations::where('id', $id)->update($data);
            }
            
            $data = [
                'conversation_id'   => $id,
                'sender_user_id'    => $user->id,
                'message'           => $post['message']
            ];
            
            ConversationDetails::create($data);
        }
        
        return response()->json(['error' => 0, 'message' => 'message sent'], 200);
    }
    
    protected function validate_send_message($data) {
        return Validator::make($data, [
            'to_user_id'    => ['required'],
            'message'       => ['required','string','max:255']
        ]);
    }
}