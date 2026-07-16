// Lista zdjęć w galerii "Realizacje" na stronie głównej.
//
// Jak dodać nowe zdjęcie:
//   1) Wrzuć plik zdjęcia do folderu images/realizacje/
//   2) Dodaj poniżej nowy wiersz z nazwą pliku, opisem i podpisem
//
// Jak podmienić zdjęcie:
//   - Podmień plik w images/realizacje/ (zachowując tę samą nazwę) albo
//   - Zmień pole "file" na nazwę nowego pliku
//
// Pole "big": true ustawia zdjęcie jako duży, wyróżniony kafelek (tylko jedno na listę ma sens).

const REALIZACJE = [
  {
    file: "domek-w-gorach.jpg",
    alt: "Domek letniskowy w górach",
    cap: "Domek w górach — realizacja klienta",
    big: true
  },
  {
    file: "model-parterowy.jpg",
    alt: "Domek parterowy",
    cap: "Model parterowy"
  },
  {
    file: "poddasze-uzytkowe.jpg",
    alt: "Domek z poddaszem użytkowym",
    cap: "Z użytkowym poddaszem"
  },
  {
    file: "domek-letniskowy.jpg",
    alt: "Domek letniskowy",
    cap: "Domek letniskowy"
  }
];
