<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue_Category extends Model
{
    use HasFactory;

    /**
     * Table Name
     */
    protected $table = 'venue_categories';

    /**
     * Table Primary Key
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'category_id'
    ];

}
