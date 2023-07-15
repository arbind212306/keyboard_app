<?php
require_once dirname(__FILE__) . '/Model/Keyboard.php';

$response = [
  'status' => '',
  'message' => '',
  'data' => '',
];

if (isset($_POST['action']) && !empty($_POST['action'])) {
    $user_id = (int) $_POST['user_id'];

    switch ($_POST['action']) {
        case 'create_user':
            echo createUser($user_id);
            retun;
        case 'update_control':
            $data = ['user_id' => $user_id, 'control' => $_POST['control']];
            echo updateControlKeyboard($data);
            return;
        case 'update_key_status':
            $data = [
                'user_id' => $user_id,
                'control' => $_POST['control'],
                'key_name' => $_POST['key_name'],
                'key_value' => $_POST['key_value']
            ];
            echo updateControlKeyboard($data);
            return;
        case 'release_control':
            echo releaseControl();
            return;
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'fetch_data') {
    $user_id = (int) $_GET['user_id'];
    $data = getData($user_id);
    foreach ($data as $key => $value) {
        if (strpos($key, 'key_') === 0) {
            $data['keys'][$key] = $value;
            unset($data[$key]);
        }
    }

    $response = [
        'status' => 'successfull',
        'data' => $data
    ];
    echo json_encode($response);
}

function createUser(int $user_id) {
    $model = new Keyboard();
    $model->delete($user_id);
    return $model->addUser($user_id);
}

function updateControlKeyboard(array $data) {
    $model = new Keyboard();
    return $model->updateKeboard($data);
}

function getData(int $user_id) {
    $model = new Keyboard();
    return $model->getData($user_id);
}

function releaseControl() {
    $model = new Keyboard();
    return $model->relaseControl();
}

