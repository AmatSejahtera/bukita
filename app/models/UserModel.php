<?php
class UserModel
{
    private $table = 'user';
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getAllUser()
    {
        $this->db->query('SELECT * FROM ' . $this->table); // Menambahkan spasi setelah FROM
        return $this->db->resultSet();
    }

    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id'); // Menambahkan spasi setelah tabel dan WHERE
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function tambahUser($data)
    {
        $query = "INSERT INTO user (nama, username, password) VALUES (:nama, :username, :password)";
        $this->db->query($query);
        $this->db->bind('nama', $data['nama']);
        $this->db->bind('username', $data['username']);
        $this->db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT)); // Mengganti md5 dengan password_hash
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function cekUsername($username)
    {
        $this->db->query("SELECT * FROM user WHERE username = :username");
        $this->db->bind('username', $username);
        return $this->db->single();
    }

    public function updateDataUser($data)
    {
        if (empty($data['password'])) { // Mengganti $_POST dengan $data untuk konsistensi
            $query = "UPDATE user SET nama = :nama WHERE id = :id";
            $this->db->query($query);
            $this->db->bind('id', $data['id']);
            $this->db->bind('nama', $data['nama']);
        } else {
            $query = "UPDATE user SET nama = :nama, password = :password WHERE id = :id";
            $this->db->query($query);
            $this->db->bind('id', $data['id']);
            $this->db->bind('nama', $data['nama']);
            $this->db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT)); // Mengganti md5 dengan password_hash
        }
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteUser($id)
    {
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function cariUser($key)
    {
        $key = htmlspecialchars($key); // Membersihkan input untuk mencegah XSS
        $this->db->query("SELECT * FROM " . $this->table . " WHERE nama LIKE :key");
        $this->db->bind('key', "%$key%");
        return $this->db->resultSet();
    }
}
