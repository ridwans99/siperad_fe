<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Models\Angkatan;
use App\Models\Prodi;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    // public function index()
    // {
    //     // $data = User::where('type', '0')->get();
    //     $data = User::with(['Prodi', 'Angkatan'])->where('type', '0')->get();
    //     // dd($data->Prodi->id);

    //     $title = 'Delete User!';
    //     $text = "Are you sure you want to delete?";
    //     confirmDelete($title, $text);
    //     return view('admin/mahasiswa/index', [
    //         'title' => 'Data Mahasiswa',
    //         'data' => $data
    //     ]);
    // }

    // public function create()
    // {
    //     $prodi = Prodi::all();
    //     $angkatan = Angkatan::all();
    //     return view('admin/mahasiswa/create', [
    //         'title' => 'Tambah Data Mahasiswa',
    //         'prodi' => $prodi,
    //         'angkatan' => $angkatan
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => ['required', 'max:100'],
    //         'username' => ['required', 'max:100', 'unique:users,username'],
    //         'prodi_id' => ['required'],
    //         'type' => ['required'],
    //         'angkatan_id' => ['required'],
    //         'password' => ['required', 'min:6'],
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect('mahasiswa/tambah')
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     $validated = $validator->validated();

    //     // Hash password sebelum disimpan
    //     $validated['password'] = Hash::make($request->input('password'));

    //     User::create($validated);

    //     Alert::success('Berhasil', 'Mahasiswa Berhasil Ditambahkan');

    //     return redirect()->route('mahasiswa.index')->with('success', 'mahasiswa berhasil ditambahkan!');
    // }

    // public function edit($id)
    // {
    //     $prodi = Prodi::all();
    //     $angkatan = Angkatan::all();
    //     $data = User::findOrFail($id);
    //     // $data = User::where('id', $id)->first();
    //     return view('admin/mahasiswa/edit', [
    //         'title' => 'Edit Data mahasiswa',
    //         'prodi' => $prodi,
    //         'angkatan' => $angkatan,
    //         'data' => $data
    //     ]);
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'id' => 'required',
    //         'name' => 'required',
    //         'username' => 'required',
    //         'prodi_id' => 'required',
    //         'angkatan_id' => 'required'
    //     ]);

    //     // $data = Barang::find($id);
    //     User::where('id', $id)
    //         ->update([
    //             'name' => $request->name,
    //             'username' => $request->username,
    //             'prodi_id' => $request->prodi_id,
    //             'angkatan_id' => $request->angkatan_id
    //         ]);


    //     Alert::success('Berhasil', 'Mahasiswa Berhasil Diubah');

    //     return redirect()->route('mahasiswa.index')
    //         ->with('success', 'mahasiswa berhasil diubah!');
    // }

    // public function destroy($id)
    // {
    //     $data = User::find($id);
    //     $data->delete();

    //     Alert::success('Berhasil', 'Mahasiswa Berhasil Dihapus');

    //     return redirect()->route('mahasiswa.index')
    //         ->with('success', 'mahasiswa berhasil dihapus!');
    // }

    public function index()
    {
        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/mahasiswa/index',
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
            Alert::error('Gagal', 'Gagal mengambil data mahasiswa dari API');
            return redirect()->back();
        }

        $data = json_decode($response, true);
        // dd($data);
        return view('admin/mahasiswa/index', [
            'title' => 'Data Mahasiswa',
            'data' => $data
        ]);
    }

    public function create()
    {
        $prodi = Prodi::all();
        $angkatan = Angkatan::all();

        return view('admin/mahasiswa/create', [
            'title' => 'Tambah Data Mahasiswa',
            'prodi' => $prodi,
            'angkatan' => $angkatan
        ]);
    }

    public function store(Request $request)
    {
        $postData = [
            'name' => $request->name,
            'username' => $request->username,
            'prodi_id' => $request->prodi_id,
            'angkatan_id' => $request->angkatan_id,
            'type' => $request->type,
            'password' => $request->password
        ];

        $client = curl_init();

        curl_setopt_array($client, [
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/mahasiswa/post',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode == 201) {
            Alert::success('Berhasil', 'Mahasiswa Berhasil Ditambahkan');
            return redirect()->route('mahasiswa.index');
        } else {
            $error = json_decode($response, true);
            return redirect('mahasiswa/tambah')->withErrors($error['errors'])->withInput();
        }
    }

    public function edit($id)
    {
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/mahasiswa/{$id}",
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode != 200) {
            Alert::error('Error', 'Data tidak ditemukan');
            return redirect()->route('peminjaman-ruang.index');
        }
        // dd( $id);
        $data = json_decode($response, true);

        $prodi = Prodi::all();
        $angkatan = Angkatan::all();

        return view('admin/mahasiswa/edit', [
            'title' => 'Edit Data Mahasiswa',
            'data' => $data,
            'prodi' => $prodi,
            'angkatan' => $angkatan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $postData = [
            'name' => $request->name,
            'username' => $request->username,
            'prodi_id' => $request->prodi_id,
            'angkatan_id' => $request->angkatan_id,
        ];

        $client = curl_init();

        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/mahasiswa/{$id}",
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
            Alert::success('Berhasil', 'Mahasiswa Berhasil Diubah');
            return redirect()->route('mahasiswa.index');
        } else {
            $error = json_decode($response, true);
            return redirect()->back()->withErrors($error['errors'])->withInput();
        }
    }

    public function destroy($id)
    {
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/mahasiswa/{$id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "DELETE",
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode == 201) {
            Alert::success('Berhasil', 'Mahasiswa Berhasil Dihapus');
        } else {
            Alert::error('Gagal', 'Mahasiswa gagal dihapus');
        }

        return redirect()->route('mahasiswa.index');
    }
}
