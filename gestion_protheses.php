<?php
// Connexion à la base de données
$host = "localhost";
$dbname = "db";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement pour ajouter une nouvelle prothèse
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $type_prothese = $_POST['type_prothese'];
    $date_production = $_POST['date_production'];
    $dentiste_id = intval($_POST['dentiste_id']);
    $patient_id = intval($_POST['patient_id']);
    $statut = $_POST['statut'];

    if ($type_prothese && $date_production && $dentiste_id && $patient_id && $statut) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO protheses (type_prothese, date_production, dentiste_id, patient_id, statut)
                VALUES (:type_prothese, :date_production, :dentiste_id, :patient_id, :statut)
            ");
            $stmt->execute([
                ':type_prothese' => $type_prothese,
                ':date_production' => $date_production,
                ':dentiste_id' => $dentiste_id,
                ':patient_id' => $patient_id,
                ':statut' => $statut
            ]);
            $message = "Prothèse ajoutée avec succès.";
        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout : " . $e->getMessage();
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}

// Récupération des données existantes pour affichage
$protheses = $pdo->query("
    SELECT p.id, p.type_prothese, p.date_production, p.statut,
           CONCAT(d.prenom, ' ', d.nom) AS dentiste,
           CONCAT(pt.prenom, ' ', pt.nom) AS patient
    FROM protheses p
    JOIN dentistes d ON p.dentiste_id = d.id
    JOIN patients pt ON p.patient_id = pt.id
")->fetchAll(PDO::FETCH_ASSOC);

$dentistes = $pdo->query("SELECT id, CONCAT(prenom, ' ', nom) AS nom_complet FROM dentistes")->fetchAll(PDO::FETCH_ASSOC);
$patients = $pdo->query("SELECT id, CONCAT(prenom, ' ', nom) AS nom_complet FROM patients")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Prothèses</title>
  <style>
   
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f9f9f9;
        color: #333;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        background: #333;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    header {
        text-align: center;
        margin-bottom: 20px;
    }

    h1 {
        font-size: 1.8em;
        color: #007bff;
        margin: 0;
    }

    .message {
        margin-bottom: 20px;
        padding: 10px;
        background-color: #e9f7ef;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 5px;
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table thead tr {
        background-color:rgba(0, 87, 179, 0.44);
        color: #fff;
    }

    table th, table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    form label {
        font-weight: bold;
        color: #f9f9f9;
    }

    form input, form select, form button {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 1em;
    }

    form input:focus, form select:focus {
        border-color: #007bff;
        outline: none;
    }

    form button {
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    form button:hover {
        background-color: #0056b3;
    }
</style>

 
</head>
<body>
<div class="container">
    <header>
        <h1>Gestion des Prothèses</h1>
    </header>
    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <table>
        <thead>
            <tr>
                <th>Type de Prothèse</th>
                <th>Date de Production</th>
                <th>Dentiste</th>
                <th>Patient</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($protheses as $prothese): ?>
            <tr>
                <td><?php echo htmlspecialchars($prothese['type_prothese']); ?></td>
                <td><?php echo htmlspecialchars($prothese['date_production']); ?></td>
                <td><?php echo htmlspecialchars($prothese['dentiste']); ?></td>
                <td><?php echo htmlspecialchars($prothese['patient']); ?></td>
                <td><?php echo htmlspecialchars($prothese['statut']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Ajouter une nouvelle Prothèse</h2>
    <form method="POST" action="">
        <label for="type_prothese">Type de Prothèse :</label>
        <input type="text" name="type_prothese" id="type_prothese" required>

        <label for="date_production">Date de Production :</label>
        <input type="date" name="date_production" id="date_production" required>

        <label for="dentiste_id">Dentiste :</label>
        <select name="dentiste_id" id="dentiste_id" required>
            <option value="">-- Sélectionnez un dentiste --</option>
            <?php foreach ($dentistes as $dentiste): ?>
                <option value="<?php echo $dentiste['id']; ?>">
                    <?php echo htmlspecialchars($dentiste['nom_complet']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="patient_id">Patient :</label>
        <select name="patient_id" id="patient_id" required>
            <option value="">-- Sélectionnez un patient --</option>
            <?php foreach ($patients as $patient): ?>
                <option value="<?php echo $patient['id']; ?>">
                    <?php echo htmlspecialchars($patient['nom_complet']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="statut">Statut :</label>
        <select name="statut" id="statut" required>
            <option value="en cours">En cours</option>
            <option value="terminée">Terminée</option>
            <option value="livrée">Livrée</option>
        </select>

        <button type="submit">Ajouter</button>
    </form>
</div>
</body>
</html>
