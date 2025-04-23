<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'Supplier', 'url' => '/supplier']
            ],
        ];

        $page = (object) [
            'title' => 'Daftar supplier yang terdaftar dalam sistem'
        ];

        $activeMenu = 'supplier';

        $suppliers = SupplierModel::all();

        return view('supplier.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'suppliers' => $suppliers
        ]);
    }

    public function list(Request $request)
    {
        $suppliers = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'alamat');

        return DataTables::of($suppliers)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                $btn = '<a href="' . url('/supplier/' . $supplier->supplier_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form action="' . url('/supplier/' . $supplier->supplier_id) . '" method="POST" class="d-inline-block">' .
                    csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus supplier ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Supplier',
            'list' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'Supplier', 'url' => '/supplier'],
                ['title' => 'Tambah', 'url' => '/supplier/tambah']
            ],
        ];

        $page = (object) ['title' => 'Tambah Supplier']; 
        $activeMenu = 'supplier';

        return view('supplier.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        // Validasi data yang diterima dari permintaan
        $request->validate([
            'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode', // Kode supplier harus diisi, string, maksimal 10 karakter, dan unik
            'supplier_nama' => 'required|string|max:100', // Nama supplier harus diisi, string, dan maksimal 100 karakter
            'alamat' => 'required|string|max:255', // Alamat harus diisi, string, dan maksimal 255 karakter         
        ]);

        // Menyimpan supplier baru
        SupplierModel::create([
            'supplier_kode' => $request->supplier_kode,
            'supplier_nama' => $request->supplier_nama,
            'alamat' => $request->alamat, 
        ]);

        // Mengalihkan dengan pesan sukses
        return redirect('/supplier')->with('success', 'Data supplier berhasil disimpan');
    }

    public function show(string $id)
    {
        $supplier = SupplierModel::find($id); // Mengambil supplier berdasarkan ID

        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'Supplier', 'url' => '/supplier'],
                ['title' => 'Detail', 'url' => '/supplier/detail/' . $id]
            ],
        ];

        $page = (object) [
            'title' => 'Detail Supplier'
        ];

        $activeMenu = 'supplier'; // Set menu yang sedang aktif

        return view('supplier.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
    }

    public function edit($id)
    {
        $supplier = SupplierModel::find($id); // Mengambil supplier berdasarkan ID

        $breadcrumb = (object) [
            'title' => 'Edit Supplier',
            'list' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'Supplier', 'url' => '/supplier'],
                ['title' => 'Edit', 'url' => '/supplier/edit/' . $id]
            ],
        ];

        $page = (object) [
            'title' => 'Edit Supplier'
        ];

        $activeMenu = 'supplier';

        return view('supplier.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
        // Validasi data yang diterima dari permintaan
        $request->validate([
            'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode,' . $id . ',supplier_id', // supplier_kode harus diisi, berupa string, maksimal 10 karakter, dan unik
            'supplier_nama' => 'required|string|max:100', // supplier_nama harus diisi, berupa string, dan maksimal 100 karakter
            'alamat' => 'required|string|max:255', // Alamat harus diisi, string, dan maksimal 255 karakter
        ]);

        // Mencari supplier berdasarkan supplier_id
        $supplier = SupplierModel::findOrFail($id); 

        // Menyiapkan data untuk diperbarui
        $dataToUpdate = [
            'supplier_kode' => $request->supplier_kode,
            'supplier_nama' => $request->supplier_nama,
            'alamat' => $request->alamat,
        ];

        // Memperbarui supplier
        $supplier->update($dataToUpdate);

        // Mengalihkan dengan pesan sukses
        return redirect('/supplier')->with('success', 'Data supplier berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = SupplierModel::find($id);

        if (!$check) {
            // Mengecek apakah data supplier dengan id yang dimaksud ada atau tidak
            return redirect('supplier')->with('error', 'Data supplier tidak ditemukan');
        }

        try {
            SupplierModel::destroy($id); // Hapus data supplier

            return redirect('supplier')->with('success', 'Data supplier berhasil dihapus');

        } catch (\Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('supplier')->with('error', 'Data supplier gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
