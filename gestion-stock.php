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

// Gestion des actions
$action = $_GET['action'] ?? '';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'add') {
        // Ajouter un article
        $nom_article = $_POST['nom_article'];
        $quantite = $_POST['quantite'];
        $prix = $_POST['prix'];
        $fournisseur = $_POST['fournisseur'];

        if (!empty($nom_article) && $quantite > 0 && $prix > 0) {
            $stmt = $pdo->prepare("INSERT INTO stock (nom_article, quantite, prix, fournisseur) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nom_article, $quantite, $prix, $fournisseur]);
            $message = "Article ajouté avec succès.";
        } else {
            $message = "Veuillez remplir correctement tous les champs.";
        }
    } elseif ($action === 'update') {
        // Mettre à jour un article
        $id = $_POST['id'];
        $nom_article = $_POST['nom_article'];
        $quantite = $_POST['quantite'];
        $prix = $_POST['prix'];
        $fournisseur = $_POST['fournisseur'];

        if (!empty($id) && !empty($nom_article) && $quantite > 0 && $prix > 0) {
            $stmt = $pdo->prepare("UPDATE stock SET nom_article = ?, quantite = ?, prix = ?, fournisseur = ? WHERE id = ?");
            $stmt->execute([$nom_article, $quantite, $prix, $fournisseur, $id]);
            $message = "Article mis à jour avec succès.";
        } else {
            $message = "Veuillez remplir correctement tous les champs.";
        }
    }
}

// Récupération des articles
$articles = $pdo->query("SELECT * FROM stock")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url( home.jpg);
           
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background:rgba(30, 30, 30, 0.73); /* Fond de la carte */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0);
           color: white; 
        }

        h1 {
            text-align: center;
            color: #007bb5;
        }
        h2{
            color: #007bb5;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007bb5;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #005f8c;
        }
        .message {
            margin: 15px 0;
            padding: 10px;
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            border-radius: 5px;
        }
    </style>
</head>
<body style=" background-image: url( sarah/home.jpg)  ">
    <div class="container">
        <h1>Gestion de Stock</h1>

        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <h2>Liste des Articles</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                    <th>Fournisseur</th>
                    <th>Date d'Ajout</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($article['id']); ?></td>
                        <td><?php echo htmlspecialchars($article['nom_article']); ?></td>
                        <td><?php echo htmlspecialchars($article['quantite']); ?></td>
                        <td><?php echo htmlspecialchars($article['prix']); ?></td>
                        <td><?php echo htmlspecialchars($article['fournisseur']); ?></td>
                        <td><?php echo htmlspecialchars($article['date_ajout']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Ajouter un Article</h2>
        <form method="POST" action="?action=add">
            <div class="form-group">
                <label for="nom_article">Nom de l'article :</label>
                <input type="text" name="nom_article" id="nom_article" required>
            </div>
            <div class="form-group">
                <label for="quantite">Quantité :</label>
                <input type="number" name="quantite" id="quantite" required>
            </div>
            <div class="form-group">
                <label for="prix">Prix :</label>
                <input type="number" step="0.01" name="prix" id="prix" required>
            </div>
            <div class="form-group">
                <label for="fournisseur">Fournisseur :</label>
                <input type="text" name="fournisseur" id="fournisseur">
            </div>
            <button type="submit">Ajouter</button>
        </form>
    </div>
</body>
</html>
