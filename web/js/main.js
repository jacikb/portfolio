/**
 * Created by Jacik on 2018-10-13.
 */
$( document ).ready(function() {
    $(".show").fadeIn(800);
    $(".logo-symf").animate({right: "30px"}, 800);

    Animate(0);

   function Animate(index)
   {
       if(index < articles.length) {
           $("#"+articles[index]).animate({top: "0px", opacity: "0.6"}, 400).promise().done(
               function(){
                   $("#"+articles[index]).animate({top: "0px", opacity: "1"}, 500);
                   Animate(++index);
//                   $("#"+articles[index]).attr({opacity: "1"});


               }
           );
       }
   }


});