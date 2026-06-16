<?php

function generateManualBcrypt($password, $salt, $secretKey) {
    // Gabungkan komponen sesuai dengan logika Anda
    $combinedPassword = $password . $salt . $secretKey;
    
    // Opsi cost untuk Bcrypt (semakin tinggi semakin lambat & aman, default biasanya 10 atau 12)
    $options = [
        'cost' => 12,
    ];

    // Fungsi native PHP untuk Bcrypt
    $hashedPassword = password_hash($combinedPassword, PASSWORD_BCRYPT, $options);
    
    return $hashedPassword;
}

// --- Contoh Penggunaan ---
$passwordInput = "rahasia123";
$salt = bin2hex(random_bytes(8)); // Contoh pembuatan salt acak
$secretKey = "SECRET_DARI_ENV";   // Simulasi env('AUTH_SECRET_KEY')

$hashed = generateManualBcrypt($passwordInput, $salt, $secretKey);

echo "Salt: " . $salt . "\n";
echo "Hash: " . $hashed . "\n";
?>