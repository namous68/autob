document.addEventListener("DOMContentLoaded", function() {
	const slider = document.querySelector('.slider');
	let counter = 0;
  
	setInterval(() => {
	  counter++;
	  if (counter === 4) {
		counter = 0;
	  }
  
	  const transformValue = -counter * 100;
	  slider.style.transform = `translateX(${transformValue}%)`;
	}, 3000);
  });
  