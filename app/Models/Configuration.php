<?php

namespace App\Models;

class Configuration extends BaseModel
{
    const URL_FORM = 'https://docs.google.com/a/mobly.com.br/forms/d/11CFIHRL33Pw-vMLbPubP5NUZe_eGhI-equ-EhB9HjIg/viewform';

    public static $selectChoices = [
        '1' => 'Yes',
        '0' => 'No'
    ];

    protected $table = 'configuration';

    protected $fillable = ['email', 'user_id', 'name', 'password', 'send_email_process', 'url_form', 'enable_queue_process'];

}