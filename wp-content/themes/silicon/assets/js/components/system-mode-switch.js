( function() {  

  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {

    let modeSwitch = document.querySelector('[data-bs-toggle="mode"]');
    let checkbox = modeSwitch.querySelector('.form-check-input');

    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) { 
      checkbox.checked = true;
      root.classList.add('dark-mode');
      window.localStorage.setItem('mode', 'dark');
    } else { 
      checkbox.checked = false;
      root.classList.remove('dark-mode'); 
      window.localStorage.setItem('mode', 'light');
    } 
  });

} )();