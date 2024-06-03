<?php session_start();
//var_dump($_SESSION); 
?>
<nav id="menu">
    <a href="news.php">Actualités</a>
    <a href="wall.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Mur</a>
    <a href="feed.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Flux</a>
    <a href="tags.php?tag_id=1">Mots-clés</a>
</nav>
<a href="news.php">Deconnexion</a>
<nav id="user">
    <a href="#">Profil</a>
        <ul>
            <li><a href="settings.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Paramètres</a></li>
            <li><a href="followers.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Mes suiveurs</a></li>
            <li><a href="subscriptions.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Mes abonnements</a></li>
        </ul>
</nav>