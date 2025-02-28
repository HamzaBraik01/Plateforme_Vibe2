<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demande;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();

        // Liste des amis actuels
        $friends = $user->friends()->get();

        // Demandes d'amis reçues en attente
        $friendRequests = Demande::where('receiver_id', $user->id)
            ->pending()
            ->with('sender')
            ->get();

        // Suggestions d'utilisateurs (exclut les amis actuels et soi-même)
        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('pseudo', 'like', "%{$search}%");
        })
        ->where('id', '!=', $user->id)
        ->whereNotIn('id', $user->friends()->pluck('users.id'))
        ->paginate(10);

        return view('friends.index', compact('friends', 'friendRequests', 'users', 'search'));
    }

    public function sendRequest(Request $request, $receiver_id)
    {
        $user = Auth::user();
        $receiver = User::findOrFail($receiver_id);

        if ($user->id === $receiver->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous ajouter vous-même.');
        }
        if ($user->isFriendWith($receiver->id)) {
            return redirect()->back()->with('error', 'Vous êtes déjà amis.');
        }
        if ($user->hasPendingRequestTo($receiver->id)) {
            return redirect()->back()->with('error', 'Une demande est déjà en attente.');
        }

        Demande::create([
            'user_id' => $user->id,
            'receiver_id' => $receiver->id,
            'statut' => Demande::STATUT_PENDING
        ]);

        return redirect()->back()->with('success', 'Demande d\'ami envoyée avec succès !');
    }

    public function acceptRequest($id)
    {
        $user = Auth::user();
        $request = Demande::where('receiver_id', $user->id)
            ->where('id', $id)
            ->pending()
            ->firstOrFail();

        $request->update(['statut' => Demande::STATUT_ACCEPTED]);

        return redirect()->back()->with('success', 'Demande d\'ami acceptée !');
    }

    public function rejectRequest($id)
    {
        $user = Auth::user();
        $request = Demande::where('receiver_id', $user->id)
            ->where('id', $id)
            ->pending()
            ->firstOrFail();

        $request->delete();

        return redirect()->back()->with('success', 'Demande d\'ami refusée.');
    }

    public function removeFriend($friend_id)
    {
        $user = Auth::user();

        $friendship = Demande::where(function ($query) use ($user, $friend_id) {
            $query->where('user_id', $user->id)
                  ->where('receiver_id', $friend_id);
        })->orWhere(function ($query) use ($user, $friend_id) {
            $query->where('user_id', $friend_id)
                  ->where('receiver_id', $user->id);
        })->accepted()->first();

        if ($friendship) {
            $friendship->delete();
            return redirect()->back()->with('success', 'Ami supprimé avec succès.');
        }

        return redirect()->back()->with('error', 'Aucune amitié trouvée à supprimer.');
    }
}
