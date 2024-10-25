<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    // Include 'description' in the fillable attributes
    protected $fillable = [
        'user_id',      // Ensure this is included if required
        'action',       // Description of the action performed
        'description',  // Add the description field
        'created_at',   // Automatically managed by Laravel
        'updated_at',   // Automatically managed by Laravel
    ];

    public $timestamps = true; 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Adjust the foreign key if different
    }
}
