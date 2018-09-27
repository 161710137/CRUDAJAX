<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswas';
    protected $fillable = ['nama','kelas','jk','alamat','eskul','foto'];
    public $timestamps = true;
    }
