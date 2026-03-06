<?php
require_once __DIR__ . '/../config.php';

// Buscar os dados do usuário a ser editado
//Trazer os dados do usuário a ser editado
//sobrescrever os dados 

$id = $_GET['id'] ?? null;  https://pairdrop.net/

// && verdadeiros   || apenas 1 verdadeiro   ! negação

if (!$id) {
    die("Usuário não encontrado.");
}

$sql = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $conexao->prepare($sql);
$stmt->execute(['id' => $id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    die("Usuário não encontrado.");
}
 
// Inicializar variáveis com dados do usuário para exibição no formulário
$nome = $usuario['nome'] ?? '';
$email = $usuario['email'] ?? '';
$mensagem_sucesso = '';
$mensagem_erro = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_post = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $email = $_POST['email'] ?? null;
    $senha = $_POST['senha'] ?? null;
    
    // Validar consistência de ID
    if ($id_post != $id) {
        $mensagem_erro = "Erro: ID do usuário inválido.";
    }
    // Validar os dados
    else if (empty($nome) || empty($email)) {
        $mensagem_erro = "Nome e e-mail são obrigatórios.";
    }
    else {
        try {
            // Atualizar o usuário no banco de dados
            $sql = "UPDATE usuarios SET nome = :nome, email = :email" . 
            (!empty($senha) ? ", senha = :senha" : "") . " WHERE id = :id";
            $stmt = $conexao->prepare($sql);
            
            $params = [
                'nome' => $nome,
                'email' => $email,
                'id' => $id
            ];

            if (!empty($senha)) {
                $params['senha'] = password_hash($senha, PASSWORD_DEFAULT);
            }

            $stmt->execute($params);
            
            $mensagem_sucesso = "Usuário atualizado com sucesso!";
            
            
            header("Refresh: 2; url=" . BASE_URL . "/usuarios/listar.php");
        } catch (Exception $e) {
            $mensagem_erro = "Erro ao atualizar usuário: " . $e->getMessage();
        }
    }
}

$titulo = "Editar Usuário |";
require_once BASE_PATH . '/includes/cabecalho.php';
?>

<section class="mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3 class="text-center"><i class="bi bi-pencil-fill"></i> Editar Usuário</h3>

    <?php if (!empty($mensagem_sucesso)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($mensagem_sucesso); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($mensagem_erro)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($mensagem_erro); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="post" class="w-75 mx-auto">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <div class="form-group">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" name="nome" class="form-control" id="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
        </div>
        <div class="form-group">
            <label for="email" class="form-label">E-mail:</label>
            <input type="email" name="email" class="form-control" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="form-group">
            <label for="senha" class="form-label">Senha:</label>
            <input type="password" name="senha" class="form-control" id="senha" placeholder="Preencha apenas se for alterar">
        </div>
        <button class="btn btn-warning my-4" type="submit"><i class="bi bi-arrow-clockwise"></i> Salvar Alterações</button>
        <a href="<?php echo BASE_URL; ?>/usuarios" class="btn btn-secondary my-4"><i class="bi bi-x-circle"></i> Cancelar</a>
    </form>
</section>

<?php require_once BASE_PATH . '/includes/rodape.php'; ?>