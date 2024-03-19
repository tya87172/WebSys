<!DOCTYPE html>
<html lang="en">
    <?php
        include "inc/nav.inc.php";
        include "inc/header.inc.php";
        include "inc/head.inc.php";
        include "user_query.php";
    ?>
    
    
    

<body>
    <main class="container">
    <section id="dogs">
    <h2>All About Dogs! Welcome <?php echo $_SESSION['user_id']; ?></h2>
    <div class="row">
    <article class="col-sm">
    <h3>Poodles</h3>
    <figure>
        
        
        <img class="img-poodle" src="images/poodle_small.jpg" alt="Poodle"
        title="View larger image..."/>
        
        <figcaption>Standard Poodle</figcaption>
        
        </figure>
    <p>
    Poodles are a group of formal dog breeds, the Standard
    Poodle, Miniature Poodle and Toy Poodle...
    </p>
    </article>
    <article class="col-sm">
    <h3>Chihuahua</h3>
    <figure>
        
        <img class="img-chihuahua thumbnail" src="images/chihuahua_small.jpg" alt="Chihuahua"
        title="View larger image..."/>
        
        <figcaption>Standard Chihuahua</figcaption>
        </figure>
    <p>
    The Chihuahua is the smallest breed of dog, and is named
    after the Mexican state of Chihuahua...
    </p>
    </article>
    </div>
    </section>
    <section id="cats">
        <h2>All About Cats!</h2>
        <div class="row">
        <article class="col-sm">
        <h3>Tabby</h3>
        <figure>
            
            <img class="img-tabby thumbnail" src="images/tabby_small.jpg" alt="Tabby"
            title="View larger image..."/>
            
            <figcaption>Standard Tabby</figcaption>
            </figure>
        <p>
        <p>A tabby cat, or simply tabby, is any domestic cat with a distinctive M-shaped marking on its forehead; stripes by its eyes and across its cheeks, along its back, and around its legs and tail;
        </p>
        </article>
        <article class="col-sm">
        <h3>Calico</h3>
        <figure>
            
            <img class="img-calico thumbnail" src="images/calico_small.jpg" alt="Calico"
            title="View larger image..."/>
            
            <figcaption>Standard Calico</figcaption>
            </figure>
        <p>
            A calico cat is a domestic cat of any breed with a tri-color coat. 
        </p>
        </article>
        </div>
        </section>
        <span id="popupContainer"></span>
        
    </main>
    </body>
    <?php
        include "inc/footer.inc.php";
    ?>
    
</html>