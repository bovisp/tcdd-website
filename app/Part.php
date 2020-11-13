<?php

namespace App;

use App\ContentBuilder;
use App\ContentBuilderType;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
        'content_builder_id',
        'sort_order',
        'content_builder_type_id'
    ];

    public function contentBuilder()
    {
        return $this->belongsTo(ContentBuilder::class, 'series_id');
    }

    public function contentBuilderType()
    {
        return $this->belongsTo(ContentBuilderType::class);
    }
}
