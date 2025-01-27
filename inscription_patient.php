<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $date_naissance = $_POST['date_naissance'];
    $adresse = $_POST['adresse'];
    $sexe = $_POST['sexe'];

    // Insérer les données dans la base
    $sql = "INSERT INTO patients (prenom, nom, email, telephone, date_naissance, adresse, sexe)
            VALUES ('$prenom', '$nom', '$email', '$telephone', '$date_naissance', '$adresse', '$sexe')";

    if ($conn->query($sql) === TRUE) {
        echo "Inscription réussie !";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription Patient</title>
    <style>
        body {
    color: beige;
    font-family: Arial, sans-serif;
    background-image:url(home.jpg) ;
    padding: 20px;
}

.container {
    max-width: 400px;
    margin: auto;
    background: #1d1d1d;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px black;
}

h2 {
    color:#007bff ;
    text-align: center;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
}

.form-group input {
    width: 100%;
    padding: 8px;
    box-sizing: border-box;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #14476B;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #14476B;
}
.but{
 color: #007bff;
}
    </style>
</head>
<body>
<div class="container">
    <h2>Inscription Patient</h2>
    <form method="POST" action="inscription_patient.php">
    <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" required placeholder="entrer votre nom">
            </div>
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" name="prenom" id="prenom" required placeholder="entrer votre prénom">
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" required placeholder="entrer votre email">
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" required placeholder="..........">
           </div>
            
            
        <div class="form-group">
        <label for="telephone">Téléphone:</label><br>
        <input type="tel" id="telephone" name="telephone" required><br><br>
         </div >
         <div class="form-group">
        <label for="date_naissance">Date de naissance:</label><br>
        <input type="date" id="date_naissance" name="date_naissance" required><br><br>
</div>
        <div class="form-group">
        <label for="adresse">Adresse:</label><br>
        <textarea id="adresse" name="adresse" required></textarea>  
    </div>
    
        <br><br>
        <div class="from-group">
        <label for="sexe">Sexe:</label><br>
        <select id="sexe" name="sexe">
            <option value="masculin">Masculin</option>
            <option value="féminin">Féminin</option>
            <option value="autre">Autre</option>
        </select>
    </div>  <br><br>
    <div class="form-group">
        <button type="submit">S'inscrire</button></div>
    </form>
    </div>
</body>
</html>
