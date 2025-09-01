<?php include "../inc/dbinfo.inc"; ?>

<html>
<head>
  <title>Gestão de Funcionários e Projetos</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
    h1, h2 { color: #333; }
    form, table { background: white; padding: 15px; border-radius: 8px; box-shadow: 0px 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
    input, select, textarea { padding: 6px; margin: 4px; width: 100%; max-width: 400px; }
    table { border-collapse: collapse; width: 100%; }
    td, th { padding: 8px; border-bottom: 1px solid #ddd; text-align: left; }
    tr:hover { background-color: #f1f1f1; }
    .btn { background: #4CAF50; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; }
    .btn:hover { background: #45a049; }
    textarea { resize: vertical; height: 60px; }
    .error { color: red; }
    .success { color: green; }
  </style>
</head>
<body>
<h1>Sistema de Gestão</h1>

<?php
/* Conexão com PostgreSQL */
$constring = "host=" . DB_SERVER . " dbname=" . DB_DATABASE . " user=" . DB_USERNAME . " password=" . DB_PASSWORD ;
$connection = pg_connect($constring);

if (!$connection){
  echo "<p class='error'>Falha na conexão com PostgreSQL</p>";
  exit;
}

/* Garantir tabelas */
VerifyEmployeesTable($connection, DB_DATABASE);
VerifyProjectsTable($connection, DB_DATABASE);

/* Processa formulários */
if ($_SERVER['REQUEST_METHOD'] === "POST") {

  if (isset($_POST['form_type']) && $_POST['form_type'] === "employee") {
      $name    = trim($_POST['NAME']);
      $address = trim($_POST['ADDRESS']);
      $team    = trim($_POST['TEAM']);
      $state   = trim($_POST['STATE']);
      if ($name !== "" || $address !== "") {
          AddEmployee($connection, $name, $address, $team, $state);
      }
  }

  if (isset($_POST['form_type']) && $_POST['form_type'] === "project") {
      $pname         = trim($_POST['PROJECT_NAME']);
      $company       = trim($_POST['PARTNER_COMPANY']);
      $start         = trim($_POST['START_DATE']);
      $end           = trim($_POST['END_DATE']);
      $responsibles  = trim($_POST['RESPONSIBLE_IDS']);
      $status        = trim($_POST['STATUS']);
      $value         = trim($_POST['VALUE']);
      $description   = trim($_POST['DESCRIPTION']);

      if ($pname !== "") {
          AddProject($connection, $pname, $company, $start, $end, $responsibles, $status, $value, $description);
      }
  }
}
?>

<!-- Formulário Funcionários -->
<h2>Adicionar Funcionário</h2>
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <input type="hidden" name="form_type" value="employee" />
  <label>Nome: <input type="text" name="NAME" /></label><br>
  <label>Endereço: <input type="text" name="ADDRESS" /></label><br>
  <label>Time: <input type="text" name="TEAM" /></label><br>
  <label>Estado: <input type="text" name="STATE" /></label><br>
  <input type="submit" class="btn" value="Adicionar Funcionário" />
</form>

<!-- Lista Funcionários -->
<h2>Lista de Funcionários</h2>
<table>
  <tr>
    <th>ID</th><th>Nome</th><th>Endereço</th><th>Time</th><th>Estado</th>
  </tr>
<?php
$result = pg_query($connection, "SELECT * FROM EMPLOYEES ORDER BY ID ASC");
while($row = pg_fetch_assoc($result)) {
  echo "<tr>
          <td>{$row['id']}</td>
          <td>{$row['name']}</td>
          <td>{$row['address']}</td>
          <td>{$row['team']}</td>
          <td>{$row['state']}</td>
        </tr>";
}
?>
</table>

<!-- Formulário Projetos -->
<h2>Adicionar Projeto</h2>
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
  <input type="hidden" name="form_type" value="project" />
  <label>Nome do Projeto: <input type="text" name="PROJECT_NAME" /></label><br>
  <label>Empresa Parceira: <input type="text" name="PARTNER_COMPANY" /></label><br>
  <label>Data Início: <input type="date" name="START_DATE" /></label><br>
  <label>Data Fim: <input type="date" name="END_DATE" /></label><br>
  <label>Responsáveis (IDs separados por vírgula): <input type="text" name="RESPONSIBLE_IDS" /></label><br>
  <label>Status:
    <select name="STATUS">
      <option value="Planejado">Planejado</option>
      <option value="Em negociação">Em negociação</option>
      <option value="Contrato fechado">Contrato fechado</option>
      <option value="Em andamento">Em andamento</option>
      <option value="Concluído">Concluído</option>
    </select>
  </label><br>
  <label>Valor do Projeto: <input type="number" name="VALUE" step="0.01" /></label><br>
  <label>Descrição:<br><textarea name="DESCRIPTION"></textarea></label><br>
  <input type="submit" class="btn" value="Adicionar Projeto" />
</form>

<!-- Lista Projetos -->
<h2>Lista de Projetos</h2>
<table>
  <tr>
    <th>ID</th><th>Projeto</th><th>Parceiro</th><th>Início</th><th>Fim</th><th>Responsáveis</th><th>Status</th><th>Valor</th><th>Descrição</th>
  </tr>
<?php
$result = pg_query($connection, "SELECT * FROM PROJECTS ORDER BY ID ASC");
while($row = pg_fetch_assoc($result)) {
  echo "<tr>
          <td>{$row['id']}</td>
          <td>{$row['project_name']}</td>
          <td>{$row['partner_company']}</td>
          <td>{$row['start_date']}</td>
          <td>{$row['end_date']}</td>
          <td>{$row['responsible_ids']}</td>
          <td>{$row['status']}</td>
          <td>{$row['value']}</td>
          <td>{$row['description']}</td>
        </tr>";
}
?>
</table>

<?php
pg_close($connection);

/* -------- Funções -------- */
function AddEmployee($connection, $name, $address, $team, $state) {
   $n = pg_escape_string($name);
   $a = pg_escape_string($address);
   $t = pg_escape_string($team);
   $s = pg_escape_string($state);
   $query = "INSERT INTO EMPLOYEES (NAME, ADDRESS, TEAM, STATE) VALUES ('$n', '$a', '$t', '$s')";
   $result = pg_query($connection, $query);
   if (!$result) echo "<p class='error'>Erro ao adicionar funcionário: ".pg_last_error($connection)."</p>";
}

function AddProject($connection, $pname, $company, $start, $end, $responsibles, $status, $value, $description) {
   $pn = pg_escape_string($pname);
   $c  = pg_escape_string($company);
   $r  = pg_escape_string($responsibles);
   $st = pg_escape_string($status);
   $d  = pg_escape_string($description);

   $start_q = $start !== "" ? "'$start'" : "NULL";
   $end_q   = $end !== "" ? "'$end'" : "NULL";
   $value_q = $value !== "" ? floatval($value) : "NULL";

   $query = "INSERT INTO PROJECTS (PROJECT_NAME, PARTNER_COMPANY, START_DATE, END_DATE, RESPONSIBLE_IDS, STATUS, VALUE, DESCRIPTION)
             VALUES ('$pn', '$c', $start_q, $end_q, '$r', '$st', $value_q, '$d')";
   $result = pg_query($connection, $query);
   if (!$result) echo "<p class='error'>Erro ao adicionar projeto: ".pg_last_error($connection)."</p>";
}

function VerifyEmployeesTable($connection, $dbName) {
  if(!TableExists("EMPLOYEES", $connection, $dbName)) {
    $query = "CREATE TABLE EMPLOYEES (
      ID serial PRIMARY KEY,
      NAME VARCHAR(45),
      ADDRESS VARCHAR(90),
      TEAM VARCHAR(50),
      STATE VARCHAR(30)
    )";
    pg_query($connection, $query);
  }
}

function VerifyProjectsTable($connection, $dbName) {
  if(!TableExists("PROJECTS", $connection, $dbName)) {
    $query = "CREATE TABLE PROJECTS (
      ID serial PRIMARY KEY,
      PROJECT_NAME VARCHAR(100),
      PARTNER_COMPANY VARCHAR(100),
      START_DATE DATE,
      END_DATE DATE,
      RESPONSIBLE_IDS TEXT,
      STATUS VARCHAR(50),
      VALUE NUMERIC(12,2),
      DESCRIPTION TEXT
    )";
    pg_query($connection, $query);
  }
}

function TableExists($tableName, $connection, $dbName) {
  $t = strtolower(pg_escape_string($tableName));
  $query = "SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_NAME = '$t'";
  $checktable = pg_query($connection, $query);
  return (pg_num_rows($checktable) > 0);
}
?>
</body>
</html>
