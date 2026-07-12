<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RelatedLink extends Model
{
    protected $fillable = ['article_id','link_text','url','sort_order'];
    public function article(): BelongsTo { return $this->belongsTo(Article::class); }
}
