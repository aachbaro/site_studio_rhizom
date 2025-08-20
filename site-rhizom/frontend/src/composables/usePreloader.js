// src/composables/usePreloader.js
export function usePreloader() {
  const controller = new AbortController();

  function preloadImage(url) {
    return new Promise((resolve, reject) => {
      const img = new Image();
      img.onload = () => resolve();
      img.onerror = reject;
      img.src = url;
    });
  }

  async function start(assets) {
    const tasks = (assets || []).map((a) => {
      if (a.type === "image") return preloadImage(a.url);
      if (a.type === "json")
        return fetch(a.url, { signal: controller.signal }).then(() => {});
      if (a.type === "font" && document.fonts?.load)
        return document.fonts.load(a.url);
      return Promise.resolve();
    });
    await Promise.allSettled(tasks); // on n'attend pas 100% de réussite
  }

  function cancel() {
    controller.abort();
  }

  return { start, cancel };
}
