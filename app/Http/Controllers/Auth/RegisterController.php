<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AbstractController;
use App\Services\Entities\User\UserService;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends AbstractController
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return \Validator::make($data, [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return mixed
     */
    protected function create(array $data)
    {
        return app(UserService::class)->create([
           'username' => $data['username'],
           'email' => $data['email'],
           'password' => bcrypt($data['password'])
       ]);
    }
}
