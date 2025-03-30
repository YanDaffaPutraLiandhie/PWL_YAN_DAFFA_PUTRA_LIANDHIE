<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /*public function index()
    {
        /*        $data = [
            'nama' => 'Pelanggan Pertama'
        ];
        UserModel::where('username', 'customer-1')->update($data);
        $users = UserModel::all();
        return view('user', ['data' => $users]);
        
        $data = [
            'level_id' => 2,
            'username' => 'manager_dua',
            'nama' => 'Manager 2',
            'password' => Hash::make('12345')

             'level_id' => 2,
            'username' => 'manager_tiga',
            'nama' => 'Manager 3',
            'password' => Hash::make('12345')
            ];
        //UserModel::create($data);

        $user = UserModel::find(1);
        return view('user', ['data' => $user]);

        //$user = UserModel::where('level_id',1)->first();
        //return view('user', ['data' => $user]);

        // $user = UserModel::firstWhere('level_id', 1);
        //return view('user',['data'=> $user]);

        //$user = UserModel::findOr(1,['username','nama'], function(){
        //    abort(404);
        //});
        //return view('user',['data'=> $user]);

        //$user = UserModel::findOr(20,['username','nama'], function(){
        //abort(404);
        //      });
        //    return view('user',['data'=> $user]);

        // $user = UserModel::findOrFail(1);
        // return view('user',['data'=> $user]);

        // $user = UserModel::where('username', 'manager9')->firstOrFail();
        // return view('user',['data'=> $user]);

        //   $user = UserModel::where('level_id', 2)->count();
        //   dd($user);
        //   return view('user', ['data' => $user]);

        // $user = UserModel::where('level_id', 2)->count();
        // return view('user', ['user' => $user]);

        //$user = UserModel::firstOrCreate(

        // 'username' => 'manager',
        //'nama' => 'Manager',[
        //'username' => 'manager22',
        //'nama' => 'Manager Dua Dua',
        //'password' => Hash::make('12345'),
        //'level_id' => 2
        //'username' => 'manager',
        //'nama' => 'manager'
        //'username' => 'manager33',
        //'nama' => 'Manager Tiga Tiga',
        //'password' => Hash::make('12345'),
        //'level_id' => 2

        //      ],
        // );
        // $user->save();
        // return view('user', ['data' => $user]);

        //      $user = UserModel::create([
        //        'username' => 'manager44',
        //        'nama' => 'Manager Empat Empat',
        //        'password' => Hash::make('12345'),
        //        'level_id' => 2
        //    ]);
        //
        //        $user->username = 'manager44';
        //
        //        $user->isDirty();
        //        $user->isDirty('username');
        //        $user->isDirty('nama');
        //        $user->isDirty(['nama', 'username']);
        //
        //        $user->isClean();
        //        $user->isClean('username');
        //        $user->isClean('nama');
        //        $user->isClean(['nama', 'username']);
        //
        //       $user->save();
        //
        //        $user->isDirty();
        //        $user->isClean();
        //       // dd($user->isDirty());
        //       dd($user->wasChanged('nama', 'username'));

        //$user = UserModel::create([
        //        'username' => 'manager11',
        //        'nama' => 'Manager Sebelas',
        //        'password' => Hash::make('12345'),
        //        'level_id' => 2
        //    ]);
        //$user->username = 'manager12';
        //$user->save();
        //$user->wasChanged();
        //$user->wasChanged('username');
        //$user->wasChanged(['username', 'level_id']);
        //$user->wasChanged('nama');
        //dd($user->wasChanged(['nama', 'username']));

        $user = UserModel::all();
        return view('user', ['data' => $user]);
    }*/
    public function tambah()
    {
        return view('user_tambah');
    }
    public function tambah_simpan(Request $request)
    {
        $data = [
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make($request->password),
            'level_id' => $request->level_id
        ];
        UserModel::create($data);
        return redirect('/user');
    }

    public function ubah($id)
    {
        $user = UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }
    public function ubah_simpan(Request $request, $id)
    {
        $user = UserModel::find($id);
        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make($request->password);
        $user->level_id = $request->level_id;
        $user->save();
        return redirect('/user');
    }
    public function hapus($id)
    {
        $user = UserModel::find($id);
        $user->delete();
        return redirect('/user');
}
/*public function index()
{       
    $user = UserModel::with('level')->get();
    dd($user);*/
public function index()
{
    $user = UserModel::with('level')->get();
    return view('user',['data'=>$user]);
}
}

