var buttonLoader = document.querySelectorAll('.button--loader');
buttonLoader.forEach(function(i){
  i.addEventListener('click', function(el){
    i.classList.toggle("button--loading");
    i.disabled = true;
  });
});