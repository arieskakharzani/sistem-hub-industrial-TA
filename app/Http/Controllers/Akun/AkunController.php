<?php

namespace App\Http\Controllers\Akun;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTerlaporRequest;
use App\Services\TerlaporService;
use App\Models\Terlapor;
use App\Models\Pelapor;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AkunController extends Controller
{
    protected $terlaporService;

    public function __construct(TerlaporService $terlaporService)
    {
        $this->terlaporService = $terlaporService;
    }

    // public function index()
    // {
    //     // Debug info
    //     dd([
    //         'user' => Auth::user(),
    //         'user_role' => Auth::user()->role ?? 'no role',
    //         'mediator_exists' => Auth::user()->mediator ?? 'no mediator'
    //     ]);
    // }
    /**
     * Display a listing of both pelapor and terlapor accounts
     */


    public function index()
    {

        try {
            // Pastikan user ada
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
            }

            // Pastikan user adalah mediator
            if ($user->active_role !== 'mediator') {
                abort(403, 'Akses ditolak. Anda bukan mediator.');
            }

            // Pastikan relasi mediator ada
            if (!$user->mediator) {
                // Fallback: return empty collections dengan stats default
                $terlapors = collect([]);
                $pelapors = collect([]);
                $pelaporStats = ['total' => 0, 'active' => 0, 'inactive' => 0, 'this_month' => 0];
                $terlaporStats = ['total' => 0, 'active' => 0, 'inactive' => 0, 'this_month' => 0];

                return view('akun.index', compact('terlapors', 'pelapors', 'pelaporStats', 'terlaporStats'))
                    ->with('error', 'Data mediator tidak ditemukan.');
            }

            // Get data terlapor dengan error handling
            try {
                $terlapors = Terlapor::with(['user'])
                    ->where('created_by_mediator_id', $user->mediator->mediator_id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10, ['*'], 'terlapor_page');
            } catch (\Exception $e) {
                Log::error('Error getting terlapor data: ' . $e->getMessage());
                $terlapors = collect([]); // Fallback ke empty collection
            }

            // Get data pelapor dengan error handling
            try {
                $pelapors = Pelapor::with(['user'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10, ['*'], 'pelapor_page');
            } catch (\Exception $e) {
                Log::error('Error getting pelapor data: ' . $e->getMessage());
                $pelapors = collect([]); // Fallback ke empty collection
            }

            // Calculate statistics dengan error handling
            try {
                // Statistik Pelapor
                $totalPelapor = Pelapor::count();
                $activePelapor = 0;
                $inactivePelapor = 0;
                $thisMonthPelapor = 0;

                try {
                    $activePelapor = Pelapor::whereHas('user', function ($q) {
                        $q->where('is_active', true);
                    })->count();
                } catch (\Exception $e) {
                    Log::error('Error counting active pelapor: ' . $e->getMessage());
                }

                try {
                    $inactivePelapor = Pelapor::whereHas('user', function ($q) {
                        $q->where('is_active', false);
                    })->count();
                } catch (\Exception $e) {
                    Log::error('Error counting inactive pelapor: ' . $e->getMessage());
                }

                try {
                    $thisMonthPelapor = Pelapor::where('created_at', '>=', now()->startOfMonth())->count();
                } catch (\Exception $e) {
                    Log::error('Error counting this month pelapor: ' . $e->getMessage());
                }

                $pelaporStats = [
                    'total' => $totalPelapor,
                    'active' => $activePelapor,
                    'inactive' => $inactivePelapor,
                    'this_month' => $thisMonthPelapor
                ];

                // Statistik Terlapor
                $totalTerlapor = 0;
                $activeTerlapor = 0;
                $inactiveTerlapor = 0;
                $thisMonthTerlapor = 0;

                try {
                    $totalTerlapor = Terlapor::count();
                } catch (\Exception $e) {
                    Log::error('Error counting total terlapor: ' . $e->getMessage());
                }

                try {
                    $activeTerlapor = Terlapor::where('status', 'active')->count();
                } catch (\Exception $e) {
                    Log::error('Error counting active terlapor: ' . $e->getMessage());
                }

                try {
                    $inactiveTerlapor = Terlapor::where('status', 'inactive')->count();
                } catch (\Exception $e) {
                    Log::error('Error counting inactive terlapor: ' . $e->getMessage());
                }

                try {
                    $thisMonthTerlapor = Terlapor::where('created_at', '>=', now()->startOfMonth())->count();
                } catch (\Exception $e) {
                    Log::error('Error counting this month terlapor: ' . $e->getMessage());
                }

                $terlaporStats = [
                    'total' => $totalTerlapor,
                    'active' => $activeTerlapor,
                    'inactive' => $inactiveTerlapor,
                    'this_month' => $thisMonthTerlapor
                ];
            } catch (\Exception $e) {
                Log::error('Error calculating stats: ' . $e->getMessage());
                $pelaporStats = ['total' => 0, 'active' => 0, 'inactive' => 0, 'this_month' => 0];
                $terlaporStats = ['total' => 0, 'active' => 0, 'inactive' => 0, 'this_month' => 0];
            }

            return view('akun.index', compact('terlapors', 'pelapors', 'pelaporStats', 'terlaporStats'));
        } catch (\Exception $e) {
            Log::error('Error in AkunController@index: ' . $e->getMessage());

            // Fallback response
            $terlapors = collect([]);
            $pelapors = collect([]);
            $pelaporStats = ['total' => 0, 'active' => 0, 'inactive' => 0, 'this_month' => 0];
            $terlaporStats = ['total' => 0, 'active' => 0, 'inactive' => 0, 'this_month' => 0];

            return view('akun.index', compact('terlapors', 'pelapors', 'pelaporStats', 'terlaporStats'))
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new terlapor account
     * @param int|null $pengaduan_id Optional pengaduan ID for auto-fill
     */
    public function create($pengaduan_id = null)
    {
        try {
            $user = Auth::user();
            if (!$user || $user->active_role !== 'mediator') {
                return redirect()->route('mediator.akun.index')->with('error', 'Akses ditolak');
            }

            $pengaduan = null;

            if ($pengaduan_id) {
                // Ambil data pengaduan dengan relasi yang diperlukan
                $pengaduan = Pengaduan::with(['pelapor', 'mediator', 'terlapor'])
                    ->findOrFail($pengaduan_id);

                // Cek apakah pengaduan sudah terhubung dengan akun terlapor
                if ($pengaduan->terlapor_id && $pengaduan->terlapor) {
                    return redirect()->route('mediator.akun.show', $pengaduan->terlapor->terlapor_id)
                        ->with('info', 'Akun terlapor untuk pengaduan ini sudah ada.');
                }

                // Optional: Cek apakah mediator yang login memiliki akses ke pengaduan ini
                if ($pengaduan->mediator_id && $pengaduan->mediator_id !== $user->mediator->mediator_id) {
                    return redirect()->route('mediator.akun.index')
                        ->with('error', 'Anda tidak memiliki akses ke pengaduan ini.');
                }
            }

            return view('akun.create', compact('pengaduan'));
        } catch (\Exception $e) {
            Log::error('Error in AkunController@create: ' . $e->getMessage());
            return redirect()->route('mediator.akun.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created terlapor account
     */
    public function store(CreateTerlaporRequest $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            if ($user->active_role !== 'mediator') {
                abort(403, 'Akses ditolak. Anda bukan mediator.');
            }

            if (!$user->mediator) {
                return redirect()->route('dashboard')->with('error', 'Data mediator tidak ditemukan.');
            }

            // Cek apakah pengaduan sudah terhubung dengan terlapor lain (jika ada pengaduan_id)
            if ($request->pengaduan_id) {
                $pengaduan = Pengaduan::findOrFail($request->pengaduan_id);
                if ($pengaduan->terlapor_id) {
                    return redirect()->back()->withInput()
                        ->with('error', 'Pengaduan ini sudah terhubung dengan akun terlapor lain.');
                }
            }

            // Prepare data untuk service
            $data = [
                'nama_terlapor' => $request->nama_terlapor,
                'alamat_kantor_cabang' => $request->alamat_kantor_cabang,
                'email' => $request->email_terlapor,
                'email_terlapor' => $request->email_terlapor,
                'no_hp_terlapor' => $request->no_hp_terlapor,
                'pengaduan_id' => $request->pengaduan_id,
            ];

            // Buat akun terlapor
            $result = $this->terlaporService->createTerlaporAccount($data, $user->mediator->mediator_id);

            // Handle berbagai status hasil
            switch ($result['status']) {
                case 'existing_pelapor_updated':
                    $message = 'Email sudah terdaftar sebagai pelapor. Role terlapor telah ditambahkan ke akun yang sama.';
                    break;
                case 'existing_updated':
                    $message = 'Akun terlapor berhasil diperbarui.';
                    break;
                case 'new_created':
                    $message = 'Akun terlapor berhasil dibuat. Password sementara telah dikirim ke email terlapor.';
                    break;
                default:
                    $message = 'Akun terlapor berhasil dibuat/diperbarui.';
            }

            // Jika ada pengaduan_id, redirect ke halaman pengaduan
            if ($request->pengaduan_id) {
                return redirect()->route('pengaduan.show', $request->pengaduan_id)
                    ->with('success', $message);
            }

            // Jika tidak ada pengaduan_id, redirect ke halaman detail terlapor
            return redirect()->route('mediator.akun.show', $result['terlapor']->terlapor_id)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error in AkunController@store: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified terlapor account
     */
    public function show($id)
    {
        try {
            $user = Auth::user();
            if (!$user || $user->active_role !== 'mediator' || !$user->mediator) {
                return redirect()->route('mediator.akun.index')->with('error', 'Akses ditolak');
            }

            $terlapor = Terlapor::with('user', 'mediator')
                ->where('terlapor_id', $id)
                ->first();

            if (!$terlapor) {
                return redirect()->route('mediator.akun.index')
                    ->with('error', 'Data terlapor tidak ditemukan');
            }

            $canManage = $terlapor->created_by_mediator_id === $user->mediator->mediator_id;

            return view('akun.show', compact('terlapor', 'canManage'));
        } catch (\Exception $e) {
            Log::error('Error in AkunController@show: ' . $e->getMessage());
            return redirect()->route('mediator.akun.index')->with('error', 'Terjadi kesalahan');
        }
    }

    /**
     * Display the specified pelapor account
     */
    public function showPelapor($id)
    {
        try {
            $user = Auth::user();
            if (!$user || $user->active_role !== 'mediator') {
                return redirect()->route('mediator.akun.index')->with('error', 'Akses ditolak');
            }

            $pelapor = Pelapor::with(['user', 'pengaduan'])
                ->where('pelapor_id', $id)
                ->first();

            if (!$pelapor) {
                return redirect()->route('mediator.akun.index')
                    ->with('error', 'Data pelapor tidak ditemukan');
            }

            return view('akun.show-pelapor', compact('pelapor'));
        } catch (\Exception $e) {
            Log::error('Error in AkunController@showPelapor: ' . $e->getMessage());
            return redirect()->route('mediator.akun.index')->with('error', 'Terjadi kesalahan');
        }
    }

    /**
     * Deactivate terlapor account
     */
    public function deactivate($id)
    {
        try {
            $user = Auth::user();
            if (!$user || $user->active_role !== 'mediator') {
                return response()->json(['error' => 'Akses ditolak'], 403);
            }

            DB::beginTransaction();

            $terlapor = Terlapor::with('user')->findOrFail($id);

            // Update user status
            if ($terlapor->user) {
                // Jika user memiliki role terlapor saja, nonaktifkan user
                if (count($terlapor->user->roles) === 1) {
                    $terlapor->user->update(['is_active' => false]);
                } else {
                    // Jika multi-role, hapus role terlapor
                    $roles = array_diff($terlapor->user->roles, ['terlapor']);
                    $terlapor->user->update([
                        'roles' => array_values($roles),
                        'active_role' => $roles[0] ?? null
                    ]);
                }
            }

            // Update terlapor status
            $terlapor->update([
                'is_active' => false,
                'has_account' => false
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Akun terlapor berhasil dinonaktifkan',
                'terlapor' => $terlapor
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in AkunController@deactivate: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Activate terlapor account
     */
    public function activate($id)
    {
        try {
            $user = Auth::user();
            if (!$user || $user->active_role !== 'mediator') {
                return response()->json(['error' => 'Akses ditolak'], 403);
            }

            DB::beginTransaction();

            $terlapor = Terlapor::with('user')->findOrFail($id);

            // Update user status
            if ($terlapor->user) {
                // Aktifkan user
                $terlapor->user->update(['is_active' => true]);

                // Tambahkan role terlapor jika belum ada
                if (!in_array('terlapor', $terlapor->user->roles)) {
                    $roles = $terlapor->user->roles;
                    $roles[] = 'terlapor';
                    $terlapor->user->update(['roles' => $roles]);
                }
            }

            // Update terlapor status
            $terlapor->update([
                'is_active' => true,
                'has_account' => true
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Akun terlapor berhasil diaktifkan',
                'terlapor' => $terlapor
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in AkunController@activate: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Deactivate pelapor account
     */
    public function deactivatePelapor($id)
    {
        try {
            $user = Auth::user();
            if (!$user || $user->active_role !== 'mediator') {
                return response()->json(['error' => 'Akses ditolak'], 403);
            }

            DB::beginTransaction();

            $pelapor = Pelapor::with('user')->findOrFail($id);

            // Update user status
            if ($pelapor->user) {
                // Jika user memiliki role pelapor saja, nonaktifkan user
                if (count($pelapor->user->roles) === 1) {
                    $pelapor->user->update(['is_active' => false]);
                } else {
                    // Jika multi-role, hapus role pelapor
                    $roles = array_diff($pelapor->user->roles, ['pelapor']);
                    $pelapor->user->update([
                        'roles' => array_values($roles),
                        'active_role' => $roles[0] ?? null
                    ]);
                }
            }

            // Update pelapor status
            $pelapor->update(['is_active' => false]);

            DB::commit();

            return response()->json([
                'message' => 'Akun pelapor berhasil dinonaktifkan',
                'pelapor' => $pelapor
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in AkunController@deactivatePelapor: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Activate pelapor account
     */
    public function activatePelapor($id)
    {
        try {
            $user = Auth::user();
            if (!$user || $user->active_role !== 'mediator') {
                return response()->json(['error' => 'Akses ditolak'], 403);
            }

            DB::beginTransaction();

            $pelapor = Pelapor::with('user')->findOrFail($id);

            // Update user status
            if ($pelapor->user) {
                // Aktifkan user
                $pelapor->user->update(['is_active' => true]);

                // Tambahkan role pelapor jika belum ada
                if (!in_array('pelapor', $pelapor->user->roles)) {
                    $roles = $pelapor->user->roles;
                    $roles[] = 'pelapor';
                    $pelapor->user->update(['roles' => $roles]);
                }
            }

            // Update pelapor status
            $pelapor->update(['is_active' => true]);

            DB::commit();

            return response()->json([
                'message' => 'Akun pelapor berhasil diaktifkan',
                'pelapor' => $pelapor
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in AkunController@activatePelapor: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get account statistics for dashboard
     */
    public function getStats()
    {
        try {
            $user = Auth::user();
            if (!$user || $user->active_role !== 'mediator' || !$user->mediator) {
                return response()->json(['message' => 'Akses ditolak'], 403);
            }

            $stats = [
                'total_terlapor' => Terlapor::count(),
                'total_pelapor' => Pelapor::count(),
                'active_terlapor' => Terlapor::where('status', 'active')->count(),
                'active_pelapor' => Pelapor::whereHas('user', function ($q) {
                    $q->where('is_active', true);
                })->count(),
                'terlapor_this_month' => Terlapor::where('created_at', '>=', now()->startOfMonth())->count(),
                'pelapor_this_month' => Pelapor::where('created_at', '>=', now()->startOfMonth())->count()
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            Log::error('Error in AkunController@getStats: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan'], 500);
        }
    }
}
