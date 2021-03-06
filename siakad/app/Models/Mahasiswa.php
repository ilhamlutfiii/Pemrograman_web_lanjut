<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model //Definisi Model
{
    protected $table='mahasiswa'; 
    // Eloquent akan membuat model mahasiswa menyimpan record di tabel mahasiswa
    protected $primaryKey = 'Nim'; // Memanggil isi DB Dengan primarykey
/**
* The attributes that are mass assignable.
**
    @var array
*/
protected $fillable = [
    'Nim',
    'Nama',
    'Kelas',
    'Jurusan',
    
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}