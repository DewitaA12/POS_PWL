<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Stok',
            'list' => [
                ['title' => 'Home', 'url' => url('/')],
                ['title' => 'Stok', 'url' => url('/stok')]
            ]
        ];

        $page = (object) [
            'title' => 'Daftar stok yang terdaftar dalam sistem'
        ];

        $activeMenu = 'stok';
        $barang = BarangModel::all();
        $supplier = SupplierModel::all();

        return view('stok.index', compact('breadcrumb', 'page', 'barang', 'supplier', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $stoks = StokModel::with(['supplier', 'barang', 'user'])
            ->select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah');

        if ($request->barang_id) {
            $stoks->where('barang_id', $request->barang_id);
        }

        if ($request->supplier_id) {
            $stoks->where('supplier_id', $request->supplier_id);
        }

        return DataTables::of($stoks)
            ->addIndexColumn()
            ->addColumn('barang_nama', function ($row) {
                return $row->barang->barang_nama ?? '-';
            })
            ->addColumn('supplier_nama', function ($row) {
                return $row->supplier->supplier_nama ?? '-';
            })
            ->addColumn('user_nama', function ($row) {
                return $row->user->nama ?? '-';
            })
            ->addColumn('aksi', function ($stok) {
                return '
                    <button onclick="modalAction(\''.url("/stok/{$stok->stok_id}/show_ajax").'\')" class="btn btn-info btn-sm">Detail</button>
                    <button onclick="modalAction(\''.url("/stok/{$stok->stok_id}/edit_ajax").'\')" class="btn btn-warning btn-sm">Edit</button>
                    <button onclick="modalAction(\''.url("/stok/{$stok->stok_id}/delete_ajax").'\')" class="btn btn-danger btn-sm">Hapus</button>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Stok',
            'list' => [
                ['title' => 'Home', 'url' => url('/')],
                ['title' => 'Stok', 'url' => url('/stok')],
                ['title' => 'Tambah', 'url' => url('/stok/tambah')]
            ]
        ];

        $page = (object) ['title' => 'Tambah stok baru'];
        $activeMenu = 'stok';
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('stok.create', compact('breadcrumb', 'page', 'activeMenu', 'barang', 'supplier', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|integer|exists:m_supplier,supplier_id',
            'barang_id' => 'required|integer|exists:m_barang,barang_id',
            'user_id' => 'required|integer|exists:m_user,user_id',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer|min:1',
        ]);

        StokModel::create($request->all());

        return redirect('/stok')->with('success', 'Data stok berhasil disimpan');
    }

    public function show(string $id)
    {
        $stok = StokModel::with(['supplier', 'barang', 'user'])->findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Detail Stok',
            'list' => [
                ['title' => 'Home', 'url' => url('/')],
                ['title' => 'Stok', 'url' => url('/stok')],
                ['title' => 'Detail', 'url' => url("/stok/{$id}")]
            ]
        ];

        $page = (object) ['title' => 'Detail stok'];
        $activeMenu = 'stok';

        return view('stok.show', compact('stok', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function edit($id)
    {
        $stok = StokModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Edit Stok',
            'list' => [
                ['title' => 'Home', 'url' => url('/')],
                ['title' => 'Stok', 'url' => url('/stok')],
                ['title' => 'Edit', 'url' => url("/stok/{$id}/edit")]
            ]
        ];

        $page = (object) ['title' => 'Edit stok'];
        $activeMenu = 'stok';
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('stok.edit', compact('stok', 'breadcrumb', 'page', 'activeMenu', 'barang', 'supplier', 'user'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'supplier_id' => 'required|integer|exists:m_supplier,supplier_id',
            'barang_id' => 'required|integer|exists:m_barang,barang_id',
            'user_id' => 'required|integer|exists:m_user,user_id',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer|min:1',
        ]);

        $stok = StokModel::findOrFail($id);
        $stok->update($request->all());

        return redirect('/stok')->with('success', 'Data stok berhasil diubah');
    }

    public function destroy(string $id)
    {
        $stok = StokModel::find($id);

        if (!$stok) {
            return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
        }

        try {
            $stok->delete();
            return redirect('/stok')->with('success', 'Data stok berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/stok')->with('error', 'Data stok gagal dihapus karena terkait dengan data lain');
        }
    }

    public function createAjax()
    {
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('stok.create_ajax', compact('barang', 'supplier', 'user'));
    }

    public function storeAjax(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $validator = Validator::make($request->all(), [
            'supplier_id' => ['required', 'integer', 'exists:m_supplier,supplier_id'],
            'barang_id' => ['required', 'integer', 'exists:m_barang,barang_id'],
            'user_id' => ['required', 'integer', 'exists:m_user,user_id'],
            'stok_tanggal' => ['required', 'date'],
            'stok_jumlah' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }

        StokModel::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan'
        ]);
    }

    public function editAjax($id)
    {
        $stok = StokModel::findOrFail($id);
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $user = UserModel::select('user_id', 'nama')->get();

        return view('stok.edit_ajax', compact('stok', 'barang', 'supplier', 'user'));
    }

    public function updateAjax(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $validator = Validator::make($request->all(), [
            'supplier_id' => ['required', 'integer', 'exists:m_supplier,supplier_id'],
            'barang_id' => ['required', 'integer', 'exists:m_barang,barang_id'],
            'user_id' => ['required', 'integer', 'exists:m_user,user_id'],
            'stok_tanggal' => ['required', 'date'],
            'stok_jumlah' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }

        $stok = StokModel::find($id);

        if (!$stok) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $stok->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate'
        ]);
    }

    public function confirmAjax($id)
    {
        $stok = StokModel::findOrFail($id);
        return view('stok.confirm_ajax', compact('stok'));
    }

    public function deleteAjax(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect('/');
        }

        $stok = StokModel::find($id);

        if (!$stok) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $stok->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }

    public function showAjax($id)
    {
        $stok = StokModel::with(['supplier', 'barang', 'user'])->find($id);

        if ($stok) {
            return view('stok.show_ajax', compact('stok'));
        }

        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    public function import()
    {
        return view('stok.import');
    }

    public function importAjax(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid request'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'file_stok' => ['required', 'mimes:xlsx', 'max:1024']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()->toArray()
            ]);
        }

        try {
            $file = $request->file('file_stok');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            foreach ($data as $row => $value) {
                if ($row > 1 && !empty($value['B']) && !empty($value['C']) && !empty($value['D']) && !empty($value['E']) && !empty($value['F'])) {
                    $insert[] = [
                        'supplier_id' => (int) $value['B'],
                        'barang_id' => (int) $value['C'],
                        'user_id' => (int) $value['D'],
                        'stok_tanggal' => $this->formatExcelDate($value['E']),
                        'stok_jumlah' => (int) $value['F'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            if (count($insert) > 0) {
                StokModel::insertOrIgnore($insert);
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data valid untuk diimport'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

      public function export_excel()
    {
        // Ambil data stok barang yang akan diexport
        $stokBarang = StokModel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->get();

        // Load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif
        $sheet->setCellValue('A1', 'ID Stok');
        $sheet->setCellValue('B1', 'ID Supplier');
        $sheet->setCellValue('C1', 'ID Barang');
        $sheet->setCellValue('D1', 'ID User');
        $sheet->setCellValue('E1', 'Tanggal Stok');
        $sheet->setCellValue('F1', 'Jumlah Stok');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // Bold header

        $baris = 2; // Baris data dimulai dari baris ke 2
        foreach ($stokBarang as $key => $value) {
            $sheet->setCellValue('A' . $baris, $value->stok_id);
            $sheet->setCellValue('B' . $baris, $value->supplier_id);
            $sheet->setCellValue('C' . $baris, $value->barang_id);
            $sheet->setCellValue('D' . $baris, $value->user_id);
            $sheet->setCellValue('E' . $baris, $value->stok_tanggal); // Format tanggal
            $sheet->setCellValue('F' . $baris, $value->stok_jumlah);
            $baris++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // Set auto size untuk kolom
        }

        $sheet->setTitle('Data Stok Barang'); // Set title sheet
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Stok Barang ' . date('Y-m-d H:i:s') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified:' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        // Mengambil data stok barang beserta relasi supplier, barang, dan user
        $stokBarang = StokModel::select('stok_id', 'supplier_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
            ->orderBy('stok_id')
            // ->orderBy('barang_id')
            // ->orderBy('user_id')
            ->with('supplier', 'barang', 'user')
            ->get();

        // Menggunakan Barryvdh\DomPDF\Facade\Pdf untuk membuat PDF
        $pdf = Pdf::loadView('stok.export_pdf', ['stokBarang' => $stokBarang]);
        $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // Set true jika ada gambar dari URL
        $pdf->render();

        // Mengembalikan hasil PDF dalam bentuk stream (langsung ditampilkan)
        return $pdf->stream('Data Stok Barang ' . date('Y-m-d H:i:s') . '.pdf');
    }
}