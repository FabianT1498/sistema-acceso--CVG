<?php

namespace App;

use App\Visitor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

use App\Traits\UploadTrait;

class Photo extends Model
{
    use UploadTrait;

    //
    protected $fillable = ['path'];
  
    /**
     * Get the user that owns the phone.
     */
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }

    public function storePhoto(UploadedFile $image, string $name){

        // Define folder path
        $folder = '/images/';

        // Make a file path where image will be stored [ folder path + file name + file extension]
        $filePath = $folder . $name. '.' . $image->getClientOriginalExtension();

        // Upload image
        $this->uploadOne($image, $folder, 'public', $name);

        $this->path = $filePath;
    }
}
