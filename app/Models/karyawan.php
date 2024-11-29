<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class karyawan extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'nip', 'telpon', 'jenis_kelamin', 'tempat_lahir', 'tgl_lahir', 'agama', 'alamat', 'cover'];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function absen()
    {
        return $this->hasMany(absen::class, 'id_karyawan');
    }

    //hapus img
    public function deleteImage()
    {
        if ($this->cover && file_exists(public_path('images/cover/' . $this->cover))) {
            return unlink(public_path('images/cover/' . $this->cover));
        }
    }
}
