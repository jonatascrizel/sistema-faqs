<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $allowedFields = ['user_name', 'user_email', 'user_password', 'user_created_at', 'ativo'];

    /**
     * getMenus
     * Gera os menus do sistema em ordem alfabética
     *
     * @return array
     */
    public function getMenus()
    {
        $query = $this->db->query('SELECT * FROM menus ORDER BY nome');
        return $query->getResult();
    }

    /**
     * salvaMenusUsuario
     * Recebe um arrai com os menus e o id do usuário para salvar na tabela de permissões
     * 
     * @param  mixed $arr_menus
     * @param  mixed $id_user
     * @return void
     */
    public function salvaMenusUsuario($arr_menus, $id_user)
    {
        //apaga as permissões já existentes
        $this->db->query('DELETE FROM users_menus WHERE id_user = ' . $id_user);

        $sql = 'INSERT INTO users_menus (id_menu, id_user) VALUES ';
        foreach ($arr_menus as $v) {
            $sql .= ' (' . $v . ', ' . $id_user . '),';
        }
        $sql = substr($sql, 0, -1);
        $this->db->query($sql);
    }

    /**
     * getMenusUsuario
     * Gera a lista completa de menus indicando quais o usuário tem permissão
     *
     * @param  mixed $id_usuario
     * @return array
     */
    public function getMenusUsuario($id_usuario)
    {
        $query = $this->db->query('SELECT
                                    m.id,
                                    m.nome,
                                    m.icone,
                                    m.controller,
                                    (SELECT id_user FROM users_menus um WHERE um.id_menu = m.id AND um.id_user = ' . $id_usuario . ') AS permissao
                                FROM
                                    menus m
                                ORDER BY
                                    m.nome');
        return $query->getResult();
    }


    /**
     * geraMenu
     * Gera os menus do sistema habilitado para um usuário específico
     *
     * @param  int $id_usuario
     * @return array
     */
    public function geraMenu($id_usuario)
    {
        $query = $this->db->query('SELECT
                                        m.id,
                                        m.nome,
                                        m.icone,
                                        m.controller,
                                        m.title
                                    FROM
                                        menus m LEFT JOIN users_menus um ON um.id_menu = m.id
                                    WHERE
                                        um.id_user = ' . $id_usuario . '
                                    ORDER BY
                                        m.nome');
        return $query->getResult();
    }
}
