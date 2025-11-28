<?php
session_start();

// --- 1. CONEXÃO COM O BANCO ---
// Define os caminhos possíveis para o arquivo de conexão
$caminho_config = dirname(__DIR__) . '/config/db.php';
$caminho_html   = dirname(__DIR__) . '/html/conexao.php';

// Tenta carregar o arquivo de conexão
if (file_exists($caminho_config)) {
    require_once $caminho_config;
} elseif (file_exists($caminho_html)) {
    require_once $caminho_html;
} else {
    // Tenta caminhos relativos simples como última tentativa
    if (file_exists('../html/conexao.php')) {
        require_once '../html/conexao.php';
    } elseif (file_exists('../config/db.php')) {
        require_once '../config/db.php';
    } else {
        die("Erro: Arquivo de conexão com banco de dados não encontrado. Verifique se 'config/db.php' ou 'html/conexao.php' existem.");
    }
}

// Garante compatibilidade entre variáveis de conexão ($conn vs $conexao)
if (isset($conexao) && !isset($conn)) $conn = $conexao;
if (!isset($conn)) die("Erro: Conexão não estabelecida. Verifique o arquivo de banco de dados.");


// --- 2. PROCESSAMENTO ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];

    if (empty($email) || empty($senha)) {
        header("Location: ../login.php?erro=vazio");
        exit();
    }

    // Prepara a query SQL
    $sql = "SELECT id, nome, email, senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            // Verifica a senha
            if (password_verify($senha, $usuario['senha'])) {
                
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nome_usuario'] = $usuario['nome'];
                $_SESSION['email_usuario'] = $usuario['email'];
                $_SESSION['loggedin'] = true;

                $destino = '';

                // Lógica de redirecionamento por domínio
                // A) ALUNO
                if (stripos($email, '@senaimgaluno.com.br') !== false) {
                    $_SESSION['usuario_tipo'] = 'aluno';
                    $destino = '../html/dashboard.php'; 
                
                // B) PROFESSOR
                } elseif (stripos($email, '@docente.edu.br') !== false) {
                    $_SESSION['usuario_tipo'] = 'professor';
                    $destino = '../html/dashboard_professor.php'; 
                
                // C) VISITANTE
                } else {
                    $_SESSION['usuario_tipo'] = 'visitante';
                    $destino = '../html/paginavisitante.php';
                }

                header("Location: " . $destino);
                exit();

            } else {
                header("Location: ../login.php?erro=senha");
                exit();
            }
        } else {
            header("Location: ../login.php?erro=usuario");
            exit();
        }
        $stmt->close();
    } else {
        die("Erro no Banco: " . $conn->error);
    }
    $conn->close();

} else {
    header("Location: ../login.php");
    exit();
}
?>