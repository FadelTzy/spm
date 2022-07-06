<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\rkakl;
use Illuminate\Support\Carbon;
use App\Models\rab;
use App\Models\kegiatan;
use App\Models\akun;

class RkaklController extends Controller
{
    public function check($id)
    {
        rkakl::where('aktivasi', 1)->update(['aktivasi' => 0]);
        $data = rkakl::where('id', $id)->first();
        $data->aktivasi = 1;
        $data->save();
        return 'success';
    }
    public function create($id)
    {
        $kegiatan =   kegiatan::with('kroitem.roitem.komponenitem.subkomponenitem')->get();
        $akun = akun::get();
        if (request()->ajax()) {
            return Datatables::of(rab::with('kegiatanr', 'kror', 'ror', 'komponenr', 'subkomponenr')->where('id_rkakl', $id)->get())->addIndexColumn()->addColumn('aksi', function ($data) {
                $dataj = json_encode($data);

                $btn = "      <ul class='list-inline mb-0'>
                <li class='list-inline-item'>
                <button type='button' data-toggle='modal' onclick='staffupd(" . $dataj . ")'   class='btn btn-sm btn-success btn-xs mb-1'><i class='bx bx-edit-alt'></i> </button>
                </li>
                    <li class='list-inline-item'>
                    <button type='button'  onclick='staffdel(" . $data->id . ")'   class='btn btn-danger btn-sm btn-xs mb-1'><i class='bx bx-trash'></i>  </button>
                    </li>
               
                </ul>";
                return $btn;
            })->addColumn('thn_anggaran', function ($data) {

                $btn = "Tahun Anggaran " .  $data->tahun_anggaran;
                return $btn;
            })->addColumn('tanggal', function ($data) {
                $date = Carbon::parse($data->tgl_cetak)->locale('id');

                $date->settings(['formatFunction' => 'translatedFormat']);

                return $date->format('l, j F Y'); // 
            })->addColumn('kegiatan_nama', function ($data) {
                if ($data->kegiatanr == null) {
                    $date = '-';
                } else {
                    $date = $data->kegiatanr->kode . ' - ' . $data->kegiatanr->nama;
                }
                return $date;
            })->addColumn('kro_nama', function ($data) {
                if ($data->kror == null) {
                    $date = '-';
                } else {
                    $date = $data->kror->nama;
                }
                return $date;
            })->addColumn('ro_nama', function ($data) {
                if ($data->ror == null) {
                    $date = '-';
                } else {
                    $date = $data->ror->nama;
                }


                return $date;
            })->addColumn('komponen_nama', function ($data) {
                if ($data->komponenr == null) {
                    $date = '-';
                } else {
                    $date = $data->komponenr->nama;
                }
                return $date;
            })->addColumn('akun_nama', function ($data) {
                if ($data->akunr == null) {
                    $date = '-';
                } else {
                    $date = $data->akunr->kode . ' - ' .  $data->akunr->nama;
                }
                return $date;
            })->rawColumns(['aksi', 'akun_nama', 'ro_nama', 'kro_nama', 'kegiatan_nama', 'komponen_nama', 'thn_anggaran', 'tanggal'])->make(true);
        }
        return view('admin.rkakl.rab', compact('kegiatan', 'akun'));
    }
    public function update(Request $request)
    {
        $data = rkakl::findorfail($request->id);
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'max:255'],
            'tanggal' => ['required',  'max:255'],

        ]);


        if ($validator->fails()) {
            $data = ['status' => 'error', 'data' => $validator->errors()];
            return $data;
        }


        $data->nama = $request->nama;
        $data->tgl_cetak = $request->tanggal;


        $data->save();

        return 'success';
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:255'],
            'tahun_anggaran' => ['required', 'max:255', 'unique:rkakl'],
            'tanggal' => ['required', 'max:255'],

        ]);
        if ($validator->fails()) {
            $data = ['status' => 'error', 'data' => $validator->errors()];
            return $data;
        }

        if ($request->status_aktivasi == 1) {
            rkakl::where('aktivasi', 1)->update(['aktivasi' => 0]);
        }
        $user = rkakl::create([
            'nama' => $request->nama,
            'tahun_anggaran' => $request->tahun_anggaran,
            'tgl' => $request->tanggal,
            'aktivasi' => $request->status_aktivasi,
            'keterangan' => $request->keterangan,
            'status_revisi' => 1
        ]);
        if ($user) {
            return 'success';
        }
    }
    public function index()
    {

        if (request()->ajax()) {
            return Datatables::of(rkakl::get())->addIndexColumn()->addColumn('aksi', function ($data) {
                $dataj = json_encode($data);

                $btn = "      <ul class='list-inline mb-0'>
                <li class='list-inline-item'>
                <button type='button' data-toggle='modal' onclick='staffcheck(" . $data->id . ")'   class='btn btn-sm btn-primary btn-xs mb-1'><i class='bx bx-check'></i> </button>
                </li>
                <li class='list-inline-item'>
                <button type='button' data-toggle='modal' onclick='staffupd(" . $dataj . ")'   class='btn btn-sm btn-warning btn-xs mb-1'><i class='bx bx-copy-alt'></i> </button>
                </li>
                <li class='list-inline-item'>
                <button type='button' data-toggle='modal' onclick='staffupd(" . $dataj . ")'   class='btn btn-sm btn-success btn-xs mb-1'><i class='bx bx-edit-alt'></i> </button>
                </li>
                    <li class='list-inline-item'>
                    <button type='button'  onclick='staffdel(" . $data->id . ")'   class='btn btn-danger btn-sm btn-xs mb-1'><i class='bx bx-trash'></i>  </button>
                    </li>
                    <li class='list-inline-item'>
                    <a type='button'  href='" . url('admin/rkakl/' . $data->id) . "'  class='btn btn-secondary btn-sm btn-xs mb-1'><i class='bx bx-clipboard'></i>  </a>
                    </li>
                </ul>";
                return $btn;
            })->addColumn('thn_anggaran', function ($data) {

                $btn = "Tahun Anggaran " .  $data->tahun_anggaran;
                return $btn;
            })->addColumn('status_aktif', function ($data) {

                if ($data->aktivasi == 1) {
                    $btn = '<button class="btn-sm btn-primary btn "> Aktif </button>';
                } else {
                    $btn =  '<button class="btn-sm btn-danger btn "> Non-Aktif </button>';
                }
                return $btn;
            })->addColumn('tanggal', function ($data) {
                $date = Carbon::parse($data->tgl_cetak)->locale('id');

                $date->settings(['formatFunction' => 'translatedFormat']);

                return $date->format('l, j F Y'); // 
            })->rawColumns(['aksi', 'status_aktif', 'thn_anggaran', 'tanggal'])->make(true);
        }

        return view('admin.rkakl.rkakl');
    }
    public function destroy($id)
    {
        $data = rkakl::findorfail($id);
        if ($data == null) {
            return 'fail';
        }
        $data->delete();
        return 'success';
    }
}
