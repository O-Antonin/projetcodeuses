$(document).ready(function(){

  $(".category-list li:nth-child(1)").each(function(){
      var colors = 'rgb('+
                   (Math.floor((180-0)*Math.random())) + ',' +
                   (Math.floor((180-0)*Math.random())) + ','
                   (Math.floor((180-0)*Math.random())) + ','
      $(this).css("background-color");

  });
  console.log( "ready!" );
});