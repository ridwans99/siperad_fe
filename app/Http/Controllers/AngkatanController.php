<?php

namespace App\Http\Controllers;

use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreAngkatanRequest;
use App\Http\Requests\UpdateAngkatanRequest;
use App\Models\Angkatan;

class AngkatanController extends Controller
{
    // public function index()
    // {
    //     $data = Angkatan::all();
    //     $title = 'Delete Angkatan!';
    //     $text = "Are you sure you want to delete?";
    //     confirmDelete($title, $text);
    //     return view('admin/angkatan/index', [
    //         'title' => 'Data Angkatan',
    //         'data' => $data
    //     ]);
    // }

    // public function create()
    // {
    //     return view('admin/angkatan/create', [
    //         'title' => 'Tambah Data Angkatan'
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'angkatan' => ['required', 'max:100'],

    //     ]);

    //     if ($validator->fails()) {
    //         return redirect('angkatan/tambah')
    //             ->withErrors($validator)
    //             ->withInput();
    //     }
    //     $validated = $validator->validated();
    //     Angkatan::create($validated);

    //     Alert::success('Berhasil', 'Angkatan Berhasil Ditambahkan');

    //     return redirect()->route('angkatan.index')->with('success', 'Angkatan berhasil ditambahkan!');
    // }

    // public function edit($id)
    // {
    //     $data = Angkatan::where('id', $id)->first();
    //     return view('admin/angkatan/edit', [
    //         'title' => 'Edit Data Angkatan',
    //         'data' => $data
    //     ]);
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'id' => 'required',
    //         'angkatan' => 'required|max:100',

    //     ]);

    //     // $data = Prodi::find($id);
    //     Angkatan::where('id', $id)
    //         ->update([
    //             'angkatan' => $request->angkatan,

    //         ]);

    //     Alert::success('Berhasil', 'Angkatan Berhasil Diubah');

    //     return redirect()->route('angkatan.index')
    //         ->with('success', 'Angkatan berhasil diubah!');
    // }

    // public function destroy($id)
    // {
    //     $data = Angkatan::find($id);
    //     $data->delete();

    //     Alert::success('Berhasil', 'Angkatan Berhasil Dihapus');

    //     return redirect()->route('angkatan.index')
    //         ->with('success', 'Angkatan berhasil dihapus!');
    // }

    public function index()
    {
        $title = 'Delete Angkatan!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/angkatan/index',
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
        //     Alert::error('Gagal', 'Gagal mengambil data angkatan dari API');
        //     return redirect()->back();
        // }

        // $data = json_decode($response, true);

        return view('admin/angkatan/index', [
            'title' => 'Data Angkatan',
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('admin/angkatan/create', [
            'title' => 'Tambah Data Angkatan'
        ]);
    }

    public function store(Request $request)
    {
        $client = curl_init();

        $postData = [
            'angkatan' => $request->angkatan
        ];

        curl_setopt_array($client, [
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/angkatan/post',
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
            Alert::success('Berhasil', 'Angkatan Berhasil Ditambahkan');
            return redirect()->route('angkatan.index');
        } else {
            $error = json_decode($response, true);
            return redirect('angkatan/tambah')->withErrors($error['errors'])->withInput();
        }
    }

    public function edit($id)
    {
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/angkatan/{$id}",
            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode != 200) {
            Alert::error('Error', 'Data tidak ditemukan');
            return redirect()->route('angkatan.index');
        }

        $data = json_decode($response, true);

        return view('admin/angkatan/edit', [
            'title' => 'Edit Data Angkatan',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $postData = [
            '_method' => 'PUT',
            'angkatan' => $request->angkatan
        ];

        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/angkatan/{$id}",
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
            Alert::success('Berhasil', 'Angkatan Berhasil Diubah');
            return redirect()->route('angkatan.index');
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
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/angkatan/{$id}",
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
            Alert::success('Berhasil', 'Angkatan Berhasil Dihapus');
        } else {
            Alert::error('Gagal', 'Angkatan gagal dihapus');
        }

        return redirect()->route('angkatan.index');
    }
}
