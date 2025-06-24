<?php

namespace App\Http\Controllers\Api;

use App\Models\Terlapor;
use Illuminate\Http\Request;
use App\Services\TerlaporService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateTerlaporRequest;

class TerlaporController extends Controller
{
    protected $terlaporService;

    public function __construct(TerlaporService $terlaporService)
    {
        $this->terlaporService = $terlaporService;
        // $this->middleware('auth:sanctum');
        // $this->middleware('role:mediator');
    }

    /**
     * Buat akun terlapor baru
     */
    public function store(CreateTerlaporRequest $request): JsonResponse
    {
        try {
            $mediatorId = Auth::user()->mediator->mediator_id;

            $result = $this->terlaporService->createTerlaporAccount(
                $request->validated(),
                $mediatorId
            );

            return response()->json([
                'success' => true,
                'message' => 'Akun terlapor berhasil dibuat dan email telah dikirim',
                'data' => [
                    'terlapor_id' => $result['terlapor']->terlapor_id,
                    'user_id' => $result['user']->user_id,
                    'nama_terlapor' => $result['terlapor']->nama_terlapor,
                    'email_terlapor' => $result['user']->email_terlapor,
                    'status' => 'email_sent'
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat akun terlapor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dapatkan daftar terlapor yang dibuat oleh mediator
     */
    public function index(): JsonResponse
    {
        try {
            $mediatorId = Auth::user()->mediator->mediator_id;
            $terlapors = $this->terlaporService->getTerlaporByMediator($mediatorId);

            return response()->json([
                'success' => true,
                'data' => $terlapors->map(function ($terlapor) {
                    return [
                        'terlapor_id' => $terlapor->terlapor_id,
                        'nama_terlapor' => $terlapor->nama_terlapor,
                        'email_terlapor' => $terlapor->email_terlapor,
                        'alamat_kantor_cabang' => $terlapor->alamat_kantor_cabang,
                        'no_hp_terlapor' => $terlapor->no_hp_terlapor,
                        'status' => $terlapor->status,
                        'user_status' => $terlapor->user->is_active,
                        'created_at' => $terlapor->created_at->format('Y-m-d H:i:s')
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data terlapor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Detail terlapor
     */
    public function show(int $id): JsonResponse
    {
        try {
            $mediatorId = Auth::user()->mediator->mediator_id;

            $terlapor = Terlapor::with('user')
                ->where('terlapor_id', $id)
                ->where('created_by_mediator_id', $mediatorId)
                ->first();

            if (!$terlapor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlapor tidak ditemukan atau Anda tidak memiliki akses'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'terlapor_id' => $terlapor->terlapor_id,
                    'nama_terlapor' => $terlapor->nama_terlapor,
                    'email_terlapor' => $terlapor->email_terlapor,
                    'alamat_kantor_cabang' => $terlapor->alamat_kantor_cabang,
                    'no_hp_terlapor' => $terlapor->no_hp_terlapor,
                    'status' => $terlapor->status,
                    'user_status' => $terlapor->user->is_active,
                    'created_at' => $terlapor->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $terlapor->updated_at->format('Y-m-d H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail terlapor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Nonaktifkan akun terlapor
     */
    public function deactivate(Request $request, int $id): JsonResponse
    {
        try {
            $mediatorId = Auth::user()->mediator->mediator_id;
            $success = $this->terlaporService->deactivateTerlapor($id, $mediatorId);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlapor tidak ditemukan atau Anda tidak memiliki akses'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Akun terlapor berhasil dinonaktifkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menonaktifkan akun: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aktifkan kembali akun terlapor
     */
    public function activate(Request $request, int $id): JsonResponse
    {
        try {
            $mediatorId = Auth::user()->mediator->mediator_id;
            $success = $this->terlaporService->activateTerlapor($id, $mediatorId);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlapor tidak ditemukan atau Anda tidak memiliki akses'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Akun terlapor berhasil diaktifkan kembali'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan akun: ' . $e->getMessage()
            ], 500);
        }
    }
}
