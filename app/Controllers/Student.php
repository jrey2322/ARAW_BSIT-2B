<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Models\LogModel;
use App\Models\StudentModel;

class Student extends Controller
{
    public function index(){
        $model = new StudentModel();
        $data['student'] = $model->findAll();
        return view('student/index', $data);
    }

    public function save(){
        $name = $this->request->getPost('name');
        $bday = $this->request->getPost('bday');
        $address = $this->request->getPost('address');

        $userModel = new \App\Models\StudentModel();
        $logModel = new LogModel();

        $data = [
            'name'       => $name,
            'bday'       => $bday,
            'address'    => $address
        ];

        if ($userModel->insert($data)) {
            $logModel->addLog('New Student has been added: ' . $name, 'ADD');
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save student']);
        }
    }