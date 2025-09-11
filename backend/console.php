#!/usr/bin/env php
<?php
// Make sure script is run from CLI
if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.\n");
}

// Example: connect to SQLite
$db = new PDO('sqlite:database.db');

// Simple CLI menu
echo "PHP CLI Console\n";
echo "1. List users\n";
echo "2. Add user\n";
echo "3. Clear users\n";
echo "0. Exit\n";
do {        
    echo "Choose an option: ";
    $choice = trim(string: fgets(STDIN));

    switch ($choice) {
        case '1':
            $stmt = $db->query("SELECT * FROM users");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            print_r($users);
            break;
        
        case '2':
            echo "Enter username: ";
            $username = trim(fgets(STDIN));
            echo "Enter password: ";
            $password = trim(fgets(STDIN));
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->execute([
                ':username' => $username,
                ':password' => password_hash($password, PASSWORD_DEFAULT)
            ]);
            echo "User added!\n";
            break;
        
        case '3':
            $stmt = $db->prepare("DELETE FROM users");
            $stmt->execute();
            echo "Users cleared!\n";
            break;


        case '0'||'exit':
            echo "Goodbye!\n";
            exit;
        default:
            echo "Invalid option.\n";
    }

} while ($choice !== '0');
