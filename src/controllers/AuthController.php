<?php namespace Franzose\UloginLaravel\Controllers;

use Auth;
use Config;
use Controller;
use Franzose\UloginLaravel\Models\UloginProfile;
use Franzose\UloginLaravel\Validators\UloginResponseValidator;
use Illuminate\Auth\UserInterface;
use Input;
use Redirect;
use Request;
use Session;

class AuthController extends Controller {

    /**
     * User instance.
     *
     * @var UserInterface
     */
    protected $user;

    /**
     * uLogin user profile.
     *
     * @var UloginProfile
     */
    protected $profile;

    /**
     * Constructor.
     *
     * @param UserInterface $user
     * @param UloginProfile $profile
     * @param UloginResponseValidator $validator
     */
    public function __construct(
        UserInterface $user,
        UloginProfile $profile,
        UloginResponseValidator $validator
    )
    {
        $this->user = $user;
        $this->profile = $profile;
        $this->validator = $validator;
    }

    /**
     * Export redirect URI to uLogin service.
     *
     * @return string
     */
    public function getLogin()
    {
        return Config::get('ulogin-laravel::config.redirect_uri');
    }

    /**
     * Attempt to login.
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function postLogin()
    {
        $ulogin = $this->requestUloginData();

        if (isset($ulogin['error']))
        {
            return $this->handleUloginResponseError($ulogin['error']);
        }

        $profile = $this->profile->where('identity', '=', $ulogin['identity'])->first();

        if ($profile)
        {
            return $this->authUserById($profile->user_id);
        }

        if ($this->validator->with($ulogin)->passes())
        {
            return $this->authUserByUlogin($ulogin);
        }

        return $this->makeErrorResponse($this->validator->getErrors()->all(':message'));
    }

    /**
     * Request user data from a social network via uLogin.
     *
     * @return array
     */
    protected function requestUloginData()
    {
        $url = 'http://ulogin.ru/token.php?token=' . Input::get('tokenn') . '&host=' . Request::server('HTTP_HOST');
        $content = file_get_contents($url);

        return json_decode($content, true);
    }

    /**
     * Handle error response from uLogin.
     *
     * @param $error
     * @return mixed
     */
    protected function handleUloginResponseError($error)
    {
        $error = array(trans('ulogin-laravel::ulogin-laravel.' . $error));

        return $this->makeErrorResponse($error);
    }

    /**
     * Make success response.
     *
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function makeSuccessResponse($message)
    {
        $uri = Config::get('ulogin-laravel::config.redirect_after_login');

        return Redirect::to($uri)->with('success', $message);
    }

    /**
     * Make error response.
     *
     * @param array $errors
     * @return \Illuminate\Http\Response|mixed
     */
    protected function makeErrorResponse(array $errors)
    {
        $uri = Session::get('ulogin_error_redirect', '/');

        return Redirect::to($uri)->withErrors($errors);
    }

    /**
     * Authorize user by their ID.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authUserById($id)
    {
        Auth::loginUsingId($id, true);

        return $this->makeSuccessResponse(trans('ulogin-laravel::ulogin-laravel.success'));
    }

    /**
     * Find or create user then create
     * their uLogin profile and authorize by ID.
     *
     * @param array $ulogin
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authUserByUlogin(array $ulogin)
    {
        $userId = $this->obtainUserId($ulogin);
        $this->createUloginProfile($userId, $ulogin);

        return $this->authUserById($userId);
    }

    /**
     * Get ID of an existing user or create them first.
     *
     * @param array $profile
     * @return int
     */
    protected function obtainUserId(array $profile)
    {
        $user = $this->user->where('email', '=', $profile['email'])->first();

        return ($user ? $user->getKey() : $this->createUser($profile)->getKey());
    }

    /**
     * Set very basic properties and create user.
     *
     * @param array $profile
     * @return UserInterface
     */
    protected function createUser($profile)
    {
        $this->user->email = $profile['email'];
        $this->user->password = $this->makeUserPassword();
        $this->user->save();

        return $this->user;
    }

    /**
     * Make user password. Override to change algorithm.
     *
     * @return string
     */
    protected function makeUserPassword()
    {
        return str_random();
    }

    /**
     * Create uLogin profile for user.
     *
     * @param int $userId
     * @param array $ulogin
     */
    protected function createUloginProfile($userId, $ulogin)
    {
        $fields = Config::get('ulogin-laravel::config.fields');
        $attributes = ['user_id' => $userId];

        foreach($fields as $field)
        {
            $attributes[$field] = (isset($ulogin[$field]) ? $ulogin[$field] : '');
        }

        $this->profile->create($attributes);
    }
} 