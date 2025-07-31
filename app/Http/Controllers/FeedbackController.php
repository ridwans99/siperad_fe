<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    // public function index()
    // {
    //     $data = Feedback::all();
    //     $title = 'Delete Feedback!';
    //     $text = "Are you sure you want to delete?";
    //     confirmDelete($title, $text);
    //     return view('admin/feedback/index', [
    //         'title' => 'Data Feedback',
    //         'data' => $data
    //     ]);
    // }

    public function index()
    {
        $title = 'Delete Feedback!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/feedback/index',
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
        //     Alert::error('Gagal', 'Gagal mengambil data feedback dari API');
        //     return redirect()->back();
        // }

        // // Decode JSON response jadi array PHP
        // $data = json_decode($response, true);

        return view('admin/feedback/index', [
            'title' => 'Data Feedback',
            'data' => $data
        ]);
    }

    public function viewFeedback()
    {
        return view('user/form/feedback', []);
    }

    public function store(Request $request)
    {
        $client = curl_init();

        $postData = [
            'tgl_feedback' => $request->tgl_feedback,
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'saran' => $request->saran,
        ];

        curl_setopt_array($client, [
            CURLOPT_URL => 'https://fmipa.unj.ac.id/siperad-be/api/feedback/post',
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
            Alert::success('Berhasil', 'Feedback Anda telah dikirim.');
            return redirect()->route('user.home')->with('success', 'Feedback Anda telah dikirim.');
        } else {
            $error = json_decode($response, true);
            return redirect()->back()->withErrors($error['errors'] ?? ['error' => 'Terjadi kesalahan'])->withInput();
        }
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'tgl_feedback' => 'required|date',
    //         'nama' => 'required|string|max:255',
    //         'kategori' => 'required|string',
    //         'saran' => 'required|string',
    //     ]);

    //     Feedback::create($request->all());

    //     Alert::success('Berhasil', 'Feedback Anda telah dikirim.');

    //     return redirect()->route('user.home')
    //         ->with('success', 'Feedback Anda telah dikirim.');
    // }

    public function destroy($id)
    {
        $postData = [
            '_method' => 'DELETE', // Override method DELETE
        ];
        
        $client = curl_init();
        curl_setopt_array($client, [
            CURLOPT_URL => "https://fmipa.unj.ac.id/siperad-be/api/feedback/{$id}",
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
            Alert::success('Berhasil', 'Feedback Berhasil Dihapus');
        } else {
            Alert::error('Gagal', 'Feedback gagal dihapus');
        }

        return redirect()->route('feedback.index');
    }

    // public function destroy($id)
    // {
    //     $data = Feedback::find($id);
    //     $data->delete();

    //     Alert::success('Berhasil', 'Feedback Berhasil Dihapus');

    //     return redirect()->route('feedback.index')
    //         ->with('success', 'Feedback berhasil dihapus!');
    // }
}
