<?php

namespace App\Http\Controllers;

use App\Events\EnquiryAlertEvent;
use App\Models\Enquiry;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class EnquiryController extends Controller
{
    /* ═══════════════════════════════════════════
     *  BUYER — Submit enquiry (Ajax)
     * ═══════════════════════════════════════════ */
    public function store(Request $request, Property $property)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:20',
            'message' => 'nullable|string|max:1000',
        ]);

        // Prevent duplicate pending enquiry from same email on same property
        $exists = Enquiry::where('property_id', $property->id)
                         ->where('email', $data['email'])
                         ->whereIn('status', ['new', 'read'])
                         ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending enquiry for this property.',
            ], 422);
        }

        $enquiry = Enquiry::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'phone'       => $data['phone'],
            'message'     => $data['message'] ?? null,
            'property_id' => $property->id,
            'seller_id'   => $property->user_id,          // adjust to your FK name
            'buyer_id'    => Auth::id(),                  // null if guest
            'status'      => 'new',
        ]);

        // ── Optional: send email notification to seller ──
        if ($property->seller?->email) {
            Mail::raw("New enquiry received for {$property->title}.", function ($m) use ($property) {
                $m->to($property->seller->email)->subject('New Property Enquiry');
            });
        }
        event(new EnquiryAlertEvent($property->user_id, [
            'property_id' => $property->id,
            'property_title' => $property->title,
            'enquiry_id' => $enquiry->id,
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Your enquiry has been sent! The seller will contact you soon.',
            'enquiry' => [
                'id'         => $enquiry->id,
                'created_at' => $enquiry->created_at->diffForHumans(),
            ],
        ]);
    }

    /* ═══════════════════════════════════════════
     *  BUYER — List my enquiries
     * ═══════════════════════════════════════════ */
    public function buyerIndex()
    {
        $enquiries = Enquiry::with('property')
            ->forBuyer(Auth::id())
            ->latest()
            ->paginate(10);

        return view('buyer.enquiries.index', compact('enquiries'));
    }

    /* ═══════════════════════════════════════════
     *  SELLER — List received enquiries
     * ═══════════════════════════════════════════ */
    public function sellerIndex()
    {
        $enquiries = Enquiry::with(['property', 'buyer'])
            ->forSeller(Auth::id())
            ->latest()
            ->paginate(10);

        $newCount = Enquiry::forSeller(Auth::id())->new()->count();

        return view('seller.enquiries.index', compact('enquiries', 'newCount'));
    }

    /* ═══════════════════════════════════════════
     *  SELLER — View single enquiry + mark read (Ajax)
     * ═══════════════════════════════════════════ */
    public function show(Enquiry $enquiry)
    {
        $this->authoriseSeller($enquiry);

        $enquiry->markAsRead();

        if (request()->expectsJson()) {
            return response()->json(['enquiry' => $enquiry->load('property')]);
        }

        return view('seller.enquiries.show', compact('enquiry'));
    }

    /* ═══════════════════════════════════════════
     *  SELLER — Reply to enquiry (Ajax)
     * ═══════════════════════════════════════════ */
    public function reply(Request $request, Enquiry $enquiry)
    {
        $this->authoriseSeller($enquiry);

        $data = $request->validate([
            'reply' => 'required|string|max:2000',
        ]);

        $enquiry->update([
            'reply'       => $data['reply'],
            'replied_at'  => now(),
            'status'      => 'replied',
        ]);

        // ── Optional: email the buyer ──
        // Mail::to($enquiry->email)->send(new EnquiryReplied($enquiry));

        return response()->json([
            'success'    => true,
            'message'    => 'Reply sent successfully.',
            'replied_at' => $enquiry->replied_at->format('d M Y, h:i A'),
        ]);
    }

    /* ═══════════════════════════════════════════
     *  SELLER — Update status (Ajax)
     * ═══════════════════════════════════════════ */
    public function updateStatus(Request $request, Enquiry $enquiry)
    {
        $this->authoriseSeller($enquiry);

        $request->validate([
            'status' => 'required|in:new,read,replied,closed',
        ]);

        $enquiry->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'status'  => $enquiry->status,
        ]);
    }

    /* ═══════════════════════════════════════════
     *  SELLER — Delete enquiry (Ajax)
     * ═══════════════════════════════════════════ */
    public function destroy(Enquiry $enquiry)
    {
        $this->authoriseSeller($enquiry);
        $enquiry->delete();

        return response()->json(['success' => true]);
    }

    /* ─── Private helpers ─── */

    private function authoriseSeller(Enquiry $enquiry): void
    {
        abort_unless($enquiry->seller_id === Auth::id(), 403);
    }
}