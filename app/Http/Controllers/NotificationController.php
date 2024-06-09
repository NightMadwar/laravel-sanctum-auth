<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // POST: Create a new notification
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'drug_id' => 'required|exists:drugs,id',
            'reminder' => 'required|date_format:Y-m-d H:i:s',
            'amount' => 'required|numeric',
        ]);

        // Create the notification
        $notification = Notification::create([
            'user_id' => Auth::user()->id,
            'drug_id' => $request->drug_id,
            'reminder' => $request->reminder,
            'amount' => $request->amount,
        ]);

        return response()->json($notification, 201);
    }

    // GET: Retrieve all notifications for a user
    public function index(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $notifications = Notification::with(['drug','user'])->where('user_id', Auth::user()->id)->get();

        return response()->json($notifications);
    }
}
