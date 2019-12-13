<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DropboxFiles extends Model
{
    protected $table = 'dropbox_files';
    protected $guarded = [];
 
    public function getSizeInKbAttribute()
    {
        return number_format($this->size / 1024, 1);
    }
}
