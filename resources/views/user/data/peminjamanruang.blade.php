@extends('user.layouts.frontend')

@section('content')
    <div class="container">
        <div class="content pt-4 pt-lg-0">
            </br></br>
            <h3 class="panel-title text-center mb-4">Data Peminjaman Ruang</h3>

            {{-- Tabel Responsif --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Matkul</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Dosen</th>
                            <th>Prodi</th>
                            <th>Angkatan</th>
                            <th>Ruang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $peminjamanruang)
                            <tr>
                                <td>{{ $peminjamanruang['tgl_peminjaman'] }}</td>
                                <td>{{ $peminjamanruang['nama_peminjam'] }}</td>
                                <td>{{ $peminjamanruang['matkul']['mata_kuliah'] }}</td>
                                <td>{{ substr($peminjamanruang['jamx']['jam'], 0, 5) }}</td>
                                <td>{{ substr($peminjamanruang['jamy']['jam'], 0, 5) }}</td>
                                <td>{{ $peminjamanruang['dosen']['nama_dosen'] }}</td>
                                <td>{{ $peminjamanruang['prodi']['nama_prodi'] }}</td>
                                <td>{{ $peminjamanruang['angkatan']['angkatan'] }}</td>
                                <td>{{ $peminjamanruang['ruang']['nama_ruang'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Optional: Pagination --}}
            {{-- <div class="d-flex justify-content-center mt-3">
                {{ $data->links() }}
            </div> --}}
        </div>
    </div>
@endsection
