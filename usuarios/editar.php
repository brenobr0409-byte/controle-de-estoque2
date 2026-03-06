<?php
require_once __DIR__ . '/../config.php';

//Buscar os dados do usuário a ser editado

//Trazer os dados usuários a ser editado

//Sobreescrever os dados

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID do usuário não encontrado");
}

$sql = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $conexao->prepare($sql);
$stmt->execute(['id' => $id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);


if (!$usuario) {
    die("Usuário não encontrado no banco.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $email = $_POST['email'] ?? null;
    $senha = $_POST['senha'] ?? null;


    if (empty($senha)) {
        $sql = "UPDATE usuarios
    SET nome = :nome, email = :email
    WHERE id = :id";

        $sql = $conexao->prepare($sql);
        $sql->execute([
            'nome' => $nome,
            'email' => $email,
            'id' => $id,

        ]);
    } else {
        $sql = "UPDATE usuarios
    SET nome = :nome, email = :email, senha = :senha
    WHERE id = :id";

        $sql = $conexao->prepare($sql);
        $sql->execute([
            'nome' => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'id' => $id,

        ]);
    }
    header("Location: listar.php?sucesso=editado");
    exit;
}

$titulo = "Editar Usuário |";
require_once BASE_PATH . '/includes/cabecalho.php';
?>

<section class="mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3 class="text-center"><i class="bi bi-pencil-fill"></i> Editar Usuário</h3>



    <form method="post" class="w-75 mx-auto">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
        <div class="form-group">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" name="nome" class="form-control" id="nome" value="<?= $usuario['nome'] ?>">
        </div>
        <div class="form-group">
            <label for="email" class="form-label">E-mail:</label>
            <input type="email" name="email" class="form-control" id="email" value="<?= $usuario['email'] ?>">
        </div>
        <div class="form-group">
            <label for="senha" class="form-label">Senha:</label>
            <input type="password" name="senha" class="form-control" id="senha" placeholder="Preencha apenas se for alterar">
        </div>
        <button class="btn btn-warning my-4" type="submit"><i class="bi bi-arrow-clockwise"></i> Salvar Alterações</button>
    </form>
</section>

<?php require_once BASE_PATH . '/includes/rodape.php'; ?>