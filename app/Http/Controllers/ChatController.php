<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChatController extends Controller {
    public function index()
    {
        return view('chat.index');
    }

    public function init(Request $request)
    {
        $user = User::authUser((string) $request->header('apiToken'));
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $payload = $request->validate([
            'user_id' => ['nullable', 'integer', 'min:1'],
        ]);

        $users = $this->resolveAvailableUsers((int) $user->id, (int) ($user->client_id ?? 0));
        $selectedUserId = (int) (($payload['user_id'] ?? 0) ?: ($users[0]['id'] ?? 0));
        $messages = $selectedUserId > 0 ? $this->getThread((int) $user->id, $selectedUserId) : [];

        return response()->json([
            'success' => true,
            'users' => $users,
            'selected_user_id' => $selectedUserId,
            'messages' => $messages,
        ]);
    }

    public function thread(Request $request)
    {
        $user = User::authUser((string) $request->header('apiToken'));
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        $payload = $request->validate([
            'user_id' => ['required', 'integer', 'min:1'],
        ]);

        $targetUserId = (int) $payload['user_id'];
        if (!$this->isAllowedChatUser((int) $user->id, (int) ($user->client_id ?? 0), $targetUserId)) {
            return response()->json(['success' => false, 'message' => 'User not allowed for chat.'], 422);
        }

        return response()->json([
            'success' => true,
            'messages' => $this->getThread((int) $user->id, $targetUserId),
        ]);
    }

    public function send(Request $request)
    {
        $user = User::authUser((string) $request->header('apiToken'));
        if (!$user || is_string($user)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized user.'], 401);
        }

        if (!Schema::hasTable('chat_messages')) {
            return response()->json(['success' => false, 'message' => 'chat_messages table not found. Run migration first.'], 422);
        }

        $payload = $request->validate([
            'user_id' => ['required', 'integer', 'min:1'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $targetUserId = (int) $payload['user_id'];
        if (!$this->isAllowedChatUser((int) $user->id, (int) ($user->client_id ?? 0), $targetUserId)) {
            return response()->json(['success' => false, 'message' => 'User not allowed for chat.'], 422);
        }

        DB::table('chat_messages')->insert([
            'sender_id' => (int) $user->id,
            'receiver_id' => $targetUserId,
            'client_id' => isset($user->client_id) ? (int) $user->client_id : null,
            'message' => trim((string) $payload['message']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
            'messages' => $this->getThread((int) $user->id, $targetUserId),
        ]);
    }

    private function resolveAvailableUsers(int $authUserId, int $clientId): array
    {
        if (!Schema::hasTable('users')) {
            return [];
        }

        $query = DB::table('users')->where('id', '!=', $authUserId);
        if (Schema::hasColumn('users', 'active')) {
            $query->where('active', 1);
        }

        if ($clientId > 0 && Schema::hasColumn('users', 'client_id')) {
            // Supports your requirement: Auth::user()->client_id == users.id
            // and same-client chat users: Auth::user()->client_id == users.client_id.
            $query->where(function ($sub) use ($clientId) {
                $sub->where('id', $clientId)
                    ->orWhere('client_id', $clientId);
            });
        }

        return $query
            ->orderBy('name')
            ->limit(200)
            ->get(['id', 'name', 'email'])
            ->map(function ($row) {
                return [
                    'id' => (int) ($row->id ?? 0),
                    'name' => (string) ($row->name ?? 'User'),
                    'email' => (string) ($row->email ?? ''),
                ];
            })
            ->all();
    }

    private function isAllowedChatUser(int $authUserId, int $clientId, int $targetUserId): bool
    {
        if ($targetUserId <= 0 || $targetUserId === $authUserId || !Schema::hasTable('users')) {
            return false;
        }

        $query = DB::table('users')->where('id', $targetUserId);
        if (Schema::hasColumn('users', 'active')) {
            $query->where('active', 1);
        }

        if ($clientId > 0 && Schema::hasColumn('users', 'client_id')) {
            $query->where(function ($sub) use ($clientId) {
                $sub->where('id', $clientId)
                    ->orWhere('client_id', $clientId);
            });
        }

        return $query->exists();
    }

    private function getThread(int $authUserId, int $targetUserId): array
    {
        if (!Schema::hasTable('chat_messages')) {
            return [];
        }

        return DB::table('chat_messages')
            ->where(function ($q) use ($authUserId, $targetUserId) {
                $q->where('sender_id', $authUserId)
                    ->where('receiver_id', $targetUserId);
            })
            ->orWhere(function ($q) use ($authUserId, $targetUserId) {
                $q->where('sender_id', $targetUserId)
                    ->where('receiver_id', $authUserId);
            })
            ->orderBy('id')
            ->limit(1000)
            ->get(['id', 'sender_id', 'receiver_id', 'message', 'created_at'])
            ->map(function ($row) use ($authUserId) {
                $createdAt = $row->created_at ? date('d M Y h:i A', strtotime((string) $row->created_at)) : '';

                return [
                    'id' => (int) ($row->id ?? 0),
                    'sender_id' => (int) ($row->sender_id ?? 0),
                    'receiver_id' => (int) ($row->receiver_id ?? 0),
                    'message' => (string) ($row->message ?? ''),
                    'created_at' => $createdAt,
                    'is_me' => (int) ($row->sender_id ?? 0) === $authUserId,
                ];
            })
            ->all();
    }
}
