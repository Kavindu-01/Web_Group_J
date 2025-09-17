// circle-cursor.js
document.addEventListener("DOMContentLoaded", () => {
  // Hide cursor everywhere
  const hideCursor = () => {
    document.querySelectorAll("*").forEach(el => {
      el.style.cursor = "none";
    });
  };
  hideCursor();

  // Create highlight circle
  const circle = document.createElement("div");
  circle.style.position = "fixed";
  circle.style.top = "0";
  circle.style.left = "0";
  circle.style.width = "30px";
  circle.style.height = "30px";
  circle.style.border = "2px solid #00bfff"; // bright blue
  circle.style.borderRadius = "50%";
  circle.style.boxShadow = "0 0 15px rgba(0,191,255,0.7)";
  circle.style.pointerEvents = "none";
  circle.style.transform = "translate(-50%, -50%)";
  circle.style.zIndex = "9999";
  document.body.appendChild(circle);

  // Follow the mouse instantly
  document.addEventListener("mousemove", (e) => {
    circle.style.top = e.clientY + "px";
    circle.style.left = e.clientX + "px";
  });

  // Re-apply hide on new elements dynamically (in case buttons/links are added later)
  const observer = new MutationObserver(hideCursor);
  observer.observe(document.body, { childList: true, subtree: true });
});
