<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body'
    ];

    protected $appends = [
        'selfOwned' //whether the user owns the particular message (apply class to show diff background)
    ];

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function getSelfOwnedAttribute ()
    {
        return $this->user_id === auth()->user()->id;
    }
}
