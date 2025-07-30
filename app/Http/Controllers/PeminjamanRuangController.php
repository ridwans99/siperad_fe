<?php

namespace App\Http\Controllers;

use App\Models\Angkatan;
use App\Models\Jam;
use App\Models\MataKuliah;
use App\Models\NamaDosen;
use App\Models\PeminjamanRuang;
use App\Models\Prodi;
use Illuminate\Http\Request;
use App\Models\Ruang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class PeminjamanRuangController extends Controller
{
    // public function index(Request $request)
    // {
    //     $data = PeminjamanRuang::with(['Prodi', 'Angkatan', 'Ruang', 'Matkul', 'Dosen', 'Jamx', 'Jamy'])->get();
    //     $title = 'Delete Peminjaman Ruangan!';
    //     $text = "Are you sure you want to delete?";
    //     confirmDelete($title, $text);
    //     if (auth()->user()->type == '1') {
    //         return view('admin/peminjaman-ruang/index', [
    //             'title' => 'Data Peminjaman Ruang',
    //             'data' => $data
    //         ]);
    //     } else {
    //         $query = PeminjamanRuang::with(['Prodi', 'Angkatan', 'Ruang', 'Matkul', 'Dosen', 'Jamx', 'Jamy'])
    //             ->where('nama_peminjam', auth()->user()->name);

    //         if ($request->has('cari')) {
    //             $query->where(function ($q) use ($request) {
    //                 $q->where('nama_peminjam', 'LIKE', '%' . $request->cari . '%')
    //                     ->orWhere('nama_peminjam', 'LIKE', '%' . $request->cari . '%');
    //             });
    //         }

    //         // if ($request->has('cari')) {
    //         //     $data = PeminjamanRuang::where('nama_peminjam', 'LIKE', '%' . $request->cari . '%')->get();
    //         // } else {
    //         //     $data = PeminjamanRuang::all();
    //         // }

    //         $data = $query->get();
    //         // dd($prodi);
    //         return view('user/data/peminjamanruang', [
    //             'data' => $data
    //         ]);
    //     }
    // }

    // public function create()
    // {
    //     $prodi = Prodi::all();
    //     $ruang = Ruang::all();
    //     $jam = Jam::all();
    //     $matkul = MataKuliah::all();
    //     $dosen =  NamaDosen::all();
    //     $angkatan = Angkatan::all();
    //     return view('admin/peminjaman-ruang/create', [
    //         'title' => 'Tambah Peminjaman ruang',
    //         'ruang' => $ruang,
    //         'prodi' => $prodi,
    //         'jam_mulai' => $jam,
    //         'jam_selesai' => $jam,
    //         'matkul' => $matkul,
    //         'dosen' => $dosen,
    //         'angkatan' => $angkatan,
    //     ]);
    // }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'nama_peminjam' => ['required'],
    //         'tgl_peminjaman' => ['required'],
    //         'ruang_id' => ['required'],
    //         'matkul_id' => ['required'],
    //         'dosen_id' => ['required'],
    //         'jam_mulai_id' => ['required'],
    //         'jam_selesai_id' => ['required'],
    //         'prodi_id' => ['required'],
    //         'angkatan_id' => ['required'],
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     // Ambil jam mulai dan jam selesai dari tabel jam
    //     $jamMulai = Jam::find($request->jam_mulai_id);
    //     $jamSelesai = Jam::find($request->jam_selesai_id);

    //     // ======================
    //     // CEK BENTROK PINJAMAN
    //     // ======================
    //     $cekBentrokPeminjaman = PeminjamanRuang::where('tgl_peminjaman', $request->tgl_peminjaman)
    //         ->where('ruang_id', $request->ruang_id)
    //         ->whereHas('Jamx', function ($q) use ($jamSelesai) {
    //             $q->where('jam', '<', $jamSelesai->jam);
    //         })
    //         ->whereHas('Jamy', function ($q) use ($jamMulai) {
    //             $q->where('jam', '>', $jamMulai->jam);
    //         })
    //         ->exists();

    //     if ($cekBentrokPeminjaman) {
    //         if (auth()->user()->type == '1') {
    //             Alert::error('Jadwal Bentrok', 'Ruangan sudah digunakan di jam tersebut (jadwal peminjaman).');
    //             return redirect()->back()->withInput();
    //         } else {
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->with('alert', [
    //                     'title' => 'Jadwal Bentrok',
    //                     'text' => 'Ruangan sudah digunakan di jam tersebut (jadwal peminjaman).',
    //                     'icon' => 'error'
    //                 ]);
    //         }
    //     }

    //     // ==========================
    //     // CEK BENTROK JADWAL TETAP
    //     // ==========================
    //     $hari = strtolower(Carbon::parse($request->tgl_peminjaman)->isoFormat('dddd'));

    //     $cekBentrokJadwalTetap = \App\Models\JadwalRuangan::where('hari', $hari)
    //         ->where('ruang_id', $request->ruang_id)
    //         ->whereHas('Jamx', function ($q) use ($jamSelesai) {
    //             $q->where('jam', '<', $jamSelesai->jam);
    //         })
    //         ->whereHas('Jamy', function ($q) use ($jamMulai) {
    //             $q->where('jam', '>', $jamMulai->jam);
    //         })
    //         ->exists();

    //     if ($cekBentrokJadwalTetap) {
    //         if (auth()->user()->type == '1') {
    //             Alert::error('Jadwal Bentrok', 'Ruangan sudah digunakan di jam tersebut (jadwal peminjaman');
    //             return redirect()->back()->withInput();
    //         } else {
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->with('alert', [
    //                     'title' => 'Jadwal Bentrok',
    //                     'text' => 'Ruangan sudah digunakan di jam tersebut (jadwal peminjaman).',
    //                     'icon' => 'error'
    //                 ]);
    //         }
    //     }

    //     // ==========================
    //     // CEK JAM
    //     // ==========================
    //     if (strtotime($jamMulai->jam) >= strtotime($jamSelesai->jam)) {
    //         if (auth()->user()->type == '1') {
    //             Alert::error('Waktu Tidak Valid', 'Jam mulai harus lebih awal dari jam selesai.');
    //             return redirect()->back()->withInput();
    //         } else {
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->with('alert', [
    //                     'title' => 'Waktu Tidak Valid',
    //                     'text' => 'Jam mulai harus lebih awal dari jam selesai.',
    //                     'icon' => 'error'
    //                 ]);
    //         }
    //     }

    //     // ==========================
    //     // CEK HARI PEMINJAMAN
    //     // ==========================
    //     if (Carbon::parse($request->tgl_peminjaman)->isBefore(Carbon::today())) {
    //         if (auth()->user()->type == '1') {
    //             Alert::error('Tanggal Tidak Valid', 'Peminjaman hanya bisa dilakukan untuk hari ini atau setelahnya.');
    //             return redirect()->back()->withInput();
    //         } else {
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->with('alert', [
    //                     'title' => 'Tanggal Tidak Valid',
    //                     'text' => 'Peminjaman hanya bisa dilakukan untuk hari ini atau setelahnya.',
    //                     'icon' => 'error'
    //                 ]);
    //         }
    //     }

    //     // ==========================
    //     // CEK JAM OPERASIONAL
    //     // ==========================
    //     $jamOperasionalMulai = strtotime('07:30:00');
    //     $jamOperasionalSelesai = strtotime('19:00:00');

    //     if (
    //         strtotime($jamMulai->jam) < $jamOperasionalMulai ||
    //         strtotime($jamSelesai->jam) > $jamOperasionalSelesai
    //     ) {
    //         if (auth()->user()->type == '1') {
    //             Alert::error('Diluar Jam Operasional', 'Jam peminjaman hanya boleh antara pukul 07:30 sampai 19:00.');
    //             return redirect()->back()->withInput();
    //         } else {
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->with('alert', [
    //                     'title' => 'Diluar Jam Operasional',
    //                     'text' => 'Jam peminjaman hanya boleh antara pukul 07:30 sampai 19:00.',
    //                     'icon' => 'error'
    //                 ]);
    //         }
    //     }

    //     // =====================
    //     // JIKA AMAN, SIMPAN DATA
    //     // =====================
    //     PeminjamanRuang::create($request->all());

    //     Alert::success('Berhasil', 'Peminjaman Ruangan Berhasil Ditambahkan');

    //     if (auth()->user()->type == '1') {
    //         return redirect()->route('peminjaman-ruang.index')
    //             ->with('success', 'Peminjaman berhasil ditambahkan!');
    //     } else {
    //         return redirect()->route('user.home')->with('success', 'Peminjaman berhasil ditambahkan!');
    //     }

    //     // return redirect(auth()->user()->type == '1' ? route('peminjaman-ruang.index') : route('user.home'))
    //     //     ->with('success', 'Peminjaman berhasil ditambahkan!');
    // }

    // public function edit($id)
    // {
    //     $ruang = Ruang::all();
    //     $prodi = Prodi::all();
    //     $matkul = MataKuliah::all();
    //     $dosen = NamaDosen::all();
    //     $jam = Jam::all();
    //     $angkatan = Angkatan::all();
    //     $data = PeminjamanRuang::findOrFail($id);
    //     return view('admin/peminjaman-ruang/edit', [
    //         'title' => 'Ubah Peminjaman Ruang',
    //         'ruang' => $ruang,
    //         'prodi' => $prodi,
    //         'matkul' => $matkul,
    //         'dosen' => $dosen,
    //         'jam' => $jam,
    //         'angkatan' => $angkatan,
    //         'data' => $data,
    //     ]);
    // }

    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id' => 'required',
    //         'nama_peminjam' => 'required',
    //         'tgl_peminjaman' => 'required',
    //         'ruang_id' => 'required',
    //         'matkul_id' => 'required',
    //         'dosen_id' => 'required',
    //         'jam_mulai_id' => 'required',
    //         'jam_selesai_id' => 'required',
    //         'prodi_id' => 'required',
    //         'angkatan_id' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     $jamMulai = Jam::find($request->jam_mulai_id);
    //     $jamSelesai = Jam::find($request->jam_selesai_id);
    //     $tgl = $request->tgl_peminjaman;

    //     // ======================
    //     // CEK BENTROK PEMINJAMAN (kecuali ID sendiri)
    //     // ======================
    //     $cekBentrokPeminjaman = PeminjamanRuang::where('id', '!=', $id)
    //         ->where('tgl_peminjaman', $tgl)
    //         ->where('ruang_id', $request->ruang_id)
    //         ->whereHas('Jamx', fn($q) => $q->where('jam', '<', $jamSelesai->jam))
    //         ->whereHas('Jamy', fn($q) => $q->where('jam', '>', $jamMulai->jam))
    //         ->exists();

    //     if ($cekBentrokPeminjaman) {
    //         Alert::error('Jadwal Bentrok', 'Ruangan sudah digunakan di jam tersebut (jadwal peminjaman).');
    //         return redirect()->back()->withInput();
    //     }

    //     // ======================
    //     // CEK BENTROK JADWAL TETAP
    //     // ======================
    //     $hari = strtolower(Carbon::parse($tgl)->isoFormat('dddd'));
    //     $cekBentrokJadwalTetap = \App\Models\JadwalRuangan::where('hari', $hari)
    //         ->where('ruang_id', $request->ruang_id)
    //         ->whereHas('Jamx', fn($q) => $q->where('jam', '<', $jamSelesai->jam))
    //         ->whereHas('Jamy', fn($q) => $q->where('jam', '>', $jamMulai->jam))
    //         ->exists();

    //     if ($cekBentrokJadwalTetap) {
    //         Alert::error('Jadwal Bentrok', 'Ruangan sudah digunakan di jam tersebut (jadwal tetap).');
    //         return redirect()->back()->withInput();
    //     }

    //     // ==========================
    //     // CEK JAM
    //     // ==========================
    //     if (strtotime($jamMulai->jam) >= strtotime($jamSelesai->jam)) {
    //         if (auth()->user()->type == '1') {
    //             Alert::error('Waktu Tidak Valid', 'Jam mulai harus lebih awal dari jam selesai.');
    //             return redirect()->back()->withInput();
    //         } else {
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->with('alert', [
    //                     'title' => 'Waktu Tidak Valid',
    //                     'text' => 'Jam mulai harus lebih awal dari jam selesai.',
    //                     'icon' => 'error'
    //                 ]);
    //         }
    //     }

    //     // ==========================
    //     // CEK HARI PEMINJAMAN
    //     // ==========================
    //     if (Carbon::parse($request->tgl_peminjaman)->isBefore(Carbon::today())) {
    //         if (auth()->user()->type == '1') {
    //             Alert::error('Tanggal Tidak Valid', 'Peminjaman hanya bisa dilakukan untuk hari ini atau setelahnya.');
    //             return redirect()->back()->withInput();
    //         } else {
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->with('alert', [
    //                     'title' => 'Tanggal Tidak Valid',
    //                     'text' => 'Peminjaman hanya bisa dilakukan untuk hari ini atau setelahnya.',
    //                     'icon' => 'error'
    //                 ]);
    //         }
    //     }

    //     // ==========================
    //     // CEK JAM OPERASIONAL
    //     // ==========================
    //     $jamOperasionalMulai = strtotime('07:30:00');
    //     $jamOperasionalSelesai = strtotime('19:00:00');

    //     if (
    //         strtotime($jamMulai->jam) < $jamOperasionalMulai ||
    //         strtotime($jamSelesai->jam) > $jamOperasionalSelesai
    //     ) {
    //         if (auth()->user()->type == '1') {
    //             Alert::error('Diluar Jam Operasional', 'Jam peminjaman hanya boleh antara pukul 07:30 sampai 19:00.');
    //             return redirect()->back()->withInput();
    //         } else {
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->with('alert', [
    //                     'title' => 'Diluar Jam Operasional',
    //                     'text' => 'Jam peminjaman hanya boleh antara pukul 07:30 sampai 19:00.',
    //                     'icon' => 'error'
    //                 ]);
    //         }
    //     }

    //     // =====================
    //     // UPDATE JIKA AMAN
    //     // =====================
    //     $data = PeminjamanRuang::findOrFail($id);
    //     $data->update($request->all());

    //     Alert::success('Berhasil', 'Peminjaman Ruangan Berhasil Diubah');

    //     return redirect()->route('peminjaman-ruang.index')
    //         ->with('success', 'Peminjaman berhasil diubah!');
    // }


    // public function destroy($id)
    // {
    //     $data = PeminjamanRuang::findOrFail($id);
    //     $data->delete();

    //     Alert::success('Berhasil', 'Peminjaman Berhasil Dihapus');

    //     return redirect()->route('peminjaman-ruang.index')
    //         ->with('success', 'Peminjaman berhasil dihapus!');
    // }

    public function index()
    {
        $title = 'Delete Peminjaman Ruangan!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);

        $client = curl_init();

        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/peminjamanruang/index",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode != 200) {
            Alert::error('Gagal', 'Gagal mengambil data peminjaman ruang dari API');
            return redirect()->back();
        }

        $data = json_decode($response, true);
        // dd($data);

        if (auth()->user()->type == '1') {
            return view('admin.peminjaman-ruang.index', [
                'title' => 'Data Peminjaman Ruang',
                'data' => $data
            ]);
        } else {
            return view('user.data.peminjamanruang', [
                'data' => $data
            ]);
        }
    }

    public function create()
    {
        $prodi = Prodi::all();
        $ruang = Ruang::all();
        $jam = Jam::all();
        $matkul = MataKuliah::all();
        $dosen =  NamaDosen::all();
        $angkatan = Angkatan::all();
        return view('admin/peminjaman-ruang/create', [
            'title' => 'Tambah Peminjaman ruang',
            'ruang' => $ruang,
            'prodi' => $prodi,
            'jam_mulai' => $jam,
            'jam_selesai' => $jam,
            'matkul' => $matkul,
            'dosen' => $dosen,
            'angkatan' => $angkatan,
        ]);
    }

    // public function create()
    // {
    //     return view('admin.peminjaman-ruang.create', [
    //         'title' => 'Tambah Peminjaman Ruang'
    //     ]);
    // }

    public function store(Request $request)
    {
        $postData = $request->only([
            'nama_peminjam',
            'tgl_peminjaman',
            'ruang_id',
            'matkul_id',
            'dosen_id',
            'jam_mulai_id',
            'jam_selesai_id',
            'prodi_id',
            'angkatan_id'
        ]);

        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/peminjamanruang/post",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($postData),
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode == 201) {
            Alert::success('Berhasil', 'Peminjaman Berhasil Ditambahkan');

            if (auth()->user()->type == '1') {
                // Admin
                Alert::success('Berhasil', 'Peminjaman berhasil ditambahkan');
                return redirect()->route('peminjaman-ruang.index');
            } else {
                // Mahasiswa
                return redirect()->route('user.home')
                    ->with('alert', [
                        'title' => 'Peminjaman Berhasil',
                        'text' => 'Silakan ambil barang di ruangan admin dan bawa KTM Anda',
                        'icon' => 'success',
                    ]);
            }
        } else {
            $error = json_decode($response, true);
            return redirect()->back()->withErrors($error['errors'])->withInput();
        }

        // if ($httpCode == 201) {
        //     Alert::success('Berhasil', 'Peminjaman berhasil ditambahkan');
        //     return redirect()->route('peminjaman-ruang.index');
        // } else {
        //     $error = json_decode($response, true);
        //     return redirect()->back()->withErrors($error['errors'] ?? ['Terjadi kesalahan'])->withInput();
        // }
    }

    public function edit($id)
    {
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/peminjamanruang/{$id}",
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode != 200) {
            Alert::error('Error', 'Data tidak ditemukan');
            return redirect()->route('peminjaman-ruang.index');
        }

        $data = json_decode($response, true);

        $ruang = Ruang::all();
        $prodi = Prodi::all();
        $matkul = MataKuliah::all();
        $dosen = NamaDosen::all();
        $jam = Jam::all();
        $angkatan = Angkatan::all();

        return view('admin.peminjaman-ruang.edit', [
            'title' => 'Edit Peminjaman Ruang',
            'ruang' => $ruang,
            'prodi' => $prodi,
            'matkul' => $matkul,
            'dosen' => $dosen,
            'jam' => $jam,
            'angkatan' => $angkatan,
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $postData = $request->only([
            'nama_peminjam',
            'tgl_peminjaman',
            'ruang_id',
            'matkul_id',
            'dosen_id',
            'jam_mulai_id',
            'jam_selesai_id',
            'prodi_id',
            'angkatan_id'
        ]);

        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/peminjamanruang/{$id}",
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
            Alert::success('Berhasil', 'Peminjaman berhasil diubah');
            return redirect()->route('peminjaman-ruang.index');
        } else {
            $error = json_decode($response, true);
            return redirect()->back()->withErrors($error['errors'] ?? ['Terjadi kesalahan'])->withInput();
        }
    }

    public function destroy($id)
    {
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/peminjamanruang/{$id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
        ]);

        $response = curl_exec($client);
        $httpCode = curl_getinfo($client, CURLINFO_HTTP_CODE);
        curl_close($client);

        if ($httpCode == 200) {
            Alert::success('Berhasil', 'Peminjaman berhasil dihapus');
        } else {
            Alert::error('Gagal', 'Peminjaman gagal dihapus');
        }

        return redirect()->route('peminjaman-ruang.index');
    }
}
