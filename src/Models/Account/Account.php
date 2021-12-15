<?php

namespace GoApptiv\TextLocal\Models\Account;

use GoApptiv\TextLocal\Models\BaseModel;
use GoApptiv\TextLocal\Services\Crypto;

/**
 *
 * @property int $id - Id of the account
 * @property string $name - Reference name for the account
 * @property string $email - Email id of the account
 * @property string $api_key - API Token of the account
 *
 **/
class Account extends BaseModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'textlocal_accounts';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];


    /**
     * Set api_key.
     *
     * @param string $value
     * @return void
     */
    public function setApiKeyAttribute($value)
    {
        $this->attributes['api_key'] = Crypto::encrypt($value);
    }

    /**
     * Get api_key.
     *
     * @param string $value
     * @return string
     */
    public function getApiKeyAttribute($value)
    {
        return $value ? Crypto::decrypt($value) : null;
    }
}
