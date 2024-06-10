<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class FaqModel extends Model{
    protected $table = 'faqs';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
      
    protected $allowedFields = [
        'pergunta',
        'resposta',
        'ativo',
    ];

    public function selectCategorias(){
      $query = $this->db->query('SELECT
                                  e.*,
                                  (SELECT count(f.id) FROM faqs f, faqs_x_categoria fe WHERE fe.id_faq = f.id AND fe.id_categoria = e.id AND f.ativo = 1) as contador
                                FROM
                                  categorias e
                                ');
      return $query->getResult();
    }

    public function faqsEvento($id_categoria = null){
      $sql = 'SELECT
                e.nome AS categoria,
                f.*
              FROM
                categorias e,
                faqs f
                LEFT JOIN faqs_x_categoria fe ON fe.id_faq = f.id
              WHERE
                f.ativo = 1
                AND fe.id_categoria = e.id ';

      if($id_categoria != 'null'){
        $sql .= 'AND e.id = '.$id_categoria.' ';
      }

      $sql .= ' GROUP BY f.id
              ORDER BY f.pergunta';

      $query = $this->db->query($sql);
      return $query->getResult();
    }

    public function listaFaqsEventos(){
      $sql = "SELECT
                f.id,
                f.pergunta,
                (SELECT GROUP_CONCAT(c.nome SEPARATOR '|') FROM categorias c , faqs_x_categoria fe WHERE c.id = fe.id_categoria AND fe.id_faq = f.id ORDER BY c.nome ASC) AS ev
              FROM
                faqs f		
              ORDER BY
                f.pergunta ASC
              ";

      $query = $this->db->query($sql);
      return $query->getResult();
    }

    public function salvaEventoXFaq($arr_eventos, $id_faq)
    {
        //apaga as permissões já existentes
        $this->db->query('DELETE FROM faqs_x_categoria WHERE id_faq = ' . $id_faq);

        $sql = 'INSERT INTO faqs_x_categoria (id_categoria, id_faq) VALUES ';
        foreach ($arr_eventos as $v) {
            $sql .= ' (' . $v . ', ' . $id_faq . '),';
        }
        $sql = substr($sql, 0, -1);
        $this->db->query($sql);
    }

    public function selectFV($id_categoria){
      $query = $this->db->query("SELECT GROUP_CONCAT(fe.id_categoria SEPARATOR '|') as faqs FROM faqs_x_categoria fe WHERE fe.id_faq = ".$id_categoria);
      return $query->getResult();
    }

    public function deletar($id){
      $this->db->query('DELETE FROM faqs_x_categoria WHERE id_faq = ' . $id);
      $this->db->query('DELETE FROM faqs WHERE id = ' . $id);
    }

}