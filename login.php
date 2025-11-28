<?php
// Inicializa a variável de erro vazia
$erro = "";

// Verifica se o formulário foi enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Pega o e-mail e remove espaços extras
    $email = trim($_POST['email']);

    // Valida se é um e-mail real
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        // 2. Extrai o domínio (tudo depois do @) e converte para minúsculo
        $partes = explode('@', $email);
        $dominio = strtolower(end($partes));

        // --- ÁREA DE CONFIGURAÇÃO ---
        
        // Domínio 1
        if ($dominio == '@senaimgaluno.com.br') {
            header("Location: ../html/dashboard.php");
            exit; // O 'exit' é obrigatório após um redirecionamento
        }
        
        // Domínio 2
        elseif ($dominio == '@docente.edu.br') {
            header("Location: ../html/dasboard_professor.php");
            exit;
        }
        
        // Caso o domínio não seja nenhum dos dois
        else {
            $erro = "Este domínio ({$dominio}) não tem permissão de acesso.";
        }

        // -----------------------------

    } else {
        $erro = "Por favor, digite um endereço de e-mail válido.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Seguro</title>
    <style>
        body { font-family: sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 350px; text-align: center; }
        input { width: 100%; padding: 12px; margin: 15px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #0d6efd; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #0b5ed7; }
        .msg-erro { color: #dc3545; background-color: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #f5c6cb; font-size: 14px; }
    </style>
</head>
<body>

<div class="box">
    <h2>Acesso ao Sistema</h2>
    
    <?php if (!empty($erro)): ?>
        <div class="msg-erro"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="email" style="display:block; text-align:left; color:#555; font-size:0.9em;">E-mail Corporativo</label>
        <input type="email" name="email" id="email" placeholder="nome@empresa.com" required>
        <button type="submit">Continuar</button>
    </form>
</div>

</body>
</html>