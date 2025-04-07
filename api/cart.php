<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    switch ($data['action']) {
        case 'add':
            $flower_id = $data['flower_id'];
            if (isset($_SESSION['cart'][$flower_id])) {
                $_SESSION['cart'][$flower_id]++;
            } else {
                $_SESSION['cart'][$flower_id] = 1;
            }
            echo json_encode(['success' => true]);
            break;
            
        case 'remove':
            $flower_id = $data['flower_id'];
            if (isset($_SESSION['cart'][$flower_id])) {
                unset($_SESSION['cart'][$flower_id]);
            }
            echo json_encode(['success' => true]);
            break;

        case 'increase':
            $flower_id = $data['flower_id'];
            if (isset($_SESSION['cart'][$flower_id])) {
                $_SESSION['cart'][$flower_id]++;
            }
            echo json_encode(['success' => true]);
            break;

        case 'decrease':
            $flower_id = $data['flower_id'];
            if (isset($_SESSION['cart'][$flower_id])) {
                if ($_SESSION['cart'][$flower_id] > 1) {
                    $_SESSION['cart'][$flower_id]--;
                } else {
                    unset($_SESSION['cart'][$flower_id]);
                }
            }
            echo json_encode(['success' => true]);
            break;
    }
}
