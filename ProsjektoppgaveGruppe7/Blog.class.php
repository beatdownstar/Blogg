<?php


class Blog implements BlogInterface

{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function showAllposts(): array
    {
        $posts = array();

        try {

            $stmt = $this->db->prepare("SELECT p.*, cat.categoryname FROM `posts` as p inner join Category as cat WHERE p.id_category=cat.id_category order by  p.pub_date desc limit 4  ");
            $stmt->execute();

            while ($post = $stmt->fetchObject('Posts')) {

                $posts[] = $post;
            }

        } catch (Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $posts;
    }

    public function showpostsofblogger(int $id_blog): array
    {

        $posts = array();

        try {
            $stmt = $this->db->prepare("SELECT * FROM posts WHERE id_blog=:id_blog");
            $stmt->bindParam(':id_blog', $id_blog, PDO::PARAM_INT);
            $stmt->execute();

            while ($post = $stmt->fetchObject("Posts")) {

                $posts[] = $post;
            }

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $posts;
    }


    public function showPost(int $id): Posts
    {

        $post = array();

        try {
            $stmt = $this->db->prepare("SELECT p.*, ub.username FROM posts as p inner join usernameblog as ub WHERE p.id_post=:id AND p.id_blog=ub.id_blog");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $post = $stmt->fetchObject("Posts");

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $post;
    }

    public function topFiveposts(): array
    {
        $topfiveposts = array();

        try {

            $stmt = $this->db->prepare("SELECT b.*, ub.username FROM `blogger` as b inner join usernameblog as ub WHERE b.id_blog=ub.id_blog");
            $stmt->execute();

            while ($posts = $stmt->fetchObject("Posts")) {

                $topfiveposts[] = $posts;
            }

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $topfiveposts;
    }

    public function showPostsInCategory(int $id_category): array
    {
        $postsincat = array();

        try {

            $stmt = $this->db->prepare("SELECT p.*, c.categoryname FROM `posts` as p inner join Category as c WHERE p.id_category=:id_category and c.id_category=p.id_category");
            $stmt->bindParam(':id_category', $id_category, PDO::PARAM_INT);
            $stmt->execute();

            while ($post = $stmt->fetchObject("Posts")) {

                $postsincat[] = $post;
            }

        } catch (Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $postsincat;
    }




    public function lastFiveblogs(): array

    {
        $lastfiveblogs = array();

        try {

            $stmt = $this->db->prepare("SELECT u.username,u.id_user, b.id_blog, b.blogname, bc.blogcategoryname, b.aboutblogger, b.photo FROM `blog` as b, `blogCategory` as bc inner join user as u Where b.id_blogcategory=bc.id_blogcategory and b.id_user=u.id_user");
            $stmt->execute();

            while ($blogs = $stmt->fetch()) {

                $lastfiveblogs[] = $blogs;
            }

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $lastfiveblogs;
    }

    public function showaboutblog(int $id_blog): array
    {

        $showaboutblog = array();

        try {
            $stmt = $this->db->prepare("SELECT u.username,u.id_user, b.id_blog, b.blogname, b.id_blogcategory, b.aboutblogger, b.photo FROM `blog` as b inner join user as u  WHERE b.id_user=u.id_user and b.id_blog=:idblog");
            $stmt->bindParam(':idblog', $id_blog, PDO::PARAM_INT);
            $stmt->execute();

            $showaboutblog = $stmt->fetch();

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $showaboutblog;
    }




    public function arkhiv(): array
    {

        $arkhivpaadate = array();

        try {
            $stmt = $this->db->prepare("SELECT DATE_FORMAT(pub_date, '%M') as month, DATE_FORMAT(pub_date, '%Y') as year, count(*) as antall FROM `posts` GROUP BY DATE_FORMAT(pub_date, '%m') DESC ");
            $stmt->execute();
            $arkhivpaadate = $stmt->fetchAll();

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $arkhivpaadate;
    }

    public function arkhivValg(string $datoen): array
    {

        $paadatoen = array();

        try {
            $stmt = $this->db->prepare("SELECT * FROM `posts` WHERE DATE_FORMAT(pub_date, '%M.%Y')=:datoen ORDER BY pub_date DESC");
            $stmt->bindParam(':datoen', $datoen, PDO::PARAM_STR);
            $stmt->execute();

            while ($posts = $stmt->fetchObject("Posts")) {

                $paadatoen[] = $posts;

            }

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $paadatoen;
    }




    public function lastFiveComments(): array
    {

        $lastfivecomments = array();

        try {

            $stmt = $this->db->prepare("SELECT c.*, u.username, p.tittle as posttittle FROM `comments` as c, 
user as u inner join posts as p WHERE u.id_user = c.id_user and c.id_post=p.id_post GROUP by c.pub_date desc limit 5 ");
            $stmt->execute();

            while ($comments = $stmt->fetchObject("Comments")) {

                $lastfivecomments[] = $comments;

            }

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $lastfivecomments;
    }

    public function PostsComments(int $id_post): array
    {
        $postcomments = array();

        try {

            $stmt = $this->db->prepare("SELECT c.*, u.username FROM `comments` as c inner join user as u WHERE id_post =:id_post and c.id_user=u.id_user");
            $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
            $stmt->execute();

            while ($comments = $stmt->fetchObject("Comments")) {

                $postcomments[] = $comments;

            }

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $postcomments;
    }




    public function registrerNyBruker(User $user): bool
    {

        try {
            $stmt = $this->db->prepare("INSERT INTO `user` (`id_user`, `username`, `password`, `email`, `id_ver`, `accept`)
					VALUES (NULL, :username, :password, :email, :id_ver, :accept)");
            $username = $user->getUsername();
            $password = $user->getPassword();
            $email = $user->getEmail();
            $id_ver = $user->getIdVer();
            $accept = $user->getAccept();

            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':id_ver', $id_ver, PDO::PARAM_STR);
            $stmt->bindParam(':accept', $accept, PDO::PARAM_INT);
            $stmt->execute();

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return true;
    }

    public function acceptere(string $id_ver)
    {

        try {

            $stmt = $this->db->prepare("SELECT id_user FROM `user` WHERE id_ver =:id_ver ");
            $stmt->bindParam(':id_ver', $id_ver, PDO::PARAM_STR);
            $stmt->execute();

            $accept = $stmt->fetch();

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $accept;
    }

    public function newAccept(string $id_ver)
    {

        try {

            $stmt = $this->db->prepare("UPDATE `user` SET accept=1 WHERE id_ver=:id_ver");
            $stmt->bindParam(':id_ver', $id_ver, PDO::PARAM_STR);
            $stmt->execute();

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return true;
    }

    public function userExists(string $username): bool
    {

        try {

            $stmt = $this->db->prepare("SELECT count(*) FROM `user` WHERE `username`=:username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            $user_exists = $stmt->fetch();

            if ($user_exists['count(*)'] == 0)
                return false;
            else
                return true;

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return false;
    }

    public function emailExists(string $email): bool
    {

        try {

            $stmt = $this->db->prepare("SELECT count(*) FROM `user` WHERE `email`=:email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $email_exists = $stmt->fetch();

            if ($email_exists['count(*)'] == 0)
                return false;
            else
                return true;

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return false;
    }

    public function Blogowner(string $username): bool
    {

        try {

            $stmt = $this->db->prepare("SELECT count(*) FROM `user` as u inner join `blog` as b WHERE `username`=:username and u.id_user=b.id_user");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            $blogowner = $stmt->fetch();

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }

        if ($blogowner['count(*)'] == 0) {
            return false;

        } else {
            return true;
        }
    }

    public function login(string $username, string $password, string $blogowner): bool
    {

        try {

            if ($blogowner) {
                $stmt = $this->db->prepare("SELECT u.id_user, u.username, u.password, u.email, u.accept, b.id_blog, b.blogname FROM user as u inner join blog as b Where `username`=:username and u.id_user=b.id_user");
            } else {
                $stmt = $this->db->prepare("SELECT * FROM `user` WHERE `username`=:username");
            }
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetchObject("User");

            $pass = $user->getPassword();
            $accept = $user->getAccept();

            if ($accept == 1) {

                if (password_verify($password, $pass)) {
                    session_regenerate_id();

                    setcookie('PHPSESSID', session_id(), time() + (3600 * 24 * 7));

                    $_SESSION['name'] = $user->getUsername();
                    $_SESSION['innlogget'] = true;
                    $_SESSION['blogowner'] = false;
                    $_SESSION['id_user'] = $user->getId();
                    $_SESSION['browser'] = $_SERVER['HTTP_USER_AGENT'];
                    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                    $_SESSION['loginsuccess'] = 0;

                    if ($blogowner) {

                        session_regenerate_id();

                        setcookie('PHPSESSID', session_id(), time() + (3600 * 24 * 7));

                        $_SESSION['id_blog'] = $user->getBlogid();
                        $_SESSION['blogname'] = $user->getBlogname();
                        $_SESSION['blogowner'] = true;
                        $_SESSION['browser'] = $_SERVER['HTTP_USER_AGENT'];
                        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                        $_SESSION['loginsuccess'] = 0;
                    }

                } else {

                    $_SESSION['loginsuccess'] = 1;

                    return false;
                }
            } else {

                $_SESSION['loginsuccess'] = 3;

            }
        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return true;
    }

    public function logout()
    {

        $_SESSION = array();

        if (ini_get("session.use_cookies")) {

            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }




    public function creatingBlog(objBlog $newblog): bool
    {

        try {

            $stmt = $this->db->prepare("INSERT INTO `blog` (`id_blog`, `id_user`, `blogname`, `id_blogcategory`, `aboutblogger`, `photo`)
					VALUES (NULL, :id_user, :blogname, :id_blogcategory, :aboutblogger, NULL)");
            $id_user = $newblog->getIdUser();
            $blogname = $newblog->getBlogName();
            $aboutblogger = $newblog->getAboutBlogger();
            $id_blogcategory = $newblog->getBlogCategory();

            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->bindParam(':blogname', $blogname, PDO::PARAM_STR);
            $stmt->bindParam(':aboutblogger', $aboutblogger, PDO::PARAM_STR);
            $stmt->bindParam(':id_blogcategory', $id_blogcategory, PDO::PARAM_STR);
            $stmt->execute();

            $stmt1 = $this->db->prepare("SELECT id_blog, blogname FROM blog  Where id_user=:id_user");
            $stmt1->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt1->execute();

            $bloginfo = $stmt1->fetch();

            $_SESSION['id_blog'] = $bloginfo['id_blog'];
            $_SESSION['blogname'] = $bloginfo['blogname'];
            $_SESSION['blogowner'] = true;

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return true;

    }

    public function newPost(Posts $post): bool
    {

        try {

            $stmt = $this->db->prepare("INSERT INTO `posts` (`id_post`, `id_blog`, `id_category`, `tittle`, `text`, `keywords`, `pub_date`, `views`, `photo`)
					VALUES (NULL, :id_blog, :id_category, :tittle, :text, :keywords, now(), 0, :photo)");
            $id_blog = $post->getIdBlog();
            $id_category = $post->getIdCategory();
            $tittle = $post->getTittle();
            $text = $post->getText();
            $keywords = $post->getKeyword();
            $photo = $post->getPhoto();

            $stmt->bindParam(':id_blog', $id_blog, PDO::PARAM_STR);
            $stmt->bindParam(':id_category', $id_category, PDO::PARAM_STR);
            $stmt->bindParam(':tittle', $tittle, PDO::PARAM_STR);
            $stmt->bindParam(':text', $text, PDO::PARAM_STR);
            $stmt->bindParam(':keywords', $keywords, PDO::PARAM_STR);
            $stmt->bindParam(':photo', $photo, PDO::PARAM_STR);
            $stmt->execute();

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return true;
    }

    public function addNewComment(Comments $newcomment): bool
    {

        try {

            $stmt = $this->db->prepare("INSERT INTO `comments` (`id_comment`, `id_post`, `id_user`, `text`, `pub_date`, `commentcommentID`)
					VALUES (NULL, :id_post, :id_user, :text, now(), :commencommentID)");
            $text = $newcomment->getText();
            $id_user = $newcomment->getIdUser();
            $id_post = $newcomment->getIdPost();
            $commencommentID = $newcomment->getCommentCommentID();

            $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->bindParam(':text', $text, PDO::PARAM_STR);
            $stmt->bindParam(':commencommentID', $commencommentID, PDO::PARAM_INT);
            $stmt->execute();

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return true;
    }





    public function categories(): array

    {
        $categories = array();

        try {

            $stmt = $this->db->prepare("SELECT * FROM Category");
            $stmt->execute();

            while ($categorie = $stmt->fetch()) {

                $categories[] = $categorie;
            }

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $categories;

    }

    public function blogCategories(): array

    {
        $blogcategories = array();

        try {

            $stmt = $this->db->prepare("SELECT * FROM blogCategory");
            $stmt->execute();

            while ($blogcategorie = $stmt->fetch()) {

                $blogcategories[] = $blogcategorie;
            }

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $blogcategories;
    }

    public function showCategories(): array
    {

        $categories = array();

        try {

            $stmt = $this->db->prepare("SELECT * FROM `Category` ORDER BY id_category");
            $stmt->execute();

            while ($categorie = $stmt->fetchObject('Posts')) {

                $categories[] = $categorie;
            }

        } catch (Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }

        return $categories;
    }






    public function search(string $string): array
    {

        $searchresult = array();

        try {

            $stmt = $this->db->prepare("SELECT p.id_post, p.id_blog, p.tittle, p.text, p.pub_date, ub.username FROM posts as p, usernameblog as ub WHERE p.id_blog=ub.id_blog and MATCH (tittle, text, keywords) AGAINST (:string)");
            $stmt->bindParam(':string', $string, PDO::PARAM_STR);
            $stmt->execute();

            while ($results = $stmt->fetch()) {

                $searchresult[] = $results;
            }

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }

        if (count($searchresult) == 0) {

            $searchresult[0] = "tomt";

            return $searchresult;

        } else {

            return $searchresult;
        }
    }

    public function showPhoto(int $id_post)
    {

        try {

            $stmt = $this->db->prepare("SELECT photo FROM `posts` WHERE id_post =:id_post ");
            $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
            $stmt->execute();

            $photo = $stmt->fetch();

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $photo;

    }

    public function setViews(int $id_post)
    {

        try {

            $stmt = $this->db->prepare("UPDATE posts SET views=views+1 WHERE id_post=:id_post");
            $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
            $stmt->execute();

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return true;
    }

    public function tags(): array
    {

        $tags = array();

        try {
            $stmt = $this->db->prepare("SELECT keywords FROM `posts` group by keywords ");
            $stmt->execute();
            $tags = $stmt->fetchAll();

        } catch (InvalidArgumentException $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return $tags;
    }






    public function deleteBlog(): bool
    {
        $id_blog = $_SESSION['id_blog'];

        try {
            $stmt = $this->db->prepare("DELETE FROM `blog` WHERE `id_blog` = :id_blog");
            $stmt->bindParam(':id_blog', $id_blog, PDO::PARAM_INT);
            $stmt->execute();

            $_SESSION['blogname'] = NULL;
            $_SESSION['blogowner'] = false;

        } catch (Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return true;
    }

    public function deleteUser(): bool
    {
        $id_user = $_SESSION['id_user'];

        try {
            $stmt = $this->db->prepare("DELETE FROM `user` WHERE `id_user` = :id_user");
            $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();

        } catch (Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return true;
    }

    public function deleteAllpostsofUser(): bool
    {

        $id_blog = $_SESSION['id_blog'];

        try {
            $stmt = $this->db->prepare("DELETE FROM `posts` WHERE `id_blog` = :id_blog");
            $stmt->bindParam(':id_blog', $id_blog, PDO::PARAM_INT);
            $stmt->execute();

        } catch (Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return true;
    }

    public function deletePost(int $id_post): bool
    {

        $id_blog = $_SESSION['id_blog'];

        try {

            $stmt = $this->db->prepare("DELETE FROM `posts` WHERE `id_blog` = :id_blog and `id_post`=:id_post");
            $stmt->bindParam(':id_blog', $id_blog, PDO::PARAM_INT);
            $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
            $stmt->execute();

        } catch (Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }
        return true;
    }




    public function deleteComment(int $id_comment): bool
    {

        try {

            $stmt = $this->db->prepare("DELETE FROM `comments` WHERE `id_comment` = :id_comment  or `commentcommentID`=:id_comment");
            $stmt->bindParam(':id_comment', $id_comment, PDO::PARAM_INT);
            $stmt->execute();

        } catch (Exception $e) {
            print $e->getMessage() . PHP_EOL;
        }

        return true;
    }


}
