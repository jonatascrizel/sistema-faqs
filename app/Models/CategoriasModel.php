<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class CategoriasModel extends Model{
    protected $table = 'categorias';
    protected $returnType = 'object';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    
    protected $allowedFields = [
        'nome',
    ];

}