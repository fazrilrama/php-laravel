<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use Validator;

class MahasiswaController extends Controller
{
    protected $user;

    public function __construct() {
        $this->username = 'fazrilramadhan';
        $this->email = 'helloword@gmail.com';
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function fetch(Request $request) {
        $user = MahasiswaModel::whereNull('deleted_at')->get();
        
        
        return response()->json([
            'status' => 200,
            'message' => 'username :  '.$this->username.' || email : '.$this->email.'',
            'data' => $user
        ]);
    }

    public function store(Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => 'required',
            'name' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email is required',
            'name' => 'name is required',
            'password' => 'Password is required'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all(),
            ]);
        }

        DB::beginTransaction();
        try {
            User::create([
                'email' => $data['email'],
                'name' => $data['name'],
                'password' => $data['password'],
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Example',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'data failed updated...'
            ]);
        }
    }

    public function update(Request $request, $id) {
        $payload = $request->all();

        // Checking
        $check = User::where('id', $id)->get();
        
        if($check->count() == 0) {
            return response()->json([
                'status' => false,
                'message' => 'data not found...'
            ]);
        }

        DB::beginTransaction();
        try {
            $data = User::where('id', $id)->first();
            $data->name     = $payload['name'];
            $data->email    = ($payload['email'] == '') ? $data->email : $payload['email'];
            $data->update();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'User has been updated ...',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'data failed updated...'
            ]);
            // something went wrong
        }
    }

    public function remove($id) {
        $check = User::where('id', $id)->get();

        // Check User
        if($check->count() == 0) {
            return response()->json([
                'status' => false,
                'message' => 'USER_NOT_FOUND'
            ]);
        }

        $user = User::where('id', $id)->first();
        $user->deleted_at = Carbon::now();
        $user->update();
        

        return response()->json([
            'status' => true,
            'message' => 'Process',
            'data' => []
        ]);
    } 
}
