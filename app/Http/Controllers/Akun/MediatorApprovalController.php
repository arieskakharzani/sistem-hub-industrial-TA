<?php

namespace App\Http\Controllers\Akun;

use App\Http\Controllers\Controller;
use App\Models\Mediator;
use App\Models\User;
use App\Notifications\MediatorApprovedNotification;
use App\Notifications\MediatorRejectedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediatorApprovalController extends Controller
{
    /**
     * Display pending mediator approvals
     */
    public function index()
    {
        $user = Auth::user();

        // Pastikan user adalah kepala dinas
        if ($user->active_role !== 'kepala_dinas') {
            abort(403, 'Akses ditolak. Anda bukan kepala dinas.');
        }

        $pendingMediators = Mediator::with(['user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedMediators = Mediator::with(['user'])
            ->where('status', 'approved')
            ->orderBy('approved_at', 'desc')
            ->get();

        $pendingCount = Mediator::where('status', 'pending')->count();
        $approvedCount = Mediator::where('status', 'approved')->count();
        $rejectedCount = Mediator::where('status', 'rejected')->count();

        return view('akun.mediator-approval-index', compact('pendingMediators', 'approvedMediators', 'pendingCount', 'approvedCount', 'rejectedCount'));
    }

    /**
     * Preview SK file
     */
    public function preview($id)
    {
        $user = Auth::user();

        if ($user->active_role !== 'kepala_dinas') {
            abort(403, 'Akses ditolak.');
        }

        $mediator = Mediator::with(['user'])->findOrFail($id);

        // Allow preview for pending, approved, and rejected mediators
        if (!in_array($mediator->status, ['pending', 'approved', 'rejected'])) {
            abort(404, 'Mediator tidak dalam status yang valid.');
        }

        return view('mediator.preview', compact('mediator'));
    }

    /**
     * Approve mediator
     */
    public function approve(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->active_role !== 'kepala_dinas') {
            abort(403, 'Akses ditolak.');
        }

        $mediator = Mediator::with(['user'])->findOrFail($id);

        if ($mediator->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Mediator tidak dalam status pending.');
        }

        try {
            // Update mediator status
            $mediator->update([
                'status' => 'approved',
                'approved_by' => $user->user_id,
                'approved_at' => now(),
            ]);

            // Aktifkan user account
            $mediator->user->update([
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Kirim notifikasi ke mediator
            $mediator->user->notify(new MediatorApprovedNotification($mediator));

            Log::info('Mediator approved', [
                'mediator_id' => $mediator->mediator_id,
                'nama_mediator' => $mediator->nama_mediator,
                'approved_by' => $user->user_id,
            ]);

            return redirect()->route('mediator.approval.index')
                ->with('success', 'Mediator berhasil disetujui. Kredensial login telah dikirim ke email.');
        } catch (\Exception $e) {
            Log::error('Error approving mediator', [
                'mediator_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyetujui mediator.');
        }
    }

    /**
     * Reject mediator
     */
    public function reject(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->active_role !== 'kepala_dinas') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi',
            'rejection_reason.max' => 'Alasan penolakan maksimal 1000 karakter',
        ]);

        $mediator = Mediator::with(['user'])->findOrFail($id);

        if ($mediator->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Mediator tidak dalam status pending.');
        }

        try {
            // Update mediator status
            $mediator->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'rejection_date' => now(),
            ]);

            // Kirim email langsung ke mediator
            try {
                \Mail::send('emails.mediator-rejected', [
                    'mediator' => $mediator,
                    'registerUrl' => route('mediator.register')
                ], function ($message) use ($mediator) {
                    $message->to($mediator->user->email)
                        ->subject('Registrasi Mediator Ditolak - SIPPPHI');
                });

                Log::info('Rejection email sent successfully', [
                    'mediator_email' => $mediator->user->email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send rejection email', [
                    'mediator_email' => $mediator->user->email,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('Mediator rejected', [
                'mediator_id' => $mediator->mediator_id,
                'nama_mediator' => $mediator->nama_mediator,
                'rejected_by' => $user->user_id,
                'rejection_reason' => $request->rejection_reason,
            ]);

            return redirect()->route('mediator.approval.index')
                ->with('success', 'Mediator berhasil ditolak. Notifikasi telah dikirim ke email.');
        } catch (\Exception $e) {
            Log::error('Error rejecting mediator', [
                'mediator_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menolak mediator.');
        }
    }

    /**
     * Download SK file
     */
    public function downloadSk($id)
    {
        $user = Auth::user();

        if ($user->active_role !== 'kepala_dinas') {
            abort(403, 'Akses ditolak.');
        }

        $mediator = Mediator::findOrFail($id);

        if (!$mediator->sk_file_path || !Storage::disk('public')->exists($mediator->sk_file_path)) {
            abort(404, 'File SK tidak ditemukan.');
        }

        return Storage::disk('public')->download($mediator->sk_file_path, $mediator->sk_file_name);
    }
}
