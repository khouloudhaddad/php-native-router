<?php
class UserController {
    public static function show(string $id): void {
        echo "User ID: " . htmlspecialchars($id);
    }

    public static function create(): void {
        echo "User created!";
    }
}