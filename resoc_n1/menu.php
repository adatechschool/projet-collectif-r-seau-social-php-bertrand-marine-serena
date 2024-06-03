<?php session_start();
?>
<nav id="menu">
    <a href="news.php">Actualités</a>

    <?php if (isset($_SESSION['connected_id']) && $_SESSION['connected_id'] !== null) : ?>
        <a href="wall.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Mur</a>
    <?php else: ?>
        <a href="#" onclick="alert('Veuillez vous connecter pour avoir accès au mur');">Mur</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['connected_id']) && $_SESSION['connected_id'] !== null) : ?>
        <a href="feed.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Flux</a>
    <?php else: ?>
        <a href="#" onclick="alert('Veuillez vous connecter pour avoir accès au flux');">Flux</a>
    <?php endif; ?>
    
    <a href="tags.php?tag_id=1">Tags</a>
</nav>
<nav id="user">
    <?php if( isset($_SESSION['connected_id']) && $_SESSION['connected_id'] !== null ) : ?>
            <a href="./logout.php">Déconnexion</a>
        <?php else: ?>
                <a href="./login.php">Connexion</a>
        <?php endif; ?> 
</nav>
<?php if (isset($_SESSION['connected_id']) && $_SESSION['connected_id'] !== null) : ?>
<nav id="user">
    <a href="#">Profil</a>
        <ul>
            <li><a href="settings.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Paramètres</a></li>
            <li><a href="followers.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Mes suiveurs</a></li>
            <li><a href="subscriptions.php?user_id=<?php echo $_SESSION['connected_id']; ?>">Mes abonnements</a></li>
        </ul>
</nav>
<?php endif; ?>