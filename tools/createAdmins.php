<?php
require_once '../models/TetAdmin.php';

// Ejemplo lista de admins (usernames y passwords simples, cámbialos)
$admins = array(
    array('username' => 'adminTET1', 'password' => 'pwdT3T2O25!admin172'),
    array('username' => 'adminTET2', 'password' => 'pwdT3T2O25!admin434'),
    array('username' => 'adminTET3', 'password' => 'pwdT3T2O25!admin213'),
    array('username' => 'adminTET4', 'password' => 'pwdT3T2O25!admin645'),
    array('username' => 'adminTET5', 'password' => 'pwdT3T2O25!admin578'),
    array('username' => 'adminTET6', 'password' => 'pwdT3T2O25!admin234'),
);

foreach ($admins as $adminData) {
    // Primero verifica si usuario ya existe para evitar duplicados
    $existingUser = TetAdmin::where('username', $adminData['username']);
    if ($existingUser) {
        echo "User {$adminData['username']} already exists.\n";
        continue;
    }

    $user = new TetAdmin(array(
        'username' => $adminData['username'],
        'pwd' => $adminData['password']
    ));

    // Hashear contraseña (usa password_compat si es necesario)
    $user->hashPassword();

    if ($user->save()) {
        echo "Created user {$adminData['username']} successfully.\n";
    } else {
        echo "Failed to create user {$adminData['username']}.\n";
    }
}
