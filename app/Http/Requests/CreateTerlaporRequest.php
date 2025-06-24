<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateTerlaporRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'mediator';
    }

    public function rules(): array
    {
        return [
            'nama_terlapor' => 'required|string|max:255',
            'alamat_kantor_cabang' => 'required|string|max:255',
            'email_terlapor' => 'required|email|unique:users,email|unique:terlapor,email_terlapor|max:100',
            'no_hp_terlapor' => 'nullable|string|max:15',
            'pengaduan_id' => 'nullable|exists:pengaduans,pengaduan_id'
        ];
    }

    public function messages(): array
    {
        return [
            'nama_terlapor.required' => 'Nama perusahaan wajib diisi',
            'alamat_kantor_cabang.required' => 'Alamat kantor/cabang wajib diisi',
            'email_terlapor.required' => 'Email perusahaan wajib diisi',
            'email_terlapor.unique' => 'Email sudah terdaftar dalam sistem',
        ];
    }
}
