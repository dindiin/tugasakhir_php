<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\tbl_biodata;

class Personal extends Controller
{
    public function getData() {
    	$data = DB::table('tbl_biodata')->get();
    	if(count($data) > 0){
    		$res['message'] = "Success!";
    		$res['value'] = $data;
    		return response($res);
    	} else {
    		$res['message'] = "Empty!";
    		return response($res);
    	}
    }

    public function store(Request $request){
      $this->validate($request,[
        'foto' => 'required|max:2048'
      ]);

      //menyimpan data file yang diupload ke variable $file
      $file = $request->file('foto');
      $nama_file = time()."_".$file->getClientOriginalName();
      // isi dengan nama folder tempat kemana file diupload
      $tujuan_upload = 'data_file';
      if($file->move($tujuan_upload,$nama_file)){
        $data = tbl_biodata::create([
          'nama'    => $request->nama,
          'no_hp' 	=> $request->no_hp,
          'alamat'  => $request->alamat,
          'hobi'  	=> $request->hobi,
          'foto'    => $nama_file
        ]);
        $res['message'] = "success!";
        $res['values'] = $data;
        return response($res);
      }
    }

    public function update(Request $request) {
      if(!empty($request->file)){
        $this->validate($request,[
          'foto' => 'required|max:2048'
        ]);

        //menyimpan data file yang diupload ke variable $file
        $file = $request->file('foto');

        $nama_file = time()."_".$file->getClientOriginalName();

        // isi dengan nama folder tempat kemana file diupload
        $tujuan_upload = 'data_file';
        $file->move($tujuan_upload,$nama_file);
        $data = DB::table('tbl_biodata')->where('id',$request->id)->get();
        foreach ($data as $personal) {
          @unlink(public_path('data_file/'.$personal->foto));
          $ket = DB::table('tbl_biodata')->where('id',$request->id)->update([
            'nama'    => $request->nama,
            'no_hp'   => $request->no_hp,
            'alamat'  => $request->alamat,
            'hobi'    => $request->hobi,
            'foto'    => $nama_file
          ]);
          $res['message'] = "success!";
          $res['values'] = $ket;
          return response($res);
        }

      } else {
        $data = DB::table('tbl_biodata')->where('id',$request->id)->get();
        foreach ($data as $personal) {
          $ket = DB::table('tbl_biodata')->where('id',$request->id)->update([
            'nama'    => $request->nama,
            'no_hp'   => $request->no_hp,
            'alamat'  => $request->alamat,
            'hobi'    => $request->hobi
          ]);
          $res['message'] = "success!";
          $res['values'] = $ket;
          return response($res);
        }
      }
    }

    public function hapus($id){
      $data = DB::table('tbl_biodata')->where('id',$id)->get();
      foreach ($data as $personal) {
        if (file_exists(public_path('data_file/'.$personal->foto))) {
          @unlink(public_path('data_file/'.$personal->foto));
          DB::table('tbl_biodata')->where('id',$id)->delete();
          $res['message'] = "success!";
          return response($res);
        } else {
          $res['message'] = "Empty!";
          return response($res);
        }
      }
    }

    public function getDetail($id){
      $data = DB::table('tbl_biodata')->where('id',$id)->get();
      if(count($data) > 0){
        $res['message'] = "Success!";
        $res['value'] = $data;
        return response($res);
      } else {
        $res['message'] = "Empty!";
        return response($res);
      }
    }

}
