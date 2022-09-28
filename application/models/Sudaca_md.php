<?php
class Sudaca_md extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function edit($table,$data,$fieldID,$ID){
        $this->db->where($fieldID,$ID);
        $this->db->update($table, $data);    
    }

    function getOpciones($id){
        $query = $this->db
                        ->select('*')
                        ->where('opcion_id =', $id)
                        ->get('opciones_variables');
        return $query->result();
    }

    function contador($id, $campo, $tabla){
        $query = $this->db->query('
                        SELECT COUNT(id) AS cantidad
                        FROM '.$tabla.'
                        WHERE '.$campo.' = '.$id);
        return $query->row();
    }
    
    function getUltimosAccesos($id){
        $query = $this->db
                        ->select('*')
                        ->where('user_id', $id)
                        ->order_by('id', 'desc')
                        ->limit(5)
                        ->get('login_attempts');
        return $query->result();
    }    

    function getAccesosDirectosPadres($id){
        $query = $this->db
                        ->select('p.*, m.id, m.descripcion, m.dashboard, m.active, m.iconpath')
                        ->join('menus m', 'm.id = p.menu_id')
                        ->join('users_groups ug', 'ug.group_id = p.group_id')
                        ->where('ug.user_id', $id)
                        ->where('m.parent', 0)
                        ->where('p.read', 1)
                        ->where('m.dashboard', 1)
                        ->where('m.active', 1)
                        ->order_by('m.estado')
                        ->get('permisos p');
        return $query->result();
    }

    function getAccesosDirectosHijos($id){
        $query = $this->db
                        ->select('p.*, m.link, m.descripcion, m.dashboard, m.parent, m.iconpath')
                        ->join('menus m', 'm.id = p.menu_id')
                        ->join('users_groups ug', 'ug.group_id = p.group_id')
                        ->where('ug.user_id', $id)
                        ->where('m.parent !=', 0)
                        ->where('p.read', 1)
                        ->where('m.dashboard', 1)
                        ->where('m.active', 1)
                        ->order_by('m.descripcion')
                        ->get('permisos p');
        return $query->result();
    }

    function getMenus($id){
        $query = $this->db
                        ->select('p.*, m.id, m.link, m.descripcion, m.dashboard, m.active, m.iconpath')
                        ->join('menus m', 'm.id = p.menu_id')
                        ->join('users_groups ug', 'ug.group_id = p.group_id')
                        ->where('ug.user_id', $id)
                        ->where('m.parent', 0)
                        ->where('p.read', 1)
                        ->where('m.active', 1)
                        ->order_by('m.estado')
                        ->get('permisos p');
        return $query->result();
    }

    function getSubmenus($id, $parent){
        $query = $this->db
                        ->select('p.*, m.link, m.descripcion, m.dashboard, m.parent, m.iconpath')
                        ->join('menus m', 'm.id = p.menu_id')
                        ->join('users_groups ug', 'ug.group_id = p.group_id')
                        ->where('ug.user_id', $id)
                        ->where('m.parent', $parent)
                        ->where('p.read', 1)
                        ->where('m.dashboard', 1)
                        ->where('m.active', 1)
                        ->order_by('m.orden, m.descripcion')
                        ->get('permisos p');
        return $query->result();
    }

}