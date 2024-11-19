<?php
class User extends Controller
{
    public function index()
    {
        $data['title'] = 'Data User';
        $data['user'] = $this->model('UserModel')->getAllUser();
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('user/index', $data);
        $this->view('templates/footer');
    }

    public function cari()
    {
        $data['title'] = 'Data User';
        $data['key'] = $_POST['key'] ?? ''; // Pastikan key ada atau default ke string kosong
        $data['user'] = $this->model('UserModel')->cariUser($data['key']);
        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('user/index', $data);
        $this->view('templates/footer');
    }

    public function edit($id)
    {
        $data['title'] = 'Edit User';
        $data['user'] = $this->model('UserModel')->getUserById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];

            if ($password === $confirmPassword) {
                if ($this->model('UserModel')->updateUser($id, $name, $email, $password)) {
                    Flasher::setMessage('Berhasil', 'diupdate', 'success');
                    header('Location: ' . base_url . '/user');
                    exit;
                } else {
                    Flasher::setMessage('Gagal', 'diupdate', 'danger');
                    header('Location: ' . base_url . '/user');
                    exit;
                }
            } else {
                Flasher::setMessage('Gagal', 'password tidak sama.', 'danger');
                header('Location: ' . base_url . '/user/edit/' . $id);
                exit;
            }
        }

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('user/edit', $data);
        $this->view('templates/footer');
    }

    public function hapus($id)
    {
        if ($this->model('UserModel')->deleteUser($id)) {
            Flasher::setMessage('Berhasil', 'dihapus', 'success');
        } else {
            Flasher::setMessage('Gagal', 'dihapus', 'danger');
        }
        header('Location: ' . base_url . '/user');
        exit;
    }
}
