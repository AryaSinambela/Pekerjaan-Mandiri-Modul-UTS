<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\LoanRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    // 5. GET /loans/create (Formulir pengajuan peminjaman aset)
    public function create(Request $request)
    {
        $equipments = Equipment::with('category')->orderBy('asset_code')->get();
        $selectedEquipmentId = $request->query('equipment_id');
        $recentLoans = LoanRequest::with('equipment')->latest()->take(10)->get();

        return view('loans.create', compact('equipments', 'selectedEquipmentId', 'recentLoans'));
    }

    // 6. POST /loans (Validasi aturan bisnis & transaksi peminjaman)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'equipment_id' => [
                'required',
                'exists:equipments,id',
                function ($attribute, $value, $fail) {
                    $equipment = Equipment::find($value);
                    if ($equipment && $equipment->status !== 'tersedia') {
                        $statusLabel = $equipment->status === 'dipinjam' ? 'dipinjam' : 'dalam perbaikan';
                        $fail("Perangkat {$equipment->name} saat ini sedang {$statusLabel} dan tidak tersedia.");
                    }
                },
            ],
            'borrower_name'        => ['required', 'string', 'min:3', 'max:100'],
            'borrower_nim'         => ['required', 'string', 'regex:/^[A-Za-z0-9]{8}$/'],
            'borrower_email'       => ['required', 'email', 'max:100'],
            'loan_date'            => ['required', 'date', 'after_or_equal:today'],
            'expected_return_date' => [
                'required',
                'date',
                'after_or_equal:loan_date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->filled('loan_date')) {
                        try {
                            $loanDate = Carbon::parse($request->input('loan_date'))->startOfDay();
                            $returnDate = Carbon::parse($value)->startOfDay();
                            if ($returnDate->gte($loanDate) && $loanDate->diffInDays($returnDate) > 7) {
                                $fail('Durasi peminjaman maksimal adalah 7 hari kalender.');
                            }
                        } catch (\Exception $e) {
                            // Diabaikan jika format tanggal tidak valid (ditangani rule 'date')
                        }
                    }
                },
            ],
            'purpose'              => ['required', 'string'],
        ], [
            'equipment_id.required'               => 'Perangkat laboratorium wajib dipilih.',
            'equipment_id.exists'                 => 'Perangkat yang dipilih tidak terdaftar.',
            'borrower_name.required'              => 'Nama peminjam wajib diisi minimal 3 karakter.',
            'borrower_name.min'                   => 'Nama peminjam wajib diisi minimal 3 karakter.',
            'borrower_nim.required'               => 'Format NIM harus terdiri dari tepat 8 digit angka/huruf institusi.',
            'borrower_nim.regex'                  => 'Format NIM harus terdiri dari tepat 8 digit angka/huruf institusi.',
            'borrower_email.required'             => 'Alamat surel mahasiswa wajib diisi.',
            'borrower_email.email'                => 'Format alamat surel yang Anda masukkan tidak valid.',
            'loan_date.required'                  => 'Tanggal peminjaman wajib diisi.',
            'loan_date.after_or_equal'            => 'Tanggal peminjaman tidak boleh sebelum tanggal hari ini.',
            'expected_return_date.required'       => 'Tanggal rencana kembali wajib diisi.',
            'expected_return_date.after_or_equal' => 'Tanggal rencana kembali tidak boleh sebelum tanggal peminjaman.',
            'purpose.required'                    => 'Tujuan peminjaman wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect('/loans/create')
                ->withErrors($validator)
                ->withInput()
                ->with('error', '[GAGAL] Pengajuan tidak dapat diproses. Silakan periksa kembali seluruh masukan data Anda.');
        }

        $validated = $validator->validated();
        $equipment = Equipment::findOrFail($validated['equipment_id']);

        $loanDate = Carbon::parse($validated['loan_date'])->startOfDay();
        $returnDate = Carbon::parse($validated['expected_return_date'])->startOfDay();

        // Kalkulasi otomatis selisih hari dikali tarif harian (minimal 1 hari jika pinjam & kembali di hari yang sama)
        $days = max(1, $loanDate->diffInDays($returnDate));
        $totalEstimatedFee = $days * $equipment->daily_rental_fee;

        LoanRequest::create([
            'equipment_id'         => $equipment->id,
            'borrower_name'        => $validated['borrower_name'],
            'borrower_nim'         => strtoupper($validated['borrower_nim']),
            'borrower_email'       => $validated['borrower_email'],
            'loan_date'            => $validated['loan_date'],
            'expected_return_date' => $validated['expected_return_date'],
            'purpose'              => $validated['purpose'],
            'status'               => 'diajukan',
            'total_estimated_fee'  => $totalEstimatedFee,
        ]);

        // Mutasi status perangkat secara konsisten agar mencegah double-booking
        $equipment->update([
            'status' => 'dipinjam',
        ]);

        return redirect()
            ->route('equipments.index')
            ->with('success', "[SUKSES] Pengajuan peminjaman '{$equipment->name}' atas nama {$validated['borrower_name']} berhasil dicatat! Estimasi biaya kompensasi: Rp " . number_format($totalEstimatedFee, 0, ',', '.'));
    }
}