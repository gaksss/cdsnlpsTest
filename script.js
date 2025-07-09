document.addEventListener("DOMContentLoaded", () => {
  // 🔍 Auto-complétion
  const searchInput = document.querySelector(".search-input");
  const suggestions = document.querySelector(".suggestions");

  searchInput.addEventListener("input", async () => {
    const query = searchInput.value.trim();

    if (query.length < 2) {
      suggestions.classList.remove("show");
      suggestions.innerHTML = "";
      return;
    }

    try {
      const response = await fetch(
        `autocomplete.php?search=${encodeURIComponent(query)}`
      );
      const results = await response.json();

      if (results.length > 0) {
        suggestions.innerHTML = results
          .map(
            (result) =>
              `<li class="suggestion-item"
         data-query="${result.title}">${result.artist} - ${result.title} (${result.label})</li>`
          )
          .join("");
      } else {
        suggestions.innerHTML =
          '<li class="suggestion-item">Aucun résultat</li>';
      }

      suggestions.classList.add("show");

      // Écouteurs de clic
      document.querySelectorAll(".suggestion-item").forEach((item) => {
        item.addEventListener("click", (e) => {
          const query = e.target.dataset.query;
          window.location.href = `index.php?search=${encodeURIComponent(
            query
          )}`;
        });
      });
    } catch (error) {
      console.error("Erreur lors de la recherche :", error);
    }
  });

  // 🔘 Fermer les suggestions si clic hors du champ
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".search-input-wrapper")) {
      suggestions.classList.remove("show");
    }
  });

  // 🧱 Vue grille / liste
  const viewButtons = document.querySelectorAll(".view-btn");
  const resultsContainer = document.getElementById("results-container");

  if (viewButtons.length && resultsContainer) {
    viewButtons.forEach((button) => {
      button.addEventListener("click", () => {
        const view = button.getAttribute("data-view");

        viewButtons.forEach((btn) => btn.classList.remove("active"));
        button.classList.add("active");

        if (view === "list") {
          resultsContainer.classList.remove("results-grid");
          resultsContainer.classList.add("results-list", "active");
        } else {
          resultsContainer.classList.remove("results-list", "active");
          resultsContainer.classList.add("results-grid");
        }
      });
    });
  }

  // ⌨️ Navigation clavier dans les cartes
  document.addEventListener("keydown", function (e) {
    const cards = document.querySelectorAll(".record-card");
    const currentIndex = Array.from(cards).findIndex(
      (card) => card === document.activeElement
    );

    if (e.key === "ArrowDown" && currentIndex < cards.length - 1) {
      e.preventDefault();
      cards[currentIndex + 1].focus();
    } else if (e.key === "ArrowUp" && currentIndex > 0) {
      e.preventDefault();
      cards[currentIndex - 1].focus();
    }
  });

  // 🔗 Scroll fluide sur pagination
  document.querySelectorAll(".pagination a").forEach((link) => {
    link.addEventListener("click", function (e) {
      if (!this.classList.contains("disabled")) {
        const section = document.querySelector(".results-section");
        if (section) {
          section.scrollIntoView({ behavior: "smooth" });
        }
      }
    });
  });
});
