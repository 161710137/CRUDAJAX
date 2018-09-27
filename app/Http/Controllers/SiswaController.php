<?php

namespace App\Http\Controllers;

use App\Siswa;  
use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Validator;
use Illuminate\Support\Facades\File;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function json(){
        $siswa = Siswa::all();
     return Datatables::of($siswa)
     ->addColumn('show_photo', function($siswa){
                if ($siswa->foto == NULL){
                    return 'No Image';
                }
                return '<img class="rounded-square" width="50" height="50" src="'. url($siswa->foto) .'?'.time().'" alt="">';
            })
            ->addColumn('action', function($siswa){
                return '<a href="#" class="btn btn-xs btn-primary edit" data-id="'.$siswa->id.'"><i class="glyphicon glyphicon-edit"></i> Edit</a><a href="#" class="btn btn-xs btn-danger delete" id="'.$siswa->id.'"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
            })
            ->rawColumns(['show_photo','action'])->make(true);
    }

    public function index()
    {
        return view('pelajar');
    }
    function fetchdata(Request $request)
    {
        $id = $request->input('id');
        $siswa = Siswa::find($id);
        $output = array(
            'nama'    =>  $siswa->nama,
            'kelas'     =>  $siswa->kelas,
            'jk'        => $siswa->jk,
            'alamat'    => $siswa->alamat,
            'eskul'     => $siswa->eskul,
            'foto'      => $siswa->foto 
        );
        echo json_encode($output);
    }
    function removedata(Request $request)
    {
        $siswa = Siswa::find($request->input('id'));
        if($siswa->delete())
        {
            echo 'Data Deleted';
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'nama'=>'required',
            'kelas'=>'required',
            'jk'=>'required',
            'alamat'=>'required',
            'eskul'=>'required'
        ],[
            'nama.required'=>'nama tidak boleh kosong',
            'kelas.required'=>'kelas tidak boleh kosong',
            'jk.required'=>'jenis kelamin tidak boleh kosong',
            'alamat.required'=>'alamat tidak boleh kosong',
            'eskul.required'=>'eskul tidak boleh kosong'

        ]);
        $siswa = new Siswa;
        $siswa->nama = $request->nama;
        $siswa->kelas = $request->kelas;
        $siswa->jk     = $request->jk;
        $siswa->alamat = $request->alamat;
        $siswa->eskul = implode(",", $request->eskul) ;
         $siswa['foto'] = null;

        if ($request->hasFile('foto')){
            $siswa->foto = 'gambar/'.str_slug($siswa['nama'], '-').'.'.$request->foto->getClientOriginalExtension();
            $request->foto->move(public_path('gambar/'), $siswa->foto);
        }

        $succes = $siswa->save();
        if ($succes){
            return response()->json([
                'success'=>true,
            ]);
            
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        return $siswa;
        return view('Siswa.form-edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'nama'=>'required',
            'kelas'=>'required',
            'jk'=>'required',
            'alamat'=>'required',
            'eskul'=>'required',
            // 'foto'=>'required'
        ],[
            'nama.required'=>'Nama harus diisi',
            'kelas.required'=>'Kelas wajib diisi',
            'jk.required'=>'Jenis Kelamin harus diisi',
            'alamat.required'=>'Alamat harus diisi',
            'eskul.required'=>'Eskul harus diisi',
            // 'foto.required'=>'Foto harus diisi'
        ]);
        $siswa = Siswa::findOrFail($id);
        $siswa->nama = $request->nama;
        $siswa->kelas = $request->kelas;
        $siswa->jk = $request->jk;
        $siswa->alamat = $request->alamat;
        $siswa->eskul = implode(",", $request->eskul);

        $siswa['foto'] = $siswa->foto;
            if ($request->hasFile('foto')){
                if (!$siswa->foto == NULL){
                unlink(public_path($siswa->foto));
            }
            $siswa['foto'] = '/gambar/'.str_slug($siswa['nama'], '-').'.'.$request->foto->getClientOriginalExtension();
            $request->foto->move(public_path('/gambar/'), $siswa['foto']);
            }
        
        // $siswa['Photo'] = $siswa->Photo;
        // if ($request->hasFile('Photo')){
        //     if (!$siswa->Photo == NULL){
        //         unlink(public_path($siswa->Photo));
        //     }
        //    $siswa['Photo'] = '/upload/Photo/'.str_slug($siswa['nama'], '-').'.'.$request->Photo->getClientOriginalExtension();
        //     $request->Photo->move(public_path('/upload/Photo/'), $siswa['Photo']);
        // }
        $succes = $siswa->save();
        if ($succes){
            return response()->json([
                'success' => true,
            ]);
               
        }
    }

    /**
     * Remove the specified resource from storage.
     *z
     * @param  \App\Siswa  $siswa
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $siswa = Siswa::findOrFail($id);

        if (!$siswa->foto == NULL){
            unlink(public_path($siswa->foto));
        }

        Siswa::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Siswa Deleted'
        ]);
    }
    public function apiContact()
    {
        $siswa = Siswa::all();
 
        return Datatables::of($siswa)
            ->addColumn('show_photo', function($siswa){
                if ($siswa->foto == NULL){
                    return 'No Image';
                }
                return '<img class="rounded-square" width="50" height="50" src="'. url($siswa->foto) .'" alt="">';
            })
            ->addColumn('action', function($siswa){
                return '<a href="#" class="btn btn-info btn-xs"><i class="glyphicon glyphicon-eye-open"></i> Show</a> ' .
                       '<a onclick="editForm('. $siswa->id .')" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                       '<a onclick="deleteData('. $siswa->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['show_photo', 'action'])->make(true);
    }
}
