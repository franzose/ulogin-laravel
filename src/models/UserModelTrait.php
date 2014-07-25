<?php namespace Franzose\UloginLaravel\Models;

/**
 * Use this trait to add support for uLogin profile to your User model.
 *
 * @package Franzose\UloginLaravel
 */
trait UserModelTrait {

    /**
     * Relation to uLogin user profile.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function uloginProfile()
    {
        return $this->hasOne('Franzose\UloginLaravel\UloginProfile');
    }
} 