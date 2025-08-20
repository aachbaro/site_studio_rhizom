// src/services/assetsToPreload.js
export function assetsToPreload() {
  return [
    { type: "image", url: "/static/home/hero1.webp" },
    { type: "image", url: "/static/home/hero2.webp" },
    { type: "json", url: "/api/carousel.php" },
  ];
}
