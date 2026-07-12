<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaAsset extends Model
{
    protected $fillable = ['file_name','file_path','mime_type','size_bytes','alt_text','uploaded_by'];
}
