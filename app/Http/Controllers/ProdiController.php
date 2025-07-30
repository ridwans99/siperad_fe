<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProdiRequest;
use App\Http\Requests\UpdateProdiRequest;
use App\Models\PeminjamanBarang;
use App\Models\PeminjamanRuang;
use App\Models\User;

class ProdiController extends Controller
{
    // public function index()
    // {
    //     $data = Prodi::all();
    //     $title = 'Delete Prodi!';
    //     $text = "Are you sure you want to delete?";
    //     confirmDelete($title, $text);
    //     return view('admin/prodi/index', [
    //         'title' => 'Data Prodi',
    //         'data' => $data
    //     ]);
    // }

    // public function create()
    // {
    //     return view('admin/prodi/create', [
    //         'title' => 'Tambah Data Prodi'
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'nama_prodi' => ['required', 'max:100'],

    //     ]);

    //     if ($validator->fails()) {
    //         return redirect('prodi/tambah')
    //             ->withErrors($validator)
    //             ->withInput();
    //     }
    //     $validated = $validator->validated();
    //     Prodi::create($validated);

    //     Alert::success('Berhasil', 'Ruangan Berhasil Ditambahkan');

    //     return redirect()->route('prodi.index')->with('success', 'Prodi berhasil ditambahkan!');
    // }

    // public function edit($id)
    // {
    //     $data = Prodi::where('id', $id)->first();
    //     return view('admin/prodi/edit', [
    //         'title' => 'Edit Data Prodi',
    //         'data' => $data
    //     ]);
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'id' => 'required',
    //         'nama_prodi' => 'required|max:100',

    //     ]);

    //     // $data = Prodi::find($id);
    //     Prodi::where('id', $id)
    //         ->update([
    //             'nama_prodi' => $request->nama_prodi,

    //         ]);

    //     Alert::success('Berhasil', 'Ruangan Berhasil Diubah');

    //     return redirect()->route('prodi.index')
    //         ->with('success', 'Prodi berhasil diubah!');
    // }

    // public function destroy($id)
    // {
    //     $data = Prodi::find($id);
    //     $data->delete();

    //     PeminjamanBarang::where('prodi_id', $id)->delete();
    //     PeminjamanRuang::where('prodi_id', $id)->delete();
    //     User::where('prodi_id', $id)->delete();

    //     Alert::success('Berhasil', 'Ruangan Berhasil Dihapus');

    //     return redirect()->route('prodi.index')
    //         ->with('success', 'Prodi berhasil dihapus!');
    // }

    public function index()
    {
        $title = 'Delete Prodi!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/prodi/index',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode != 200) {
            Alert::error('Gagal', 'Gagal mengambil data prodi dari API');
            return redirect()->back();
        }

        $data = json_decode($response, true);
        return view('admin/prodi/index', [
            'title' => 'Data Prodi',
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('admin/prodi/create', [
            'title' => 'Tambah Data Prodi'
        ]);
    }

    public function store(Request $request)
    {
        $postData = [
            'nama_prodi' => $request->nama_prodi,
        ];

        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/prodi/post',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode == 201) {
            Alert::success('Berhasil', 'Prodi Berhasil Ditambahkan');
            return redirect()->route('prodi.index');
        } else {
            $error = json_decode($response, true);
            return redirect('prodi/tambah')->withErrors($error['errors'])->withInput();
        }
    }

    public function edit($id)
    {
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/prodi/{$id}",
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode != 200) {
            Alert::error('Error', 'Data tidak ditemukan');
            return redirect()->route('prodi.index');
        }

        $data = json_decode($response, true);

        return view('admin/prodi/edit', [
            'title' => 'Edit Data Prodi',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $postData = [
            'nama_prodi' => $request->nama_prodi,
        ];

        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/prodi/{$id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode == 200) {
            Alert::success('Berhasil', 'Prodi Berhasil Diubah');
            return redirect()->route('prodi.index');
        } else {
            $error = json_decode($response, true);
            return redirect()->back()->withErrors($error['errors'])->withInput();
        }
    }

    public function destroy($id)
    {
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/prodi/{$id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "DELETE",
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode == 200) {
            Alert::success('Berhasil', 'Prodi Berhasil Dihapus');
        } else {
            Alert::error('Gagal', 'Prodi gagal dihapus');
        }

        return redirect()->route('prodi.index');
    }
}
