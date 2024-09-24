<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    // The table associated with the model.
    protected $table = 'forms';

    protected $casts = [
      'file_path' => 'array',
    ];

    // The attributes that are mass assignable.
    protected $fillable = [
        'form_name',
        'user_name',
        'company_name',
        'email',
        'phone',
        'po_number',
        'due_date',
        'subject',
        'project_description',
        'file_description',
        'project_details',
        'website_url',
        'message',
        'career_option',
        'file_path',
    ];
}