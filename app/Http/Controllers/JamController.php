<?php

namespace App\Http\Controllers;

use App\Models\Jam;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use App\Http\Requests\StoreJamRequest;
use App\Http\Requests\UpdateJamRequest;

class JamController extends Controller
{
    // public function index()
    // {
    //     $data = Jam::all();
    //     $title = 'Delete Jam!';
    //     $text = "Are you sure you want to delete?";
    //     confirmDelete($title, $text);
    //     return view('admin/jam/index', [
    //         'title' => 'Data Jam',
    //         'data' => $data
    //     ]);
    // }

    // public function create()
    // {
    //     return view('admin/jam/create', [
    //         'title' => 'Tambah Data Jam'
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'jam' => ['required'],

    //     ]);

    //     if ($validator->fails()) {
    //         return redirect('jam/tambah')
    //             ->withErrors($validator)
    //             ->withInput();
    //     }
    //     $validated = $validator->validated();
    //     Jam::create($validated);

    //     Alert::success('Berhasil', 'Jam Berhasil Ditambahkan');

    //     return redirect()->route('jam.index')->with('success', 'Jam berhasil ditambahkan!');
    // }

    // public function edit($id)
    // {
    //     $data = Jam::where('id', $id)->first();
    //     return view('admin/jam/edit', [
    //         'title' => 'Edit Data Jam',
    //         'data' => $data
    //     ]);
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'id' => 'required',
    //         'jam' => 'required',

    //     ]);

    //     // $data = Prodi::find($id);
    //     Jam::where('id', $id)
    //         ->update([
    //             'jam' => $request->jam,

    //         ]);

    //     Alert::success('Berhasil', 'Jam Berhasil Diubah');

    //     return redirect()->route('jam.index')
    //         ->with('success', 'Jam berhasil diubah!');
    // }

    // public function destroy($id)
    // {
    //     $data = Jam::find($id);
    //     $data->delete();

    //     Alert::success('Berhasil', 'Jam Berhasil Dihapus');

    //     return redirect()->route('jam.index')
    //         ->with('success', 'Jam berhasil dihapus!');
    // }

    public function index()
    {
        $title = 'Delete Jam!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/jam/index',
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


        // if ($httpCode != 201) {
        //     Alert::error('Gagal', 'Gagal mengambil data jam dari API');
        //     return redirect()->back();
        // }

        // $data = json_decode($response, true);

        return view('admin/jam/index', [
            'title' => 'Data Jam',
            'data' => $data
        ]);
    }

    public function create()
    {
        return view('admin/jam/create', [
            'title' => 'Tambah Data Jam'
        ]);
    }

    public function store(Request $request)
    {
        $postData = [
            'jam' => $request->jam,
        ];

        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/jam/post',
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
            Alert::success('Berhasil', 'Jam Berhasil Ditambahkan');
            return redirect()->route('jam.index');
        } else {
            $error = json_decode($response, true);
            return redirect('jam/tambah')->withErrors($error['errors'])->withInput();
        }
    }

    public function edit($id)
    {
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/jam/{$id}",
            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode != 200) {
            Alert::error('Error', 'Data tidak ditemukan');
            return redirect()->route('jam.index');
        }

        $data = json_decode($response, true);

        return view('admin/jam/edit', [
            'title' => 'Edit Data Jam',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $postData = [
            '_method' => 'PUT',
            'jam' => $request->jam,
        ];

        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/jam/{$id}",
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
            Alert::success('Berhasil', 'Jam Berhasil Diubah');
            return redirect()->route('jam.index');
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
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/jam/{$id}",
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
            Alert::success('Berhasil', 'Jam Berhasil Dihapus');
        } else {
            Alert::error('Gagal', 'Jam gagal dihapus');
        }

        return redirect()->route('jam.index');
    }
}
