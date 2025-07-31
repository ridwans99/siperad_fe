<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreMataKuliahRequest;
use App\Http\Requests\UpdateMataKuliahRequest;

class MataKuliahController extends Controller
{
    // public function index()
    // {
    //     $data = MataKuliah::all();
    //     $title = 'Delete Mata Kuliah!';
    //     $text = "Are you sure you want to delete?";
    //     confirmDelete($title, $text);
    //     return view('admin/mata-kuliah/index', [
    //         'title' => 'Data Mata Kuliah',
    //         'data' => $data
    //     ]);
    // }

    public function index()
    {
        $title = 'Delete Mata Kuliah!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/matakuliah/index',
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
        //     Alert::error('Gagal', 'Gagal mengambil data Mata Kuliah dari API');
        //     // return redirect()->back();
        // }

        // // Decode JSON response jadi array PHP
        // $data = json_decode($response, true);

        return view('admin/mata-kuliah/index', [
            'title' => 'Data Mata Kuliah',
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('admin/mata-kuliah/create', [
            'title' => 'Tambah Data Mata Kuliah'
        ]);
    }

    public function store(Request $request)
    {
        $client = curl_init();

        $postData = [
            'mata_kuliah' => $request->mata_kuliah,
        ];

        curl_setopt_array($client, [
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/matakuliah/post',
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
            Alert::success('Berhasil', 'Mata Kuliah Berhasil Ditambahkan');
            return redirect()->route('matkul.index');
        } else {
            $error = json_decode($response, true);
            return redirect('matkul/tambah')->withErrors($error['errors'])->withInput();
        }
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'mata_kuliah' => ['required', 'max:100'],

    //     ]);

    //     if ($validator->fails()) {
    //         return redirect('matkul/tambah')
    //             ->withErrors($validator)
    //             ->withInput();
    //     }
    //     $validated = $validator->validated();
    //     MataKuliah::create($validated);

    //     Alert::success('Berhasil', 'Mata Kuliah Berhasil Ditambahkan');

    //     return redirect()->route('matkul.index')->with('success', 'Mata Kuliah berhasil ditambahkan!');
    // }

    public function edit($id)
    {
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/matakuliah/{$id}",
            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode != 200) {
            Alert::error('Error', 'Data tidak ditemukan');
            return redirect()->route('matakuliah.index');
        }

        $data = json_decode($response, true);

        return view('admin/mata-kuliah/edit', [
            'title' => 'Edit Data Mata Kuliah',
            'data' => $data
        ]);
    }

    // public function edit($id)
    // {
    //     $data = MataKuliah::where('id', $id)->first();
    //     return view('admin/mata-kuliah/edit', [
    //         'title' => 'Edit Data Mata Kuliah',
    //         'data' => $data
    //     ]);
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'id' => 'required',
    //         'mata_kuliah' => 'required|max:100',

    //     ]);

    //     // $data = Prodi::find($id);
    //     MataKuliah::where('id', $id)
    //         ->update([
    //             'mata_kuliah' => $request->mata_kuliah,

    //         ]);

    //     Alert::success('Berhasil', 'Mata Kuliah Berhasil Diubah');

    //     return redirect()->route('matkul.index')
    //         ->with('success', 'Mata Kuliah berhasil diubah!');
    // }

    public function update(Request $request, $id)
    {
        $postData = [
            '_method' => 'PUT',
            'mata_kuliah' => $request->mata_kuliah,
        ];

        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/matakuliah/{$id}",
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
            Alert::success('Berhasil', 'Mata Kuliah Berhasil Diubah');
            return redirect()->route('matkul.index');
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
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/matakuliah/{$id}",
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
            Alert::success('Berhasil', 'Mata Kuliah Berhasil Dihapus');
        } else {
            Alert::error('Gagal', 'Mata Kuliah gagal dihapus');
        }

        return redirect()->route('matkul.index');
    }

    // public function destroy($id)
    // {
    //     $data = MataKuliah::find($id);
    //     $data->delete();

    //     Alert::success('Berhasil', 'Mata Kuliah Berhasil Dihapus');

    //     return redirect()->route('matkul.index')
    //         ->with('success', 'Mata Kuliah berhasil dihapus!');
    // }
}
