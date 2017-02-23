<?php
/**
 * Unit Template Name: Course Unit Flip Cards
 *
 * Be sure to use the "Unit Template Name:" in the header.
 * To display the course unit content, be sure to inclue the loop.
 */
?>
<?php get_header(); ?>

<style>
.flip {
  -webkit-perspective: 600;
perspective: 600;

 position: relative;
 text-align: center;
}
.flip .card.flipped {
  -webkit-transform: rotatey(-180deg);
    transform: rotatey(-180deg);
}
.flip .card {

  height: 100%;
  -webkit-transform-style: preserve-3d;
  -webkit-transition: 0.5s;
    transform-style: preserve-3d;
    transition: 0.5s;
}
.flip .card .face {

  -webkit-backface-visibility: hidden ;
    backface-visibility: hidden ;
  z-index: 2;
   
}
.flip .card .front {
  position: absolute;
   width: 100%;
  z-index: 1;

}
.flip .card .back {
  -webkit-transform: rotatey(-180deg);
    transform: rotatey(-180deg);
}
  .inner{margin:0px !important;}
</style>
<?php echo the_title();?>


<div class="flip">
                <div class="card">
                     <div class="face front">
                       <p>front</p>
                    </div>
                    <div class="face back">
                         <p>back</p>
                    </div>
                </div>
            </div>
    
       
            <div class="flip">
                <div class="card">
                     <div class="face front">
                       <p>front</p>
                    </div>
                    <div class="face back">
                         <p>back</p>
                    </div>
                </div>
            </div>
        



<?php get_sidebar(); ?>

<?php get_footer(); ?>