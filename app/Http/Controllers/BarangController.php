<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
   public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'Barang', 'url' => '/barang']
            ],
        ];
        
        $page = (object) [
            'title' => 'Daftar barang yang terdaftar dalam sistem'
        ];
        
        $activeMenu = 'barang'; // set menu yang sedang aktif
    
        $kategori = KategoriModel::all(); // Perbaikan: Gunakan KategoriModel

        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategori' => $kategori]);
    }

    // Ambil data kategori dalam bentuk json untuk datatables
   public function list(Request $request)
    {
        $barang = BarangModel::with('kategori')->select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual');
    
        return DataTables::of($barang)
            ->addIndexColumn()
            ->addColumn('kategori_nama', function ($barang) {
                return $barang->kategori->kategori_nama ?? 'N/A';
            })
            ->addColumn('aksi', function ($barang) {
                $btn = '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'Barang', 'url' => '/barang'],
                ['title' => 'Tambah', 'url' => '/barang/tambah']
            ],
        ];
    
        $page = (object) ['title' => 'Tambah Barang']; 
        $activeMenu = 'barang';
    
        // Ambil data kategori dari tabel m_kategori
        $kategori = KategoriModel::all(); // Ambil semua kategori
    
        return view('barang.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori // Kirim data kategori ke view
        ]);
    }
    

    public function store(Request $request)
    {
        // Validasi data yang diterima dari permintaan
        $request->validate([
            'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode', // Kode barang harus diisi, string, maksimal 10 karakter, dan unik
            'barang_nama' => 'required|string|max:100', // Nama barang harus diisi, string, dan maksimal 100 karakter
            'kategori_id' => 'required|exists:m_kategori,kategori_id', // Pastikan kategori_id ada dan valid
            'harga_beli' => 'required|numeric', // Harga beli harus diisi dan berupa angka
            'harga_jual' => 'required|numeric', // Harga jual harus diisi dan berupa angka
        ]);
    
        // Menyimpan barang baru
        BarangModel::create([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'kategori_id' => $request->kategori_id, // Menyimpan kategori_id
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
        ]);
    
        // Mengalihkan dengan pesan sukses
        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }
    
public function show(string $id)
    {
    $barang = BarangModel::find($id); // Mengambil barang berdasarkan ID

    $breadcrumb = (object) [
        'title' => 'Detail Barang',
        'list' => [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Barang', 'url' => '/barang'],
            ['title' => 'Detail', 'url' => '/barang/detail/' . $id]
        ],
    ];

    $page = (object) [
        'title' => 'Detail Barang'
    ];

    $activeMenu = 'barang'; // Set menu yang sedang aktif

    return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

public function edit($id)
    {
    $barang = BarangModel::find($id); // Mengambil barang berdasarkan ID

    $breadcrumb = (object) [
        'title' => 'Edit Barang',
        'list' => [
            ['title' => 'Home', 'url' => '/'],
            ['title' => 'Barang', 'url' => '/barang'],
            ['title' => 'Edit', 'url' => '/barang/edit/' . $id]
        ],
    ];

    $page = (object) [
        'title' => 'Edit Barang'
    ];

    $activeMenu = 'barang';

    return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
    // Validasi data yang diterima dari permintaan
    $request->validate([
        'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode,' . $id . ',barang_id', // barang_kode harus diisi, berupa string, maksimal 10 karakter, dan unik
        'barang_nama' => 'required|string|max:100', // barang_nama harus diisi, berupa string, dan maksimal 100 karakter
        'harga_beli' => 'required|numeric', // Harga beli harus diisi dan berupa angka
        'harga_jual' => 'required|numeric', // Harga jual harus diisi dan berupa angka
    ]);

    // Mencari barang berdasarkan barang_id
    $barang = BarangModel::findOrFail($id); 

    // Menyiapkan data untuk diperbarui
    $dataToUpdate = [
        'barang_kode' => $request->barang_kode,
        'barang_nama' => $request->barang_nama,
        'harga_beli' => $request->harga_beli,
        'harga_jual' => $request->harga_jual,
    ];  

    // Memperbarui barang
    $barang->update($dataToUpdate);

    // Mengalihkan dengan pesan sukses
    return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }

    public function destroy(string $id)
    {
    $check = BarangModel::find($id);

    if (!$check) {
        // Mengecek apakah data barang dengan id yang dimaksud ada atau tidak
        return redirect('barang')->with('error', 'Data barang tidak ditemukan');
    }

    try {
        BarangModel::destroy($id); // Hapus data barang

        return redirect('barang')->with('success', 'Data barang berhasil dihapus');

    } catch (\Illuminate\Database\QueryException $e) {
        // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
        return redirect('barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
    }
    }

    public function show_ajax(string $id){
        $barang = BarangModel::find($id);
        return view('barang.show_ajax', ['barang' => $barang]);
    }
    
    public function create_ajax(){
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('barang.create_ajax')->with('kategori', $kategori);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => 'required|integer',
                'barang_kode' => 'required|string|min:1|unique:m_barang,barang_kode',
                'barang_nama' => 'required|string|max:100',
                'harga_beli' => 'required|numeric', // Perbaikan: Ubah dari string ke numeric
                'harga_jual' => 'required|numeric'  // Perbaikan: Ubah dari string ke numeric
            ];
            
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            BarangModel::create($request->all());
    
            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil disimpan'
            ]);
        }
    
        redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    }

   public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' => 'required|integer',
                'barang_kode' => 'required|string|min:1|unique:m_barang,barang_kode,' . $id . ',barang_id',
                'barang_nama' => 'required|string|max:100',
                'harga_beli' => 'required|numeric', // Perbaikan: Ubah dari string ke numeric
                'harga_jual' => 'required|numeric'  // Perbaikan: Ubah dari string ke numeric
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $barang = BarangModel::find($id);
            if ($barang) {
                $barang->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        
        return redirect('/');
    }

    public function confirm_ajax(string $id){
        $barang = BarangModel::find($id);
        return view('barang.confirm_ajax', ['barang' => $barang]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $barang = BarangModel::find($id);

            if ($barang) {
                $barang->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }

    public function import()
    {
        return view('barang.import');
    }


    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                // validasi file harus xls atau xlsx, max 1MB
                'file_barang' => ['required', 'mimes:xlsx', 'max:1024']
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $file = $request->file('file_barang'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel
    
            $insert = [];
    
            if (count($data) > 1) { // jika data lebih dari 1 barisavlo wwwww                       
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                        $insert[] = [
                            'kategori_id' => $value['A'],
                            'barang_kode' => $value['B'],
                            'barang_nama' => $value['C'],
                            'harga_beli'  => $value['D'],
                            'harga_jual'  => $value['E'],
                            'created_at'  => now(),
                        ];
                    }
                }
    
                if (count($insert) > 0) {
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    BarangModel::insertOrIgnore($insert);
                }
    
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
    
        return redirect('/');
    }
}
