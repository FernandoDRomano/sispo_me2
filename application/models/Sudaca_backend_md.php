<?php
class Sudaca_backend_md extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function groups_getAll(){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(1, $user_row->id)){
            $query = $this->db
                            ->where('id !=', '1')
                            ->get('groups');                            
        }
        else{
            $query = $this->db->get('groups');
        }
        return $query->result();
    }

    function menus_getAll(){
        $query = $this->db
                        ->select('m1.descripcion AS parent_descr, m.id AS id, m.descripcion AS descr, m.*')
                        ->join('menus m1', 'm1.id = m.parent', 'left')
                        ->get('menus m');
        return $query->result();
    }

    function menus_getForPermisos(){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(1, $user_row->id)){
            $grupo = $this->ion_auth->get_users_groups($user_row->id)->result();
            foreach ($grupo as $f) {
                $g_id = $f->id;    
            }

            $query = $this->db
                        ->select('m1.descripcion AS parent_descr, m.id AS id, m.descripcion AS descr, m.*')
                        ->join('menus m1', 'm1.id = m.parent', 'left')
                        ->join('permisos p', 'p.menu_id = m.id')
                        ->where('p.group_id', $g_id)
                        ->get('menus m');
        }else{
            $query = $this->db
                        ->select('m1.descripcion AS parent_descr, m.id AS id, m.descripcion AS descr, m.*')
                        ->join('menus m1', 'm1.id = m.parent', 'left')
                        ->get('menus m');
        }    
        return $query->result();
    }

    function permisos_getAll(){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(1, $user_row->id)){
          $query = $this->db
                        ->select('p.*, g.name AS grupo, m.descripcion AS menu')
                        ->join('groups g','p.group_id = g.id')
                        ->join('menus m', 'p.menu_id = m.id')
                        ->where('p.group_id !=', 1)
                        ->get('permisos p');
        }else{
          $query = $this->db
                        ->select('p.*, g.name AS grupo, m.descripcion AS menu')
                        ->join('groups g','p.group_id = g.id')
                        ->join('menus m', 'p.menu_id = m.id')
                        ->get('permisos p');      
        }
        
        return $query->result();
    }

    function permisos_buscar(/*$perpage,$start*/){
        $query = $this->db
                        ->select('permisos.id AS id, menus.descripcion AS menu, groups.id AS id_grupo, groups.description AS grupo, permisos.read AS Leer, 
                            permisos.insert AS Insertar, permisos.update AS Actualizar, permisos.delete AS Borrar, permisos.exportar AS Exportar, permisos.imprimir AS Imprimir')
                        ->join('menus',     'menus.id = permisos.menu_id', 'inner')
                        ->join('groups',    'groups.id = permisos.group_id', 'inner');
        if($where = $this->input->post('buscar', TRUE))
            $query = $this->db->where('permisos.id =', $where)
                            ->or_where("menus.descripcion LIKE '%".$where."%'")
                            ->or_where("groups.description LIKE '%".$where."%'");
        $query = $this->db
                        ->get('permisos');
        return $query->result('array');
    }

    function permisos_getByLink($link){
        $query = $this->db
                    ->select('id')
                    ->where('link',$link)
                    ->get('menus');
        return $query->row();
    }

    function permisos_buscarPermiso($url, $perfil){
        $query = $this->db
                        ->select('p.read')
                        ->join('menus m', 'm.id = p.menu_id')
                        ->where('p.group_id', $perfil)
                        ->like('m.link', $url)
                        ->get('permisos p');
        return $query->row();
    }

    function permisos_buscarGrupo($usuario){
        $query = $this->db
                        ->select('group_id')
                        ->where('user_id', $usuario)
                        ->get('users_groups');
        return $query->row();
    }

    function users_getAll(){
        $user_row = $this->ion_auth->user()->row();
        if (!$this->ion_auth->in_group(1, $user_row->id)){
            $query = $this->db
                            ->select('u.*, g.name AS nombre_grupo')
                            ->join('users_groups up', 'up.user_id = u.id')
                            ->join('groups g', 'g.id = up.group_id')
                            ->where('g.id !=', 1)
                            ->get('users u');
        }
        else{
            $query = $this->db
                            ->select('u.*, g.name AS nombre_grupo')
                            ->join('users_groups up', 'up.user_id = u.id')
                            ->join('groups g', 'g.id = up.group_id')                            
                            ->get('users u');            
        }
        return $query->result();
    }
}