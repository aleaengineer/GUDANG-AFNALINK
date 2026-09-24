<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller {
    public function index(){
        $users=User::latest()->paginate(20);
        return view('users.index', compact('users'));
    }
    public function create(){
        $jabatans=['Teknisi','NOC','Marketing','Kasir','CEO','CFO','CMO'];
        return view('users.create', compact('jabatans'));
    }
    public function store(Request $request){
        $data=$request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed',
            'jabatan'=>'required|in:Teknisi,NOC,Marketing,Kasir,CEO,CFO,CMO,Finance',
            'role'=>'required|in:admin,operator',
        ]);
        $data['password']=Hash::make($data['password']);
        $user=User::create($data);
        AuditLog::record(auth()->id(),'create_user',"Tambah user: {$user->name} ({$user->jabatan} - {$user->role})");
        return redirect()->route('users.index')->with('success','User berhasil ditambahkan');
    }
    public function edit(User $user){
        $jabatans=['Teknisi','NOC','Marketing','Kasir','CEO','CFO','CMO'];
        return view('users.edit', compact('user','jabatans'));
    }
    public function update(Request $request, User $user){
        $data=$request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email,'.$user->id,
            'password'=>'nullable|min:6|confirmed',
            'jabatan'=>'required|in:Teknisi,NOC,Marketing,Kasir,CEO,CFO,CMO,Finance',
            'role'=>'required|in:admin,operator',
        ]);
        if($data['password']) $data['password']=Hash::make($data['password']); else unset($data['password']);
        $user->update($data);
        AuditLog::record(auth()->id(),'update_user',"Update user: {$user->name}");
        return redirect()->route('users.index')->with('success','User berhasil diupdate');
    }
    public function destroy(User $user){
        if($user->id===auth()->id()) return back()->withErrors(['error'=>'Tidak bisa hapus akun sendiri']);
        $nama=$user->name; $user->delete();
        AuditLog::record(auth()->id(),'delete_user',"Hapus user: {$nama}");
        return redirect()->route('users.index')->with('success','User berhasil dihapus');
    }
}
