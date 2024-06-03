<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Mur</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <header>
            <img src="resoc.jpg" alt="Logo de notre réseau social"/>
            <?php include("menu.php"); ?>
        </header>
        <div id="wrapper">
            <?php
            /**
             * Etape 1: Le mur concerne un utilisateur en particulier
             * La première étape est donc de trouver quel est l'id de l'utilisateur
             * Celui ci est indiqué en parametre GET de la page sous la forme user_id=...
             * Documentation : https://www.php.net/manual/fr/reserved.variables.get.php
             * ... mais en résumé c'est une manière de passer des informations à la page en ajoutant des choses dans l'url
             * FAIT
             */
            $userId =intval($_GET['user_id']);
            ?>
            <?php
            /**
             * Etape 2: se connecter à la base de donnée
             * FAIT
             */
            include("connect.php");
            ?>

            <aside>
                <?php
                /**
                 * Etape 3: récupérer le nom de l'utilisateur
                 * FAIT
                 */                
                $laQuestionEnSql = "SELECT * FROM users WHERE id= '$userId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $user = $lesInformations->fetch_assoc();
                //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par l'alias et effacer la ligne ci-dessous
                //echo "<pre>" . print_r($user, 1) . "</pre>";
                //FAIT
                ?>
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez tous les message de l'utilisatrice : <?php echo $user['alias']; ?>
                        (n° <?php echo $userId ?>)
                    </p>
                </section>
                <section>
                    <h3>Messages</h3>
                    <?php
                    $listAuteurs = [];
                    $laQuestionEnSql = "SELECT * FROM users";
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                    while ($user = $lesInformations->fetch_assoc())
                    {
                        $listAuteurs[$user['id']] = $user['alias'];
                    }

                    $listTags = [];
                    $laQuestionEnSql2 = "SELECT * FROM tags";
                    $lesInformations2 = $mysqli->query($laQuestionEnSql2);
                    while ($tags = $lesInformations2->fetch_assoc())
                    {
                        $listTags[$tags['id']] = $tags['label'];
                    }
                    /**
                     * TRAITEMENT DU FORMULAIRE
                     */
                    // Etape 1 : vérifier si on est en train d'afficher ou de traiter le formulaire
                    // si on recoit un champs email rempli il y a une chance que ce soit un traitement
                    $enCoursDeTraitement = isset($_POST['auteur']);
                    if ($enCoursDeTraitement)
                    {
                        // on ne fait ce qui suit que si un formulaire a été soumis.
                        // Etape 2: récupérer ce qu'il y a dans le formulaire @todo: c'est là que votre travaille se situe
                        // observez le résultat de cette ligne de débug (vous l'effacerez ensuite)
                        //echo "<pre>" . print_r($_POST, 1) . "</pre>";
                        // et complétez le code ci dessous en remplaçant les ??? OK
                        $authorId = $_POST['auteur'];
                        $postContent = $_POST['message'];

                        //Etape 3 : Petite sécurité
                        // pour éviter les injection sql : https://www.w3schools.com/sql/sql_injection.asp
                        $authorId = intval($mysqli->real_escape_string($authorId));
                        $postContent = $mysqli->real_escape_string($postContent);
                        $tagsId = intval($mysqli->real_escape_string($_POST['tag']));

                        $postContent .= "#$listTags[$tagsId]";

                        //Etape 4 : construction de la requete
                        $lInstructionSql = "INSERT INTO posts "
                                . "(id, user_id, content, created) "
                                . "VALUES (NULL, "
                                . $authorId . ", "
                                . "'" . $postContent . "', "
                                . "NOW())"
                                ;
                        //echo $lInstructionSql;
                        // Etape 5 : execution
                        $ok = $mysqli->query($lInstructionSql);
                        if ( ! $ok)
                        {
                            echo "Impossible d'ajouter le message : " . $mysqli->error;
                        } else
                        {
                            echo "Message posté en tant que : " . $listAuteurs[$authorId];
                        }
                    }
                    ?>                     
                    <form action="" method="post">
                        <input type='hidden' name='???' value='achanger'>
                        <dl>
                            <dt><label for='auteur'>Auteur</label></dt>
                            <dd><select name='auteur'>
                                    <?php
                                    foreach ($listAuteurs as $id => $alias)
                                        echo "<option value='$id'>$alias</option>";
                                    ?>
                                </select></dd>
                            <dt><label for='message'>Message</label></dt>
                            <dd><textarea name='message'></textarea></dd>
                            <dt><label for='tag'>Mot(s) clé(s)</label></dt>
                            <dd><select name='tag' id='tag'>
                                <option value="">--Sélectionne ton mot clé--</option>
                                
                                    <?php foreach ($listTags as $id => $label)
                                        echo "<option value='$id'>#$label</option>";
                                    ?>
                            </select></dd>

                        </dl>
                        <input type='submit'>
                    </form>         
                </article>
                </section>
            </aside>
            <main>
                <?php
                /**
                 * Etape 3: récupérer tous les messages de l'utilisatrice
                 * FAIT
                 */
                $laQuestionEnSql = "
                    SELECT posts.content, posts.created, users.alias AS author_name, 
                    COUNT(likes.id) AS like_number, GROUP_CONCAT(DISTINCT tags.label) AS taglist,
                    GROUP_CONCAT(DISTINCT tags.id ORDER BY tags.label) AS taglistid -- ajout id tag
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    WHERE posts.user_id='$userId' 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : " . $mysqli->error);
                }

                /**
                 * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
                 * FAIT
                 */
                while ($post = $lesInformations->fetch_assoc())
                {
                    $explode = explode(",", $post['taglist']);
                    $explodeid = explode(",", $post['taglistid']);

                    //echo "<pre>" . print_r($post, 1) . "</pre>";
                    ?>                
                    <article>
                        <h3>
                            <time><?php echo $post['created']; ?></time>
                        </h3>
                        <address>par <?php echo $post['author_name']; ?></address>
                        <div>
                            <p><?php echo $post['content']; ?></p>
                        </div>                                            
                        <footer>
                            <small>♥ <?php echo $post['like_number']; ?></small>
                            <?php if (!empty($post['taglist']) && !empty($post['taglistid'])) { ?>
                                <?php if (count($explodeid) > 1):
                                    for ($i = 0; $i < count($explodeid); $i++) { ?>
                                    <a href="tags.php?tag_id=<?php echo $explodeid[$i]; ?>">#<?php echo $explode[$i]; ?></a>
                                <?php ;} ?>
                                <?php else: ?>
                                    <a href="tags.php?tag_id=<?php echo $explodeid[0]; ?>">#<?php echo $explode[0]; ?></a>
                                <?php endif ?>
                            <?php } else { ?>
                                <p></p>
                            <?php } ?>
                        </footer>
                    </article>
                <?php } ?>


            </main>
        </div>
    </body>
</html>
