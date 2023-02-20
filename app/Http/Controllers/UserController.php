<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUser;
use App\Models\BookRenting;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function list(Request $request)
    {
        $users = User::get();
        return api_response_send('success', '', $users, 200);
    }

    public function update(StoreUser $request, User $user)
    {
        DB::beginTransaction();
        try {
            $user->firstname = $request['firstname'];
            $user->lastname = $request['lastname'];
            $user->mobile = $request['mobile'];
            $user->email = $request['email'];
            $user->age = $request['age'];
            $user->gender = $request['gender'];
            $user->city = $request['city'];
            if (isset($request['password'])) {
                $user->password = bcrypt($request['password']);
            }
            $user->save();
            DB::commit();
            return api_response_send('success', 'User updated successfully.', $user, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return api_response_send('error', $e->getMessage(), [], 500);
        }
    }

    public function profile($id)
    {
        $user = User::where('id', $id)->first();
        return api_response_send('success', '', $user, 200);
    }

    public function delete(User $user)
    {
        DB::beginTransaction();
        try {
            BookRenting::where('user_id', $user->id)->delete();
            $user->delete();
            DB::commit();
            return api_response_send('success', 'User deleted successfully.', [], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return api_response_send('error', $e->getMessage(), [], 500);
        }
    }

    public function UserBookRanted(Request $request)
    {
        $users = User::with(['book_rented', 'book_rented.books'])->get();
        return api_response_send('success', '', $users, 200);
    }
}
