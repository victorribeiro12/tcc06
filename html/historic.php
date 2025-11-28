<?php
session_start();

// --- CORREÇÃO DA CONEXÃO ---
// Tenta encontrar o arquivo de conexão na pasta atual ou na pasta config
if (file_exists('conexao.php')) {
    include 'conexao.php';
} elseif (file_exists('../config/db.php')) {
    include '../config/db.php';
} else {
    // Para o script aqui se não achar, evitando o erro Fatal mais abaixo
    die("<div style='color: white; background: #D13438; padding: 20px; font-family: sans-serif;'>
            <strong>Erro Crítico:</strong> Arquivo de conexão não encontrado.<br>
            Verifique se o arquivo <b>conexao.php</b> está na pasta <i>html</i> ou se <b>db.php</b> está na pasta <i>config</i>.
         </div>");
}

// Compatibilidade: Se o seu arquivo usa $conexao, criamos $conn para padronizar
if (isset($conexao) && !isset($conn)) {
    $conn = $conexao;
}

// Agora é seguro usar o banco
$nome_a_exibir = isset($_SESSION['nome_usuario']) ? $_SESSION['nome_usuario'] : 'Convidado';
$id_usuario = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 1; 

// 1. Busca os cursos em que o usuário está inscrito
$sql_inscricoes = "
    SELECT c.*, i.data_inscricao 
    FROM cursos c 
    JOIN inscricoes i ON c.id = i.id_curso 
    WHERE i.id_usuario = $id_usuario
    ORDER BY i.data_inscricao DESC
";
$result_inscricoes = $conn->query($sql_inscricoes);

$meus_cursos = [];
if ($result_inscricoes && $result_inscricoes->num_rows > 0) {
    while($row = $result_inscricoes->fetch_assoc()) {
        $meus_cursos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico - Autocademy</title>
    <!-- CSS Padronizado -->
    <link rel="stylesheet" href="../css/style4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo-area">
            <img src="../imagens/logo.png" alt="Logo">
            <span class="logo-text">Autocademy</span>
        </div>

        <a href="dashboard.php" class="nav-link">
            <img src="../imagens/iconhome.png" alt="Início">
            <span class="nav-text">Início</span>
        </a>

        <a href="materias.php" class="nav-link">
            <img src="../imagens/icons8-livros-96.png" alt="Matérias">
            <span class="nav-text">Matérias</span>
        </a>

        <a href="chat.html" class="nav-link">
           <img src="../imagens/chat.png" alt="Chat">
            <span class="nav-text">Chat</span>
        </a>

        <!-- LINK ATIVO -->
        <a href="historic.php" class="nav-link active">
            <img src="../imagens/iconhistorico.png" alt="Histórico">
            <span class="nav-text">Histórico</span>
        </a>

        <div class="spacer"></div>

        <a href="config.html" class="nav-link">
            <img src="../imagens/icons8-configurações-150.png" alt="Config">
            <span class="nav-text">Configurações</span>
        </a>
    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="main-wrapper">
        
        <header class="top-header">
            <h2 style="font-size: 1.2rem; color: var(--text-primary); font-weight: 600;">Meu Histórico & Progresso</h2>
            
            <div class="user-profile">
                <span><?php echo $nome_a_exibir; ?></span>
                <div class="avatar-circle"><i class="fa-solid fa-user"></i></div>
            </div>
        </header>

        <div class="scroll-content">
            <div class="container" style="max-width: 1000px;">

                <!-- CAMPO DE BUSCA -->
                <div class="history-search-container">
                    <i class="fa-solid fa-search" style="color: var(--text-secondary);"></i>
                    <input type="text" id="searchInput" class="history-search-input" placeholder="Pesquisar por atividade, matéria ou exercício...">
                </div>

                <!-- SEÇÃO 1: ATIVIDADES PENDENTES -->
                <div class="section-divider">
                    <i class="fa-regular fa-clock"></i> Pendências e Exercícios
                </div>

                <div id="pendingList">
                    <div class="task-card" data-search="matemática lista exercício">
                        <div class="task-icon"><i class="fa-solid fa-list-check"></i></div>
                        <div class="task-info">
                            <span class="task-title">Lista de Exercícios: Cálculo I</span>
                            <div class="task-meta">Matemática Avançada • <span class="badge-due">Vence Hoje</span></div>
                        </div>
                        <a href="#" class="task-action">Continuar</a>
                    </div>

                    <div class="task-card" data-search="mecânica motor vídeo">
                        <div class="task-icon"><i class="fa-solid fa-play"></i></div>
                        <div class="task-info">
                            <span class="task-title">Aula: Montagem de Cabeçote</span>
                            <div class="task-meta">Mecânica de Precisão • <span class="badge-pending">Parou em 50%</span></div>
                        </div>
                        <a href="#" class="task-action">Assistir</a>
                    </div>

                    <div class="task-card" data-search="elétrica diagrama pdf">
                        <div class="task-icon"><i class="fa-solid fa-file-pdf"></i></div>
                        <div class="task-info">
                            <span class="task-title">Leitura: Diagramas Elétricos</span>
                            <div class="task-meta">Elétrica Automotiva • <span class="badge-pending">Não Iniciado</span></div>
                        </div>
                        <a href="#" class="task-action">Ler</a>
                    </div>
                </div>

                <!-- SEÇÃO 2: MATÉRIAS INSCRITAS -->
                <div class="section-divider">
                    <i class="fa-solid fa-book"></i> Meus Cursos Inscritos
                </div>

                <div class="materias-grid" id="courseList">
                    <?php if (!empty($meus_cursos)): ?>
                        <?php foreach($meus_cursos as $curso): ?>
                            <?php 
                                $img_src = "../" . $curso['imagem_url'];
                                $fallback = "https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&q=80";
                            ?>
                            
                            <a href="materia_detalhes.php?id=<?php echo $curso['id']; ?>" 
                               class="materia-card search-item" 
                               style="text-decoration: none;"
                               data-search="<?php echo strtolower($curso['nome']); ?>">
                                
                                <img src="<?php echo $img_src; ?>" class="card-bg" onerror="this.src='<?php echo $fallback; ?>'">
                                <div class="card-overlay">
                                    <div>
                                        <h2 class="card-title"><?php echo $curso['nome']; ?></h2>
                                        <span style="font-size: 0.8rem; color: #ccc;">Inscrito em: <?php echo date('d/m/Y', strtotime($curso['data_inscricao'])); ?></span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-secondary); background: var(--bg-card); border-radius: 12px; border: 1px dashed var(--border-color);">
                            <i class="fa-solid fa-folder-open" style="font-size: 2rem; margin-bottom: 10px;"></i>
                            <p>Você ainda não está inscrito em nenhum curso.</p>
                            <a href="materias.php" style="color: var(--accent-color); font-weight: bold; margin-top: 10px; display: inline-block;">Ver Catálogo</a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </main>

    <script src="tema.js"></script>
</body>
</html>