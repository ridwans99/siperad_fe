<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruang;
use RealRashid\SweetAlert\Facades\Alert;

class RuangController extends Controller
{
    // public function index() {
    //     $data = Ruang::all();
    //     $title = 'Delete Ruangan!';
    //     $text = "Are you sure you want to delete?";
    //     confirmDelete($title, $text);
    //     return view('admin/ruang/index', [
    //         'title' => 'Data Ruang',
    //         'data' => $data
    //     ]);
    // }

    public function index()
    {
        $title = 'Delete Ruangan!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/ruang/index',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',

            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $data = [];
        $message = null;

        $decoded = json_decode($response, true);

        if ($httpCode == 200 && is_array($decoded)) {
            $data = $decoded;
        } else {
            $message = $decoded['message'] ?? 'Data tidak ditemukan.';
            Alert::warning('Info', $message);
        }


        // if ($httpCode != 200) {
        //     Alert::error('Gagal', 'Gagal mengambil data ruang dari API');
        //     return redirect()->back();
        // }

        // // Decode JSON response jadi array PHP
        // $data = json_decode($response, true);

        return view('admin/ruang/index', [
            'title' => 'Data Alat',
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('admin/ruang/create', [
            'title' => 'Tambah Data Ruang'
        ]);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nama_ruang' => 'required|min:5|max:100',
    //         'keterangan' => 'required|min:5|max:100',
    //         'status_ruang'
    //     ]);

    //     Ruang::create($request->all());
    //     Alert::success('Berhasil', 'Ruangan Berhasil Ditambahkan');

    //     return redirect()->route('ruang.index')
    //         ->with('success', 'Ruang berhasil ditambahkan!');
    // }

    public function store(Request $request)
    {
        $client = curl_init();

        $postData = [
            'nama_ruang' => $request->nama_ruang,
            'keterangan' => $request->keterangan,
            'status_ruang' => $request->status_ruang
        ];

        curl_setopt_array($client, [
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/ruang/post',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),

            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode == 201) {
            Alert::success('Berhasil', 'Ruangan Berhasil Ditambahkan');
            return redirect()->route('ruang.index');
        } else {
            $error = json_decode($response, true);
            return redirect('ruang/tambah')->withErrors($error['errors'])->withInput();
        }
    }

    // public function edit($id)
    // {
    //     $data = Ruang::where('id', $id)->first();
    //     return view('admin/ruang/edit', [
    //         'title' => 'Edit Data Ruang',
    //         'data' => $data
    //     ]);
    // }

    public function edit($id)
    {
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/ruang/{$id}",
            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode != 200) {
            Alert::error('Error', 'Data tidak ditemukan');
            return redirect()->route('ruang.index');
        }

        $data = json_decode($response, true);

        return view('admin/ruang/edit', [
            'title' => 'Edit Data Ruang',
            'data' => $data
        ]);
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'nama_ruang' => 'required|min:5|max:100',
    //         'keterangan' => 'required|min:5|max:100',
    //         'status_ruang' => 'required'
    //     ]);

    //     $data = Ruang::find($id);
    //     $data->update($request->all());
    //     Alert::success('Berhasil', 'Ruangan Berhasil Diubah');

    //     return redirect()->route('ruang.index')
    //         ->with('success', 'Ruang berhasil diubah!');
    // }

    public function update(Request $request, $id)
    {
        $postData = [
            '_method' => 'PUT',
            'nama_ruang' => $request->nama_ruang,
            'keterangan' => $request->keterangan,
            'status_ruang' => $request->status_ruang,
        ];

        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/ruang/{$id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],

            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode == 200) {
            Alert::success('Berhasil', 'Ruangan Berhasil Diubah');
            return redirect()->route('ruang.index');
        } else {
            $error = json_decode($response, true);
            return redirect()->back()->withErrors($error['errors'])->withInput();
        }
    }

    public function destroy($id)
    {
        $postData = [
            '_method' => 'DELETE', // Override method DELETE
        ];

        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/ruang/{$id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($postData),

            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode == 200) {
            Alert::success('Berhasil', 'Ruangan Berhasil Dihapus');
        } else {
            Alert::error('Gagal', 'Ruangan gagal dihapus');
        }

        return redirect()->route('ruang.index');
    }

    // public function destroy($id)
    // {
    //     $data = Ruang::findOrFail($id);
    //     $data->delete();

    //     Alert::success('Berhasil', 'Ruangan Berhasil Dihapus');

    //     return redirect()->route('ruang.index')
    //         ->with('success', 'Ruang berhasil dihapus!');
    // }
}
