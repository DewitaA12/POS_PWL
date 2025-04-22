<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    // public function index()
    // {
    //     // DB::insert('insert into m_level(level_kode, level_nama, created_at) values(?,?,?)', 
    //     // ['CUS', 'Pelanggan', now()]);

    //     // $row = DB::update('update m_level set level_nama = ? where level_kode = ?', ['Customer', 'CUS']);
    //     // return 'Update data berhasil. Jumlah data yang diupdate: ' . $row.' baris';

    //     // $row = DB::delete('delete from m_level where level_kode = ?', ['CUS']);
    //     // return 'Delete data berhasil. Jumlah data yang dihapus: ' . $row. ' baris';

    //     $data = DB::select('select * from m_level');
    //     return view('level', ['data' => $data]);
    // }

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => [
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'Level', 'url' => '/level']
            ],
        ];
        
        $page = (object) [
            'title' => 'Daftar level yang terdaftar dalam sistem'
        ];
        
        $activeMenu = 'level'; // set menu yang sedang aktif
    
        $levels = LevelModel::all(); 

        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'levels' => $levels]);
    }

    // Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');

        return DataTables::of($levels)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
                $btn = '<a href="'. url('/level/'. $level->level_id) .'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'. url('/level/'. $level->level_id .'/edit') .'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form action="'. url('/level/'. $level->level_id) .'" method="POST" class="d-inline-block">'.
                         csrf_field() . method_field('DELETE') .
                         '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus level ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    
}
