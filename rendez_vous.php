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

// Récupération des listes de patients et de dentistes
$patients = $pdo->query("SELECT id, CONCAT(prenom, ' ', nom) AS nom_complet FROM patients")->fetchAll(PDO::FETCH_ASSOC);
$dentistes = $pdo->query("SELECT id, CONCAT(prenom, ' ', nom) AS nom_complet FROM dentistes")->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = intval($_POST['patient_id']);
    $dentiste_id = intval($_POST['dentiste_id']);
    $date_rendez_vous = $_POST['date_rendez_vous'];
    $etat_paiement = $_POST['etat_paiement'];

    // Validation des champs
    if ($patient_id && $dentiste_id && $date_rendez_vous && $etat_paiement) {
        try {
            // Insertion du rendez-vous
            $stmt = $pdo->prepare("
                INSERT INTO rendez_vous (patient_id, dentiste_id, date_rendez_vous, etat_paiement) 
                VALUES (:patient_id, :dentiste_id, :date_rendez_vous, :etat_paiement)
            ");
            $stmt->execute([
                'patient_id' => $patient_id,
                'dentiste_id' => $dentiste_id,
                'date_rendez_vous' => $date_rendez_vous,
                'etat_paiement' => $etat_paiement
            ]);

            $message = "Le rendez-vous a été enregistré avec succès.";
        } catch (PDOException $e) {
            // Gestion des erreurs pour violation de contrainte d'unicité
            if ($e->getCode() == 23000) { // Code SQL pour "duplicate entry"
                $message = "Erreur : Un rendez-vous existe déjà pour ce patient ou ce dentiste à la même date et heure.";
            } else {
                $message = "Erreur lors de l'enregistrement : " . $e->getMessage();
            }
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enregistrement de rendez-vous</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.1);
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #00577a;
            color: #fff;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #004a63;
        }
        .message {
            margin-top: 15px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Créer un rendez-vous</h1>
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="patient_id">Patient</label>
                <select name="patient_id" id="patient_id" required>
                    <option value="">-- Sélectionnez un patient --</option>
                    <?php foreach ($patients as $patient): ?>
                        <option value="<?php echo $patient['id']; ?>">
                            <?php echo htmlspecialchars($patient['nom_complet']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="dentiste_id">Dentiste</label>
                <select name="dentiste_id" id="dentiste_id" required>
                    <option value="">-- Sélectionnez un dentiste --</option>
                    <?php foreach ($dentistes as $dentiste): ?>
                        <option value="<?php echo $dentiste['id']; ?>">
                            <?php echo htmlspecialchars($dentiste['nom_complet']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="date_rendez_vous">Date et heure</label>
                <input type="datetime-local" name="date_rendez_vous" id="date_rendez_vous" required>
            </div>
            <div class="form-group">
                <label for="etat_paiement">État du paiement</label>
                <select name="etat_paiement" id="etat_paiement" required>
                    <option value="non payé">Non payé</option>
                    <option value="payé">Payé</option>
                </select>
            </div>
            <button type="submit">Enregistrer</button>
        </form>
    </div>
</body>
</html>
