<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreBarangRequest;
use App\Http\Requests\UpdateBarangRequest;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    // public function index()
    // {
    //     // $data = Barang::all();
    //     // $title = 'Delete Alat!';
    //     // $text = "Are you sure you want to delete?";
    //     // confirmDelete($title, $text);
    //     $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/alat/index',
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => 'GET',
    //     ));

    //     $response = curl_exec($curl);

    //     curl_close($curl);
    //     echo $response;
    //     if (auth()->user()->type == '1') {
    //         return view('admin/barang/index', [
    //             'title' => 'Data Alat',
    //             'data' => $data
    //         ]);
    //     } else {
    //         // dd($ruanganList);
    //         return view('user/alat/view', [
    //             'data' => $data
    //         ]);
    //     }
    // }

    public function index()
    {
        $title = 'Delete Alat!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/alat/index',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',

            // CURLOPT_SSL_VERIFYPEER => false,
            // CURLOPT_SSL_VERIFYHOST => false,
        ));

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // if ($response === false) {
        //     $error = curl_error($curl);
        //     dd("cURL Error: " . $error);
        // }
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
        //     Alert::error('Gagal', 'Gagal mengambil data alat dari API');
        //     return redirect()->back();
        // }

        // // Decode JSON response jadi array PHP
        // $data = json_decode($response, true);

        if (auth()->user()->type == '1') {
            return view('admin/barang/index', [
                'title' => 'Data Alat',
                'data' => $data
            ]);
        } else {
            return view('user/alat/view', [
                'data' => $data
            ]);
        }
    }


    public function create()
    {
        return view('admin/barang/create', [
            'title' => 'Tambah Data Alat'
        ]);
    }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'nama_barang' => ['required', 'max:100'],
    //         'deskripsi_barang' => ['required', 'max:100'],
    //         'status_barang' => ['required'],
    //         'stok' => ['required', 'numeric', 'min:0'],
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect('barang/tambah')
    //             ->withErrors($validator)
    //             ->withInput();
    //     }
    //     $validated = $validator->validated();
    //     Barang::create($validated);

    //     Alert::success('Berhasil', 'Barang Berhasil Ditambahkan');

    //     return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    // }

    public function store(Request $request)
    {
        $client = curl_init();

        $postData = [
            'nama_barang' => $request->nama_barang,
            'deskripsi_barang' => $request->deskripsi_barang,
            'status_barang' => $request->status_barang,
            'stok' => $request->stok,
        ];

        curl_setopt_array($client, [
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/alat/post',
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
            Alert::success('Berhasil', 'Barang Berhasil Ditambahkan');
            return redirect()->route('barang.index');
        } else {
            $error = json_decode($response, true);
            return redirect('barang/tambah')->withErrors($error['errors'])->withInput();
        }
    }

    // public function edit($id)
    // {
    //     $data = Barang::where('id', $id)->first();
    //     return view('admin/barang/edit', [
    //         'title' => 'Edit Data Alat',
    //         'data' => $data
    //     ]);
    // }

    public function edit($id)
    {
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/alat/{$id}",
            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        // if ($httpCode != 200) {
        //     dd([
        //         'http_code' => $httpCode,
        //         'response' => $response
        //     ]);
        // }

        if ($httpCode != 200) {
            Alert::error('Error', 'Data tidak ditemukan');
            return redirect()->route('barang.index');
        }

        $data = json_decode($response, true);

        return view('admin/barang/edit', [
            'title' => 'Edit Data Alat',
            'data' => $data
        ]);
    }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'id' => 'required',
    //         'nama_barang' => 'required|max:100',
    //         'deskripsi_barang',
    //         'status_barang' => 'required',
    //         'stok' => 'required'
    //     ]);

    //     // $data = Barang::find($id);
    //     Barang::where('id', $id)
    //         ->update([
    //             'nama_barang' => $request->nama_barang,
    //             'deskripsi_barang' => $request->deskripsi_barang,
    //             'status_barang' => $request->status_barang,
    //             'stok' => $request->stok
    //         ]);

    //     Alert::success('Berhasil', 'Barang Berhasil Diubah');

    //     return redirect()->route('barang.index')
    //         ->with('success', 'Barang berhasil diubah!');
    // }

    // yg ini kepake
    // public function update(Request $request, $id)
    // {
    //     $postData = [
    //         'nama_barang' => $request->nama_barang,
    //         'deskripsi_barang' => $request->deskripsi_barang,
    //         'status_barang' => $request->status_barang,
    //         'stok' => $request->stok,
    //     ];

    //     $client = curl_init();
    //     curl_setopt_array($client, [
    //         CURLOPT_URL => "http://fmipa.unj.ac.id/siperad-be/api/alat/{$id}",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_CUSTOMREQUEST => 'PUT',
    //         CURLOPT_POSTFIELDS => http_build_query($postData),
    //         CURLOPT_HTTPHEADER => [
    //             'Content-Type: application/x-www-form-urlencoded',
    //         ],
    //         CURLOPT_SSL_VERIFYPEER => false,
    //         CURLOPT_SSL_VERIFYHOST => false,
    //     ]);

    //     $response = curl_exec($client);
    //     $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
    //     curl_close($client);

    //     if ($httpCode == 200) {
    //         Alert::success('Berhasil', 'Barang Berhasil Diubah');
    //         return redirect()->route('barang.index');
    //     } else {
    //         $error = json_decode($response, true);
    //         return redirect()->back()->withErrors($error['errors'])->withInput();
    //     }
    // }

    public function update(Request $request, $id)
    {
        $postData = [
            '_method' => 'PUT',
            'nama_barang' => $request->nama_barang,
            'deskripsi_barang' => $request->deskripsi_barang,
            'status_barang' => $request->status_barang,
            'stok' => $request->stok,
        ];

        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/alat/{$id}", // gunakan HTTPS
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST', 
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json',
                // 'Authorization: Bearer ' . $token, // aktifkan jika API membutuhkan autentikasi
            ],
            
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);

        if (curl_errno($client)) {
            $errorMsg = curl_error($client);
            curl_close($client);
            return redirect()->back()->withErrors(['curl_error' => $errorMsg])->withInput();
        }

        curl_close($client);

        if (in_array($httpCode, [200, 202])) {
            Alert::success('Berhasil', 'Barang Berhasil Diubah');
            return redirect()->route('barang.index');
        } else {
            $error = json_decode($response, true);
            return redirect()->back()
                ->withErrors($error['errors'] ?? ['update' => 'Gagal memperbarui barang.'])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $postData = [
            '_method' => 'DELETE',
        ];

        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/alat/{$id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($postData),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json',
                // 'Authorization: Bearer ' . $token, // jika backend pakai auth
            ],

            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);

        if (curl_errno($client)) {
            $errorMsg = curl_error($client);
            curl_close($client);
            return redirect()->route('barang.index')
                ->withErrors(['curl_error' => $errorMsg]);
        }

        curl_close($client);

        if (in_array($httpCode, [200, 202, 204])) {
            Alert::success('Berhasil', 'Barang Berhasil Dihapus');
        } else {
            $error = json_decode($response, true);
            Alert::error('Gagal', $error['message'] ?? 'Barang gagal dihapus.');
        }

        return redirect()->route('barang.index');
    }


    // yg ini kepake
    // public function destroy($id)
    // {
    //     $client = curl_init();
    //     curl_setopt_array($client, [
    //         CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/alat/{$id}",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_CUSTOMREQUEST => "DELETE",

    //         CURLOPT_SSL_VERIFYPEER => false,
    //         CURLOPT_SSL_VERIFYHOST => false,
    //     ]);

    //     $response = curl_exec($client);
    //     $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
    //     curl_close($client);

    //     if ($httpCode == 200) {
    //         Alert::success('Berhasil', 'Barang Berhasil Dihapus');
    //     } else {
    //         Alert::error('Gagal', 'Barang gagal dihapus');
    //     }

    //     return redirect()->route('barang.index');
    // }

    // public function destroy($id)
    // {
    //     $data = Barang::find($id);
    //     $data->delete();

    //     Alert::success('Berhasil', 'Barang Berhasil Dihapus');

    //     return redirect()->route('barang.index')
    //         ->with('success', 'Barang berhasil dihapus!');
    // }
}
