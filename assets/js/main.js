document.addEventListener('DOMContentLoaded',function(){
  const header=document.getElementById('site-header-main');
  const menu=document.querySelector('.menu-toggle');
  const search=document.querySelector('.search-toggle');
  const panel=document.getElementById('search-panel');
  if(menu){menu.addEventListener('click',function(){const open=header.classList.toggle('is-open');menu.setAttribute('aria-expanded',String(open));});}
  if(search&&panel){search.addEventListener('click',function(){const open=panel.classList.toggle('is-open');search.setAttribute('aria-expanded',String(open));if(open){panel.querySelector('input')?.focus();}});}
});
