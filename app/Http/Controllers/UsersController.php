<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\UpdateUserRequest;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "人員管理";
        // ログインユーザー以外の社員情報を取得
        $users = User::whereNotIn('id', [auth()->id()])
            ->orderBy('employee_number')
            ->paginate(10);

        return view('users.index', compact('title', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "社員登録";

        return view('users.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();

            $new_number = $request->input('employee_number');
            $name = $request->input('name');
            $password = Hash::make($request->input('password'));
            $role = $request->input('role');

            User::create([
                'employee_number' => $new_number,
                'name' => $name,
                'password' => $password,
                'role' => $role,
            ]);

            DB::commit();

            session()->flash('status', '社員情報を登録しました');

            return redirect()->route('user.index');
        } catch (\Exception $e) {
            DB::rollback();
            // エラーハンドリングやログの記録などを行う
            session()->flash('error', 'エラーが発生しました');
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = "社員情報変更";
        $user = User::where('employee_number', $id)->first();

        // ログインユーザーの編集不可にする（URLからアクセス不可にする）
        if ($user->id === auth()->id()) {
            return abort(404);
        }
        return view('users.edit', compact('user', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::where('employee_number', $id)->first();
        $user->name = $request['name'];
        $user->role = $request['role'];
        $user->updated_at = Carbon::now();
        $user->save();

        session()->flash('status', '社員情報を更新しました');
        return redirect()->route('user.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function password_update(PasswordRequest $request, $id)
    {
        $password = Hash::make($request->input('password'));

        $user = User::where('employee_number', $id)->first();
        $user->password = $password;
        $user->updated_at = Carbon::now();
        $user->save();

        session()->flash('status', '社員情報を更新しました');
        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('employee_number', $id)->first();

        // 論理削除を行う
        $user->delete();

        // 削除完了メッセージをフラッシュ
        session()->flash('alert',  '登録情報を削除しました');

        // 他の処理やリダイレクトなどを行う
        return redirect()->route('user.index');
    }
}
