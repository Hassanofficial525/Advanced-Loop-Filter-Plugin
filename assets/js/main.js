document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('elementor-post-search');
    const sortSelect = document.getElementById('elementor-post-sort');
    const clearBtn = document.getElementById('clear-search');
    clearBtn.style.display = 'none'; // Hide clear button on initial page load
    const postsContainer = document.querySelector('.elementor-loop-container');
    const noResults = document.getElementById('elementor-no-results');
    const posts = postsContainer ? Array.from(postsContainer.children) : [];
    const originalOrder = [...posts];

    const isEnglish = !window.location.href.includes('/ar');
    searchInput.placeholder = isEnglish ? 'Search posts...' : 'ابحث';
    noResults.textContent = isEnglish ? 'No matching posts found.' : 'لم يتم العثور على نتائج';

    function normalizeText(text) {
        return text.toString().normalize('NFC').replace(/[^\p{L}\p{N}]+/gu,'').trim().toLowerCase();
    }

    const hasDates = posts.some(post => {
        const d = post.getAttribute('data-date');
        return d && !isNaN(new Date(d).getTime());
    });

    if (!hasDates) {
        const optionsToHide = sortSelect.querySelectorAll('option[value="newest"], option[value="oldest"]');
        optionsToHide.forEach(opt => opt.style.display = 'none');
    }

    function filterAndSortPosts() {
        const query = normalizeText(searchInput.value);
        //let visiblePosts = posts.filter(post => normalizeText(post.textContent).includes(query));
        let visiblePosts = posts.filter(post => {
        const selectors = LoopFilterSettings.titleSelectors;
        const targetEl = post.querySelector(selectors);
        const targetText = targetEl ? normalizeText(targetEl.textContent) : "";
        return targetText.includes(query);
        });
        posts.forEach(post => post.style.display = visiblePosts.includes(post) ? '' : 'none');
        noResults.style.display = visiblePosts.length === 0 ? 'block' : 'none';

        const sortValue = sortSelect.value;
        if (sortValue !== 'default') {
            visiblePosts.sort((a, b) => {
                // const aTitleEl = a.querySelector('h6, h3, .elementor-post__title');
                // const bTitleEl = b.querySelector('h6, h3, .elementor-post__title');
                const selectors = LoopFilterSettings.titleSelectors || 'h6, h3, .elementor-post__title';
                console.log("USED SELECTORS:", selectors);
                const aTitleEl = a.querySelector(selectors);
                const bTitleEl = b.querySelector(selectors);

                const aText = normalizeText(aTitleEl ? aTitleEl.textContent : a.textContent);
                const bText = normalizeText(bTitleEl ? bTitleEl.textContent : b.textContent);
                const aDate = a.getAttribute('data-date') || '';
                const bDate = b.getAttribute('data-date') || '';

                switch(sortValue) {
                    case 'newest': return new Date(bDate) - new Date(aDate);
                    case 'oldest': return new Date(aDate) - new Date(bDate);
                    case 'az': return aText.localeCompare(bText);
                    case 'za': return bText.localeCompare(aText);
                    default: return 0;
                }
            });
            visiblePosts.forEach(post => postsContainer.appendChild(post));
        } else {
            originalOrder.forEach(post => postsContainer.appendChild(post));
        }
    }

    searchInput.addEventListener('input', function() {
        filterAndSortPosts();
        clearBtn.style.display = this.value.trim() ? 'flex' : 'none';
    });

    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        this.style.display = 'none';
        filterAndSortPosts();
    });

    sortSelect.addEventListener('change', filterAndSortPosts);
});
