<?php

namespace App\Http\Controllers;

use App\Models\NamaDosen;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreNamaDosenRequest;
use App\Http\Requests\UpdateNamaDosenRequest;

class NamaDosenController extends Controller
{
    // public function index()
    // {
    //     $data = NamaDosen::all();
    //     $title = 'Delete Dosen!';
    //     $text = "Are you sure you want to delete?";
    //     confirmDelete($title, $text);
    //     return view('admin/dosen/index', [
    //         'title' => 'Data Dosen',
    //         'data' => $data
    //     ]);
    // }

    // public function viewdosen()
    // {
    //     $data = NamaDosen::all();
    //     return view('user/dosen/dosen', [
    //         'data' => $data
    //     ]);
    // }


    // public function create()
    // {
    //     return view('admin/dosen/create', [
    //         'title' => 'Tambah Data Dosen'
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'nama_dosen' => ['required', 'max:100'],

    //     ]);

    //     if ($validator->fails()) {
    //         return redirect('dosen/tambah')
    //             ->withErrors($validator)
    //             ->withInput();
    //     }
    //     $validated = $validator->validated();
    //     NamaDosen::create($validated);

    //     Alert::success('Berhasil', 'Dosen Berhasil Ditambahkan');

    //     return redirect()->route('dosen.index')->with('success', 'Dosen berhasil ditambahkan!');
    // }

    // public function edit($id)
    // {
    //     $data = NamaDosen::where('id', $id)->first();
    //     return view('admin/dosen/edit', [
    //         'title' => 'Edit Data Dosen',
    //         'data' => $data
    //     ]);
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'id' => 'required',
    //         'nama_dosen' => 'required|max:100',

    //     ]);

    //     // $data = Prodi::find($id);
    //     NamaDosen::where('id', $id)
    //         ->update([
    //             'nama_dosen' => $request->nama_dosen,

    //         ]);

    //     Alert::success('Berhasil', 'Dosen Berhasil Diubah');

    //     return redirect()->route('dosen.index')
    //         ->with('success', 'Dosen berhasil diubah!');
    // }

    // public function destroy($id)
    // {
    //     $data = NamaDosen::find($id);
    //     $data->delete();

    //     Alert::success('Berhasil', 'Dosen Berhasil Dihapus');

    //     return redirect()->route('dosen.index')
    //         ->with('success', 'Dosen berhasil dihapus!');
    // }

    // public function ubahstatus(Request $request)
    // {
    //     $data = NamaDosen::where('id', $request->dosen_id)->first();
    //     if ($data->kehadiran_dosen == "1") {
    //         NamaDosen::where('id', $request->dosen_id)
    //             ->update([
    //                 'kehadiran_dosen' => "0",
    //             ]);
    //     } else {
    //         NamaDosen::where('id', $request->dosen_id)
    //             ->update([
    //                 'kehadiran_dosen' => "1",
    //             ]);
    //     }


    //     Alert::success('Berhasil', 'Kehadiran Dosen Berhasil Diubah');

    //     return redirect()->route('dosen.index')
    //         ->with('success', 'Dosen berhasil diubah!');
    // }

    public function index()
    {
        $title = 'Delete Dosen!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => 'http://127.0.0.1:8000/api/dosen/index',
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode != 200) {
            Alert::error('Gagal', 'Gagal mengambil data dosen dari API');
            return redirect()->back();
        }

        $data = json_decode($response, true);
        // dd($data);

        return view('admin/dosen/index', [
            'title' => 'Data Dosen',
            'data' => $data
        ]);
    }

    public function viewdosen()
    {
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => 'http://127.0.0.1:8000/api/dosen/index',
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($client);
        $data = json_decode($response, true);
        curl_close($client);

        return view('user/dosen/dosen', [
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('admin/dosen/create', [
            'title' => 'Tambah Data Dosen'
        ]);
    }

    public function store(Request $request)
    {
        $postData = [
            'nama_dosen' => $request->nama_dosen,
        ];

        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => 'http://127.0.0.1:8000/api/dosen/post',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode == 201) {
            Alert::success('Berhasil', 'Dosen Berhasil Ditambahkan');
            return redirect()->route('dosen.index');
        } else {
            $error = json_decode($response, true);
            return redirect('dosen/tambah')->withErrors($error['errors'])->withInput();
        }
    }

    public function edit($id)
    {
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "http://127.0.0.1:8000/api/dosen/{$id}",
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode != 200) {
            Alert::error('Error', 'Data dosen tidak ditemukan');
            return redirect()->route('dosen.index');
        }

        $data = json_decode($response, true);

        return view('admin/dosen/edit', [
            'title' => 'Edit Data Dosen',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $postData = [
            'nama_dosen' => $request->nama_dosen,
        ];

        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "http://127.0.0.1:8000/api/dosen/{$id}",
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
            Alert::success('Berhasil', 'Dosen Berhasil Diubah');
            return redirect()->route('dosen.index');
        } else {
            $error = json_decode($response, true);
            return redirect()->back()->withErrors($error['errors'])->withInput();
        }
    }

    public function destroy($id)
    {
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "http://127.0.0.1:8000/api/dosen/{$id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "DELETE",
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode == 200) {
            Alert::success('Berhasil', 'Dosen Berhasil Dihapus');
        } else {
            Alert::error('Gagal', 'Gagal menghapus dosen');
        }

        return redirect()->route('dosen.index');
    }

    public function ubahstatus(Request $request)
    {
        $postData = [
            'dosen_id' => $request->dosen_id,
        ];
        $client = curl_init();
        // curl_setopt_array($client, [
        //     CURLOPT_URL => 'http://127.0.0.1:8000/api/dosen/status',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_POST => true,
        //     CURLOPT_POSTFIELDS => http_build_query($postData),
        // ]);
        curl_setopt_array($client, [
            CURLOPT_URL => "http://127.0.0.1:8000/api/dosen/status",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        // $curlError = curl_error($client);

        // $responseBody = json_decode($response, true);

        // dd([
        //     'http_code' => $httpCode,
        //     'curl_error' => $curlError,
        //     'response' => $response,
        //     'decoded' => $responseBody,
        // ]);

        if ($httpCode == 200) {
            Alert::success('Berhasil', 'Kehadiran Dosen Berhasil Diubah');
        } else {
            // dd($response);
            Alert::error('Gagal', 'Gagal mengubah kehadiran dosen');
        }

        return redirect()->route('dosen.index');
    }
}
