@extends('admin.partials.main');

@section('container')
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">{{ $title }}</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active"><a href="{{ route('admin.home') }}">Dashboard</a> > <a
                        href="{{ route('peminjaman-barang.index') }}">Data Peminjaman Alat</a> > {{ $title }}</li>
            </ol>
            <div class="card mb-4">
                <div class="card-body">
                    <h6>Berikut adalah form {{ Str::lower($title) }}.</h6>
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <div class="alert-title">
                        <h4>Whoops!</h4>
                    </div>
                    There are some problems with your input.
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    {{ $title }}
                </div>
                <div class="card-body">
                    <form action="{{ route('peminjaman-barang.update', $data['id']) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        {{-- <input type="hidden" name="id" value="{{ $data->id }}"> --}}
                        <div>
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Tanggal Peminjaman
                            </label>
                            <div class="form-group mb-2">
                                <input id="birthday" name="tgl_peminjaman" class="date-picker form-control"
                                    placeholder="dd-mm-yyyy" type="text" required="required" type="text"
                                    value="{{ $data['tgl_peminjaman'] }}" onfocus="this.type='date'"
                                    onmouseover="this.type='date'" onclick="this.type='date'" onblur="this.type='text'"
                                    onmouseout="timeFunctionLong(this)">
                                <script>
                                    function timeFunctionLong(input) {
                                        setTimeout(function() {
                                            input.type = 'text';
                                        }, 60000);
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label for="nama_peminjam">Nama Peminjam</label>
                            <input type="text" class="form-control" name="nama_peminjam"
                                value="{{ $data['nama_peminjam'] }}" id="nama_peminjam" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="nim">NIM</label>
                            <input type="text" class="form-control" name="nim" value="{{ $data['nim'] }}"
                                id="nim" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="no_hp">No HP</label>
                            <input type="text" class="form-control" name="no_hp" value="{{ $data['no_hp'] }}"
                                id="no_hp" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="prodi_id">Prodi</label>
                            <select class="form-select" name="prodi_id">
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->id }}"
                                        {{ $p->id == $data['prodi_id'] ? 'selected' : '' }}>
                                        {{ $p->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- <select class="form-select" name="prodi_id">
                                <option selected value="{{ $data->prodi['id'] }}">Prodi terpilih :
                                    {{ $data->prodi['nama_prodi'] }} </option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select> --}}
                        </div>
                        <div class="form-group mb-2">
                            <label for="angkatan_id">Angkatan</label>
                            <select class="form-select" name="angkatan_id">
                                @foreach ($angkatan as $a)
                                    <option value="{{ $a->id }}"
                                        {{ $a->id == $data['angkatan_id'] ? 'selected' : '' }}>
                                        {{ $a->angkatan }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- <select class="form-select" name="angkatan_id">
                                <option selected value="{{ $data->angkatan['id'] }}">Angkatan terpilih :
                                    {{ $data->angkatan['angkatan'] }}</option>
                                @foreach ($angkatan as $a)
                                    <option value="{{ $a->id }}">{{ $a->angkatan }}</option>
                                @endforeach
                            </select> --}}
                        </div>
                        <div class="form-group mb-2">
                            <label for="matkul_id">Mata Kuliah</label>
                            <select class="form-select" name="matkul_id">
                                @foreach ($matkul as $m)
                                    <option value="{{ $m->id }}"
                                        {{ $m->id == $data['matkul_id'] ? 'selected' : '' }}>
                                        {{ $m->mata_kuliah }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- <select class="form-select" name="matkul_id">
                                <option selected value="{{ $data->matkul['id'] }}">Mata Kuliah terpilih :
                                    {{ $data->matkul['mata_kuliah'] }} </option>
                                @foreach ($matkul as $m)
                                    <option value="{{ $m->id }}">{{ $m->mata_kuliah }}</option>
                                @endforeach
                            </select> --}}
                        </div>
                        <div class="form-group mb-2">
                            <label for="dosen_id">Nama Dosen</label>
                            <select class="form-select" name="dosen_id">
                                @foreach ($dosen as $d)
                                    <option value="{{ $d->id }}"
                                        {{ $d->id == $data['dosen_id'] ? 'selected' : '' }}>
                                        {{ $d->nama_dosen }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- <select class="form-select" name="dosen_id">
                                <option selected value="{{ $data->dosen['id'] }}">Nama Dosen terpilih :
                                    {{ $data->dosen['nama_dosen'] }} </option>
                                @foreach ($dosen as $nd)
                                    <option value="{{ $nd->id }}">{{ $nd->nama_dosen }}</option>
                                @endforeach
                            </select> --}}
                        </div>
                        <div class="form-group mb-2">
                            <label for="barang_id">Nama Alat</label>
                            <select class="form-select" name="barang_id">
                                @foreach ($barang as $b)
                                    <option value="{{ $b->id }}"
                                        {{ $b->id == $data['barang_id'] ? 'selected' : '' }}>
                                        {{ $b->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- <select class="form-select" name="barang_id">
                                <option selected value="{{ $data['barang']['id'] }}">Nama Alat terpilih :
                                    {{ $data['barang']['nama_barang'] }} </option>
                                @foreach ($barang as $b)
                                    <option value="{{ $b['id'] }}">{{ $b['nama_barang'] }}</option>
                                @endforeach
                            </select> --}}
                        </div>


                        <div class="form-group mb-2">
                            <label class="col-form-label col-md-3 col-sm-3 label-align">Status Peminjaman
                            </label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_peminjaman"
                                    id="status_peminjaman" value="{{ 1 }}"
                                    {{ $data['status_peminjaman'] == 1 ? 'Checked' : '' }}>
                                <label class="form-check-label" for="status_peminjaman">
                                    Telah dikembalikan
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status_peminjaman"
                                    id="status_peminjaman" value="{{ 0 }}"
                                    {{ $data['status_peminjaman'] == 0 ? 'Checked' : '' }}>
                                <label class="form-check-label" for="status_peminjaman">
                                    Belum dikembalikan
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Ubah</button>
                    </form>
                </div>
            </div>

        </div>
    </main>
@endsection
