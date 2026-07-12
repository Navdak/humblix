<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['client_name','client_role','company','location','project_category','rating','comment','admin_response','is_approved'];
    protected function casts(): array { return ['rating' => 'integer', 'is_approved' => 'boolean']; }
}
