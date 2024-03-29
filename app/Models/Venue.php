<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    /**
     * Table Name
     */
    protected $table = 'venues';

    /**
     * Table Primary Key
     */
    protected $primaryKey = 'venue_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'venue_name',
        'venue_type_id'
    ];
}
