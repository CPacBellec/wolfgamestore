<?php include 'header.php'; ?>
<?php include 'config.php'; ?>

<div class="container mx-auto p-4">
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

        $result = $conn->query("SELECT COUNT(*) AS total FROM utilisateurs");
        if ($result) {
            $row = $result->fetch_assoc();
            $total_utilisateurs = $row['total'];
        }
        $role = ($total_utilisateurs == 0) ? 'admin' : 'utilisateur';

        $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nom, $email, $mot_de_passe, $role);

        if ($stmt->execute()) {
            echo "<p class='text-green-500'>Inscription réussie.</p>";
        } else {
            echo "<p class='text-red-500'>Erreur lors de l'inscription: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    $conn->close();
    ?>

    <!-- Formulaire d'inscription -->
    <form class="max-w-md mx-auto bg-white p-6 rounded-md shadow-md mt-4" method="post" action="inscription.php">
        <label class="block mb-2">Nom: <input class="border p-2 w-full" type="text" name="nom" required></label>
        <label class="block mb-2">Email: <input class="border p-2 w-full" type="email" name="email" required></label>
        <label class="block mb-2">Mot de passe: <input class="border p-2 w-full" type="password" name="mot_de_passe" required></label>
        <button class="bg-blue-500 text-white p-2 rounded-md cursor-pointer" type="submit">S'inscrire</button>
    </form>
</div>

<?php include 'footer.php'; ?>