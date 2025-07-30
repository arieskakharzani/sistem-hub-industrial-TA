<?php

namespace App\Http\Controllers;

use App\Models\Anjuran;
use App\Models\Pelapor;
use App\Models\Terlapor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AnjuranResponseNotification;

class AnjuranResponseController extends Controller
{
    /**
     * Show anjuran list for pelapor
     */
    public function indexPelapor()
    {
        $user = Auth::user();

        if ($user->active_role !== 'pelapor') {
            return back()->with('error', 'Hanya pelapor yang dapat mengakses halaman ini');
        }

        $pelapor = Pelapor::where('user_id', $user->user_id)->first();

        if (!$pelapor) {
            return back()->with('error', 'Data pelapor tidak ditemukan');
        }

        // Ambil anjuran yang ditujukan untuk pelapor ini
        $anjurans = Anjuran::with(['dokumenHI.pengaduan.mediator'])
            ->whereHas('dokumenHI.pengaduan', function ($query) use ($pelapor) {
                $query->where('pelapor_id', $pelapor->pelapor_id);
            })
            ->where('status_approval', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('anjuran-response.index-pelapor', compact('anjurans', 'user'));
    }

    /**
     * Show anjuran list for terlapor
     */
    public function indexTerlapor()
    {
        $user = Auth::user();

        if ($user->active_role !== 'terlapor') {
            return back()->with('error', 'Hanya terlapor yang dapat mengakses halaman ini');
        }

        $terlapor = Terlapor::where('user_id', $user->user_id)->first();

        if (!$terlapor) {
            return back()->with('error', 'Data terlapor tidak ditemukan');
        }

        // Ambil anjuran yang ditujukan untuk terlapor ini
        $anjurans = Anjuran::with(['dokumenHI.pengaduan.mediator'])
            ->whereHas('dokumenHI.pengaduan', function ($query) use ($terlapor) {
                $query->where('terlapor_id', $terlapor->terlapor_id);
            })
            ->where('status_approval', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('anjuran-response.index-terlapor', compact('anjurans', 'user'));
    }

    /**
     * Show anjuran detail for response
     */
    public function show($id)
    {
        $user = Auth::user();
        $anjuran = Anjuran::with(['dokumenHI.pengaduan.mediator', 'dokumenHI.pengaduan.pelapor', 'dokumenHI.pengaduan.terlapor'])
            ->findOrFail($id);

        // Check if user has access to this anjuran
        $hasAccess = false;
        $userRole = null;

        if ($user->active_role === 'pelapor') {
            $pelapor = Pelapor::where('user_id', $user->user_id)->first();
            if ($pelapor && $anjuran->dokumenHI->pengaduan->pelapor_id === $pelapor->pelapor_id) {
                $hasAccess = true;
                $userRole = 'pelapor';
            }
        } elseif ($user->active_role === 'terlapor') {
            $terlapor = Terlapor::where('user_id', $user->user_id)->first();
            if ($terlapor && $anjuran->dokumenHI->pengaduan->terlapor_id === $terlapor->terlapor_id) {
                $hasAccess = true;
                $userRole = 'terlapor';
            }
        }

        if (!$hasAccess) {
            return back()->with('error', 'Anda tidak memiliki akses ke anjuran ini');
        }

        return view('anjuran-response.show', compact('anjuran', 'user', 'userRole'));
    }

    /**
     * Submit response for anjuran
     */
    public function submitResponse(Request $request, $id)
    {
        $user = Auth::user();
        $anjuran = Anjuran::findOrFail($id);

        // Validate access
        $hasAccess = false;
        $userRole = null;

        if ($user->active_role === 'pelapor') {
            $pelapor = Pelapor::where('user_id', $user->user_id)->first();
            if ($pelapor && $anjuran->dokumenHI->pengaduan->pelapor_id === $pelapor->pelapor_id) {
                $hasAccess = true;
                $userRole = 'pelapor';
            }
        } elseif ($user->active_role === 'terlapor') {
            $terlapor = Terlapor::where('user_id', $user->user_id)->first();
            if ($terlapor && $anjuran->dokumenHI->pengaduan->terlapor_id === $terlapor->terlapor_id) {
                $hasAccess = true;
                $userRole = 'terlapor';
            }
        }

        if (!$hasAccess) {
            return back()->with('error', 'Anda tidak memiliki akses ke anjuran ini');
        }

        // Check if deadline has passed
        if ($anjuran->isResponseDeadlinePassed()) {
            return back()->with('error', 'Batas waktu respon telah berakhir');
        }

        // Check if already responded
        $responseField = $userRole === 'pelapor' ? 'response_pelapor' : 'response_terlapor';
        if ($anjuran->$responseField !== 'pending') {
            return back()->with('error', 'Anda sudah memberikan respon untuk anjuran ini');
        }

        // Validate request
        $request->validate([
            'response' => 'required|in:setuju,tidak_setuju',
            'note' => 'nullable|string|max:1000'
        ]);

        // Update response
        if ($userRole === 'pelapor') {
            $anjuran->response_pelapor = $request->response;
            $anjuran->response_note_pelapor = $request->note;
            $anjuran->response_at_pelapor = now();
        } else {
            $anjuran->response_terlapor = $request->response;
            $anjuran->response_note_terlapor = $request->note;
            $anjuran->response_at_terlapor = now();
        }

        $anjuran->updateOverallResponseStatus();
        $anjuran->save();

        // Notify mediator about the response
        $this->notifyMediator($anjuran, $userRole, $request->response);

        return redirect()->route('anjuran-response.index-' . $userRole)
            ->with('success', 'Respon Anda telah berhasil disimpan');
    }

    /**
     * Notify mediator about response
     */
    private function notifyMediator($anjuran, $userRole, $response)
    {
        $mediator = $anjuran->mediator();

        if ($mediator && $mediator->user) {
            $mediator->user->notify(new AnjuranResponseNotification($anjuran, $userRole, $response));
        }
    }
}
