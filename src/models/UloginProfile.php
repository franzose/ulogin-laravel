<?php namespace Franzose\UloginLaravel\Models;

use Config;
use Eloquent;

/**
 * uLogin profile model.
 *
 * @package Franzose\UloginLaravel
 */
class UloginProfile extends Eloquent {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array(
        'user_id', 'network', 'identity', 'email',
        'first_name', 'last_name', 'photo', 'photo_big', 'profile',
        'access_token', 'country', 'city'
    );

    /**
     * Constructs model and gets its table name from config.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->table = Config::get('ulogin-laravel::config.db_table', 'ulogin_profiles');

        parent::__construct($attributes);
    }

    /**
     * User that owns the profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }
} 