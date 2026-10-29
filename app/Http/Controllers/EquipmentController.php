<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('id')->get();
        $query = Equipment::with('category');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('asset_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('status') && $request->input('status') !== 'semua') {
            $query->where('status', $request->input('status'));
        }

        $equipments = $query->orderByDesc('id')
                            ->paginate(6)
                            ->appends($request->query());

        return view('equipments.index', compact('equipments', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('id')->get();
        return view('equipments.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_code'       => ['required', 'string', 'max:20', 'unique:equipments,asset_code'],
            'name'             => ['required', 'string', 'min:5', 'max:150'],
            'category_id'      => ['required', 'exists:categories,id'],
            'specification'    => ['required', 'string'],
            'condition'        => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'daily_rental_fee' => ['required', 'numeric', 'min:0'],
            'image_type'       => ['required', 'in:upload,url'],
            'image'            => ['required_if:image_type,upload', 'nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'image_url'        => ['required_if:image_type,url', 'nullable', 'url'],
        ], [
            'asset_code.required'      => 'Kode aset wajib diisi.',
            'asset_code.unique'        => 'Kode aset sudah terdaftar di dalam sistem.',
            'name.required'            => 'Nama perangkat wajib diisi.',
            'name.min'                 => 'Nama perangkat minimal terdiri dari 5 karakter.',
            'category_id.required'     => 'Kategori perangkat wajib dipilih.',
            'specification.required'   => 'Spesifikasi teknis wajib diisi.',
            'daily_rental_fee.required'=> 'Tarif kompensasi harian wajib diisi.',
            'image.required_if'        => 'Berkas foto fisik perangkat wajib diunggah.',
            'image_url.required_if'    => 'Tautan URL gambar internet wajib diisi.',
            'image_url.url'            => 'Format URL gambar internet tidak valid.',
        ]);

        $imagePath = null;

        if ($request->input('image_type') === 'upload' && $request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $slugCode = Str::slug($validated['asset_code'], '_');
            $filename = time() . '_' . $slugCode . '.' . $extension;
            $imagePath = $file->storeAs('equipments', $filename, 'public');
        } elseif ($request->input('image_type') === 'url') {
            $imagePath = $request->input('image_url');
        }

        $equipment = Equipment::create([
            'category_id'      => $validated['category_id'],
            'asset_code'       => $validated['asset_code'],
            'name'             => $validated['name'],
            'specification'    => $validated['specification'],
            'condition'        => $validated['condition'],
            'status'           => 'tersedia',
            'daily_rental_fee' => $validated['daily_rental_fee'],
            'image_path'       => $imagePath,
        ]);

        return redirect()
            ->route('equipments.index')
            ->with('success', "[SUKSES] Perangkat '{$equipment->name}' berhasil didaftarkan!");
    }

    public function show($id)
    {
        $equipment = Equipment::with(['category', 'loanRequests' => function ($q) {
            $q->latest();
        }])->findOrFail($id);

        return view('equipments.show', compact('equipment'));
    }
}