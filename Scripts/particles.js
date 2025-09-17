// particleBackground.js
(function() {
  // ===== Create background canvas =====
  const canvas = document.createElement("canvas");
  canvas.style.position = "fixed";
  canvas.style.top = 0;
  canvas.style.left = 0;
  canvas.style.width = "100%";
  canvas.style.height = "100%";
  canvas.style.pointerEvents = "none"; // allow clicking through
  canvas.style.zIndex = -1; // behind all content
  document.body.appendChild(canvas);
  
  const ctx = canvas.getContext("2d");
  let width = canvas.width = window.innerWidth;
  let height = canvas.height = window.innerHeight;

  // ===== Particles =====
  const particles = [];
  const particleCount = 80;

  function random(min, max) {
    return Math.random() * (max - min) + min;
  }

  for(let i=0;i<particleCount;i++){
    particles.push({
      x: random(0, width),
      y: random(0, height),
      vx: random(-0.3,0.3),
      vy: random(-0.3,0.3),
      size: random(1,3),
      color: `hsla(${Math.random()*360}, 80%, 70%, 0.6)`
    });
  }

  function drawParticles() {
    ctx.clearRect(0,0,width,height);
    particles.forEach(p => {
      p.x += p.vx;
      p.y += p.vy;

      if(p.x < 0 || p.x > width) p.vx *= -1;
      if(p.y < 0 || p.y > height) p.vy *= -1;

      ctx.beginPath();
      ctx.arc(p.x, p.y, p.size, 0, Math.PI*2);
      ctx.fillStyle = p.color;
      ctx.fill();
    });

    // Optional: connect close particles
    for(let i=0;i<particles.length;i++){
      for(let j=i+1;j<particles.length;j++){
        const dx = particles[i].x - particles[j].x;
        const dy = particles[i].y - particles[j].y;
        const dist = Math.sqrt(dx*dx + dy*dy);
        if(dist < 80){
          ctx.strokeStyle = `rgba(255,255,255,0.08)`;
          ctx.beginPath();
          ctx.moveTo(particles[i].x, particles[i].y);
          ctx.lineTo(particles[j].x, particles[j].y);
          ctx.stroke();
        }
      }
    }
  }

  // ===== Animate =====
  function animate() {
    drawParticles();
    requestAnimationFrame(animate);
  }

  animate();

  // ===== Resize =====
  window.addEventListener("resize", () => {
    width = canvas.width = window.innerWidth;
    height = canvas.height = window.innerHeight;
  });
})();
