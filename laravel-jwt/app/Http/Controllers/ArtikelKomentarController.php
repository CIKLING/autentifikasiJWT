<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArtikelKomentar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArtikelKomentarController extends Controller
{
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $validator = Validator::make(request()->all(), [
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $user = Auth::user();
        $komentar = $user->artikelKomentar()->create([
            'artikel_id' => $request->artikel_id,
            'body' => $request->body,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'komentar berhasil ditambahkan',
            'data' => $komentar,
        ]);
    }

    public function update(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $validator = Validator::make(request()->all(), [
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $user = Auth::user();

        // Cara Pertama:
        // $komentar = ArtikelKomentar::where('id', $request->komentar_id)->update([
        //     'body' => $request->body,
        // ]);


        // Cara Kedua:
        $komentar = ArtikelKomentar::find($request->komentar_id);

        if ($user->id != $komentar->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'kamu bukan pemilik komentar',
            ], 403);
        }

        $komentar->body = $request->body;
        $komentar->save();

        return response()->json([
            'success' => true,
            'message' => 'komentar berhasil diubah',
            'data' => $komentar,
        ]);
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $komentar = ArtikelKomentar::find($request->komentar_id);

        if ($user->id != $komentar->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'kamu bukan pemilik komentar',
            ], 403);
        }

        $komentar->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus',
            'data' => $komentar,
        ]);
    }
}
