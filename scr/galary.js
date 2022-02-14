$(document).ready(function () {
  
  $(".slider").slick({
    
    infinite: true,
       
 slidesToShow: 1,
        
slidesToScroll: 1,

 dots: true,
    
    responsive: [
{

breakpoint: 480,
 
settings: {
 slidesToShow: 2,
 slidesToScroll: 2
 }
}]

});

});
