<?php 

require_once __DIR__ . "/../config/db.php";

class Producto{

    private $id;
    private $nombre;
    private $precio;
    private $descripcion;
    private $imagen;
    private $categoria_id;

    private $db;

    public function __construct(){
        $this->db = DataBase::conexion();
    }

    /* SETTERS */
    public function setId($id){
        $this->id = $id;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function setPrecio($precio){
        $this->precio = $precio;
    }
    public function setDescripcion($descripcion){
        $this->descripcion = $descripcion;
    }
    public function setImagen($imagen){
        $this->imagen = $imagen;
    }
    public function setCategoriaId($categoria_id){
        $this->categoria_id = $categoria_id;
    }

    public function count(){
        $query = $this->db->prepare("SELECT COUNT(id) as cantidad FROM producto");
        $query->execute();
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function countForCategoria(){
        $query = $this->db->prepare("SELECT COUNT(id) as cantidad FROM producto WHERE categoria_id = :categoria");
        $query->execute([
            ":categoria" => $this->categoria_id
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getAll($cuantos, $pagina){
        $desde = ($pagina -1) * $cuantos;

        $sql = "SELECT p.*, c.nombre as 'categoria' FROM producto p ";
        $sql .= " INNER JOIN categoria c ON c.id = p.categoria_id ORDER BY id DESC limit $cuantos offset $desde";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getForCategoria($cuantos, $pagina){
        $desde = ($pagina -1) * $cuantos;

        $sql = "SELECT * FROM producto WHERE categoria_id = :categoria limit $cuantos offset $desde";
        $query = $this->db->prepare($sql);
        $query->execute([
            ":categoria" => $this->categoria_id
        ]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function getOne(){
        $sql = "SELECT * FROM producto WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([
            ":id" => $this->id
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function search(){
        $sql = "SELECT * FROM producto WHERE nombre LIKE '{$this->nombre}%' ";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    public function save(){
        $sql = "INSERT INTO producto VALUES(null, :nombre,:precio,:descripcion,:imagen,:categoria)";
        $query = $this->db->prepare($sql);
        $save = $query->execute([
            ":nombre" => $this->nombre,
            ":precio" => $this->precio,
            ":descripcion" => $this->descripcion,
            ":imagen" => $this->imagen,
            ":categoria" => $this->categoria_id
        ]);

        return $save;
    }

    public function update($newImg = true){
        
        if($newImg){
            $sql = "UPDATE producto SET nombre = :nombre, precio = :precio, descripcion = :descripcion, imagen = :imagen, categoria_id = :categoria WHERE id = :id";
            $query = $this->db->prepare($sql);
            $update = $query->execute([
                ":id" => $this->id,
                ":nombre" => $this->nombre,
                ":precio" => $this->precio,
                ":descripcion" => $this->descripcion,
                ":imagen" => $this->imagen,
                ":categoria" => $this->categoria_id
            ]);
        }else{
            $sql = "UPDATE producto SET nombre = :nombre, precio = :precio, descripcion = :descripcion, categoria_id = :categoria WHERE id = :id";
            $query = $this->db->prepare($sql);
            $update = $query->execute([
                ":id" => $this->id,
                ":nombre" => $this->nombre,
                ":precio" => $this->precio,
                ":descripcion" => $this->descripcion,
                ":categoria" => $this->categoria_id
            ]);
        }
        
        return $update;
    }

    public function delete(){
        $sql = "DELETE FROM producto WHERE id = :id";
        $query = $this->db->prepare($sql);
        $delete = $query->execute([
            ":id" => $this->id
        ]);
        return $delete;
    }
}