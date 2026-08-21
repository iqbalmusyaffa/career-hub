<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrInternalNote extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function hrUser()
    {
        return $this->belongsTo(User::class, 'hr_user_id');
    }
}
