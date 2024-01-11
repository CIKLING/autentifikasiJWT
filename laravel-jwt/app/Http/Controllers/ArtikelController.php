<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArtikelShow;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $artikel = Artikel::latest()->paginate(7);
        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil ditampilkan',
            'data' => $artikel,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $validator = Validator::make(request()->all(), [
            'judul' => 'required',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $user = Auth::user();
        $artikel = $user->artikels()->create([
            'judul' => $request->judul,
            'body' => $request->body,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil ditambahkan',
            'data' => $artikel,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Artikel  $artikel
     * @return \Illuminate\Http\Response
     */
    public function show(Artikel $artikel)
    {
        $artikel = Artikel::with('komentar')->find($artikel->id);
        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil ditampilkan',
            'data' => new ArtikelShow($artikel),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Artikel  $artikel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Artikel $artikel)
    {
        date_default_timezone_set("Asia/Jakarta");

        $validator = Validator::make(request()->all(), [
            'judul' => 'required',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $user = Auth::user();

        // Cara Pertama:
        // $artikel = Artikel::where('id', $artikel->id)->update([
        //     'judul' => $request->judul,
        //     'body' => $request->body,
        // ]);

        // Cara Kedua:
        $artikel = Artikel::find($artikel->id);

        if ($user->id != $artikel->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'kamu bukan pemilik artikel',
            ], 403);
        }

        $artikel->judul = $request->judul;
        $artikel->body = $request->body;
        $artikel->save();

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil diubah',
            'data' => $artikel,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Artikel  $artikel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Artikel $artikel)
    {
        $user = Auth::user();
        $artikel = Artikel::find($artikel->id);
        
        if ($user->id != $artikel->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'kamu bukan pemilik artikel',
            ], 403);
        }

        $artikel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil dihapus',
            'data' => $artikel,
        ]);
    }
}
