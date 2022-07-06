<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\rab;
use Illuminate\Support\Facades\Validator;

class RabController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => ['required', 'max:255'],
            'anggaran' => ['required', 'max:255'],
            'akun' => ['required', 'max:255'],

        ]);
        if ($validator->fails()) {
            $data = ['status' => 'error', 'data' => $validator->errors()];
            return $data;
        }

        $user = rab::create([
            'anggaran' => $request->anggaran,
            'realisasi' => $request->realisasi,
            'sisa' => $request->anggaran - $request->realisasi,
            'id_rkakl' => $request->id_rkakl,
            'id_akun' => $request->akun,
            'id_kegiatan' => $request->kegiatan,
            'id_kro' => $request->kro,
            'ro' => $request->ro,
            'komponen' => $request->komponen,
            'sub_komponen' => $request->subkomponen,
            'tanggal' => $request->tanggal,
            'status' => 0,
            'keterangan' => $request->keterangan

        ]);
        if ($user) {
            return 'success';
        }
    }
    public function update(Request $request)
    {
        $data = rab::findorfail($request->id);
        $validator = Validator::make($request->all(), [
            'tanggal' => ['required', 'max:255'],

        ]);
        if ($validator->fails()) {
            $data = ['status' => 'error', 'data' => $validator->errors()];
            return $data;
        }
        $data->anggaran = $request->anggaran;
        $data->realisasi = $request->realisasi;
        $data->sisa = $request->anggaran - $request->realisasi;
        $data->id_akun = $request->akun;
        $data->id_kegiatan = $request->kegiatan;
        $data->id_kro = $request->kro;
        $data->ro = $request->ro;
        $data->komponen = $request->komponen;
        $data->sub_komponen = $request->subkomponen;
        $data->tanggal = $request->tanggal;
        $data->status = 0;
        $data->keterangan = $request->keterangan;
        $data->updated_at = date('Y-m-d G:i:s');

        $data->save();

        return 'success';
    }
    public function destroy($id)
    {
        $data = rab::findorfail($id);
        if ($data == null) {
            return 'fail';
        }
        $data->delete();
        return 'success';
    }
}
