<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Contact extends Model
{
    use HasFactory, HasApiTokens;
    protected $fillable = ['firstName', 'surname', 'email', 'phone'];
}
