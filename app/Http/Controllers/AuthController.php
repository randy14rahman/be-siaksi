<?php

namespace App\Http\Controllers;

use Zend\Debug\Debug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function login(Request $request){

        // encrypt password
        // Debug::dump(Hash::make('Bandung1994#'));

        // verify password
        // Debug::dump(Hash::check('Bandung1994#', '$2y$10$9NK.MPLUH1Dcigd4qsTP5ut8IORAuQ2E33osN589aEfiflFp3xngW'));
        
        $params = [
            'email' => $request->input('username')??$request->input('nip')
        ];
        $password = $request->input('password');
        // Debug::dump($password);

        $results = app('db')->select("SELECT id, name, email, password  FROM users where `email` = :email ", $params);
        // Debug::dump($results);die();

        $verifyPassword = ($results) ? Hash::check($password, $results[0]->password) : false;

        if ($verifyPassword) {
            $statusCode = 200;
            $response = [
                'status' => 1,
                'data' => $results
            ];
        } else {
            $statusCode = 401;
            $response = [
                'status' => 0,
                'message' => 'Login gagal, periksa kembali username atau password Anda.'
            ];
        }
        
        return response()->json($response, $statusCode);
        
    }

}
