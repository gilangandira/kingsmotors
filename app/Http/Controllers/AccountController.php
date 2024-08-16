<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;


class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return
            response()->view(
                'account.account',
                [
                    "title" => "List Assets",
                    "users" => User::all()
                ]
            )
        ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('account.register', [
            'title' => 'Add Account',
            // 'active' => 'create_asset'
            'users' => User::all()
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
        // Validate the incoming request
        $request->validate(
            [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ],
            [
                'name.required' => 'Nama Wajib Di isi',
                'email.required' => 'Email Wajib Di isi',
                'password.required' => 'Password Wajib Di isi'
            ]
        );

        // Prepare the data for user creation
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];

        try {
            // Create the user
            User::create($data);

            // Redirect with success message
            return redirect('/account')->with('success', 'Akun Telah Di Tambah');
        } catch (\Exception $e) {
            // Log the exception and redirect with an error message
            Log::error('User registration failed: ' . $e->getMessage());

            return redirect('/account')->with('error', 'Terjadi kesalahan, coba lagi nanti.');
        }
    }

    public function edit($id)
    {
        $data = User::where('id', $id)->first();
        return response(view('account.edit_account', [
            'title' => 'Edit Account',
            'user' => $data,
        ]));
    }


    public function update(Request $request, $id)
    {

        DB::beginTransaction();

        try {
            // Mengupdate data pada tabel User
            $user = User::find($id);
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            if ($request->filled('password')) {
                // Input baru untuk password diberikan
                $user->password = bcrypt($request->input('password'));
            }
            $user->save();
            DB::commit();

            return redirect('/account')->with('success', 'Data berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollback();
            return redirect('/account')->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        // Cek apakah ID yang diberikan adalah akun yang dilindungi
        // Misalnya, jangan hapus akun admin utama dengan id 1
        if (in_array($id, [1, 2])) {
            return redirect('/account')->with('error', 'Akun ini dilindungi dan tidak dapat dihapus.');
        }


        // Temukan user berdasarkan ID
        $user = User::findOrFail($id);

        // Hapus pengguna
        $user->delete();

        // Redirect ke halaman daftar akun dengan pesan sukses
        return redirect('/account')->with('success', 'Data berhasil diperbarui.');
    }


    public function indexLogin()
    {
        return view('account.login', [
            'title' => 'Login',
            'active' => 'login'
        ]);
    }

    public function login(Request $request)
    {
        // Flash the name into the session to be used in the view
        Session::flash('name', $request->name);

        // Validate the incoming request
        $request->validate(
            [
                'name' => 'required',
                'password' => 'required'
            ],
            [
                'name.required' => 'Username Wajib Di isi',
                'password.required' => 'Password Wajib Di isi'
            ]
        );

        // Prepare the credentials array
        $credentials = $request->only('name', 'password');
        try {
            // Attempt to log the user in with the provided credentials
            if (Auth::attempt($credentials)) {
                // Regenerate the session to prevent session fixation attacks
                $request->session()->regenerate();

                // Redirect to the intended page or homepage with a success message
                return redirect()->intended('/')->with('success', 'Selamat Datang');
            } else {
                // Log an error message for failed login attempts
                Log::warning('Login attempt failed for user: ' . $request->name);

                // Redirect back to the login page with an error message
                return redirect('/login')->with('error', 'Password atau Username Salah');
            }
        } catch (\Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Login process failed: ' . $e->getMessage());

            // Redirect back to the login page with a generic error message
            return redirect('/login')->with('error', 'Terjadi kesalahan, coba lagi nanti.');
        }
    }



    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    }





}