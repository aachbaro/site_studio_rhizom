export function assetsToPreload() {
  return [
    // Images visibles sur la home immédiatement
    { type: 'image', url: '/static/home/hero1.webp' },
    { type: 'image', url: '/static/home/hero2.webp' },
    // Données initiales
    { type: 'json',  url: '/api/carousel.php' },
    // Ajoute ici les premières images de projets si tu les connais
  ]
}