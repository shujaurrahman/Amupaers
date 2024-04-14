const searchInput = document.getElementById('search-input');
const searchResults = document.getElementById('search-results');
const suggestionsContainer = document.getElementById('suggestions');
const sectionTitle = document.getElementById('section-title');


let debounceTimeout;
const fetchAndDisplayTags = () => {
    fetch(`../backend/get_all_tags.php`)
        .then(response => response.json())
        .then(data => {
            const tagsList = document.getElementById('all-tags');

            data.forEach(tag => {
                const tagItem = document.createElement('li');
                tagItem.textContent = tag;
                tagItem.classList.add('tag');
                tagsList.appendChild(tagItem);
            });
        })
        .catch(error => console.error('Error fetching tags:', error));
};
// Call the function to fetch and display tags when the page loads
window.addEventListener('load', fetchAndDisplayTags);

// Function to display papers by tag
function displayPapersByTag(tag) {
    // Set the section title to the clicked tag
    sectionTitle.textContent = `All Papers Tagged as '${tag}'`;

    // Fetch and display papers associated with the tag
    fetch(`../backend/get_papers_by_tag.php?tag=${tag}`)
        .then(response => response.json())
        .then(papers => {
            // Clear previous search results
            searchResults.innerHTML = '';

            // Display papers
            papers.forEach(paper => {
                const listItem = document.createElement('li');
                const article = document.createElement('article');
                article.classList.add('category-card');
                article.onclick = () => openPDF(paper.file_path, paper.id); // assuming openPDF is defined somewhere

                const contentDiv = document.createElement('div');

                const titleHeader = document.createElement('h3');
                titleHeader.classList.add('h3', 'card-title');
                const titleLink = document.createElement('a');
                titleLink.href = '#';
                titleLink.textContent = paper.title;
                titleHeader.appendChild(titleLink);

                const metaSpan1 = document.createElement('span');
                metaSpan1.classList.add('card-meta');
                metaSpan1.textContent = `${paper.department} DEPARTMENT`;

                const metaSpan2 = document.createElement('span');
                metaSpan2.classList.add('card-meta', 'small');
                metaSpan2.textContent = `${paper.course}`;

                const metaSpan3 = document.createElement('span');
                metaSpan3.classList.add('card-meta', 'small');
                metaSpan3.textContent = `${paper.category} paper of year ${paper.year}`;

                const metaSpan4 = document.createElement('span');
                metaSpan4.classList.add('card-meta', 'small');
                const uploadDate = new Date(paper.upload_date);
                metaSpan4.textContent = ` ${uploadDate.toDateString()} | Views: ${paper.view_count}`;

                contentDiv.appendChild(titleHeader);
                contentDiv.appendChild(metaSpan1);
                contentDiv.appendChild(document.createElement('br'));
                contentDiv.appendChild(metaSpan2);
                contentDiv.appendChild(document.createElement('br'));
                contentDiv.appendChild(metaSpan3);
                contentDiv.appendChild(document.createElement('br'));
                contentDiv.appendChild(metaSpan4);

                article.appendChild(contentDiv);
                listItem.appendChild(article);
                searchResults.appendChild(listItem);
            });
        })
        .catch(error => console.error('Error fetching papers by tag:', error));
}


// Event listener for tag clicks
document.getElementById('all-tags').addEventListener('click', (event) => {
    if (event.target.classList.contains('tag')) {
        const tag = event.target.textContent;
        displayPapersByTag(tag);
    }
});




// Function to clear out all available tags
const clearAllTags = () => {
    const allTagsContainer = document.querySelector('.all-tags');
    if (allTagsContainer) {
        allTagsContainer.remove();
    }
};

// Function to fetch search suggestions
const fetchSuggestions = (query) => {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        fetch(`../backend/search.php?query=${query}`)
            .then(response => response.json())
            .then(data => {
                // Clear previous suggestions
                suggestionsContainer.innerHTML = '';

                // Sort suggestions based on relevance
                const sortedSuggestions = data.sort((a, b) => {
                    // Calculate relevance score for each suggestion
                    const relevanceA = calculateRelevance(a, query);
                    const relevanceB = calculateRelevance(b, query);

                    // Sort in descending order of relevance score
                    return relevanceB - relevanceA;
                });

                // Display up to 3 best matches
                sortedSuggestions.slice(0, 2).forEach(suggestion => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.textContent = suggestion.title;
                    suggestionItem.classList.add('suggestion-item');
                    suggestionItem.addEventListener('click', () => {
                        searchInput.value = suggestion.title;
                        suggestionsContainer.innerHTML = '';
                        performSearch(suggestion.title);
                    });
                    suggestionsContainer.appendChild(suggestionItem);
                });
            })
            .catch(error => console.error('Error fetching suggestions:', error));
    }, 300); // Adjust debounce time as needed
};

// Function to calculate relevance score for a suggestion
const calculateRelevance = (suggestion, query) => {
    let relevance = 0;
    if (suggestion.title.toLowerCase().includes(query.toLowerCase())) {
        relevance += 10;
    }
    return relevance;
};

// / Function to perform search
const performSearch = (query) => {
    clearAllTags();
    fetch(`../backend/search.php?query=${query}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                sectionTitle.textContent = `No papers found for query "${query}"`;
                searchResults.innerHTML = '';
            } else {
                sectionTitle.textContent = `${data.length} Result for query "${query}"`;
            suggestionsContainer.innerHTML='';
            searchResults.innerHTML = '';
            data.forEach(paper => {
                const listItem = document.createElement('li');
                const article = document.createElement('article');
                article.classList.add('category-card');
                article.onclick = () => openPDF(paper.file_path, paper.id); // assuming openPDF is defined somewhere

                const contentDiv = document.createElement('div');

                const titleHeader = document.createElement('h3');
                titleHeader.classList.add('h3', 'card-title');
                const titleLink = document.createElement('a');
                titleLink.href = '#';
                titleLink.textContent = paper.title;
                titleHeader.appendChild(titleLink);

                const metaSpan1 = document.createElement('span');
                metaSpan1.classList.add('card-meta');
                metaSpan1.textContent = `${paper.department} DEPARTMENT`;

                const metaSpan2 = document.createElement('span');
                metaSpan2.classList.add('card-meta', 'small');
                metaSpan2.textContent = `${paper.course}`;

                const metaSpan3 = document.createElement('span');
                metaSpan3.classList.add('card-meta', 'small');
                metaSpan3.textContent = `${paper.category} paper of year ${paper.year}`;

                const metaSpan4 = document.createElement('span');
                metaSpan4.classList.add('card-meta', 'small');
                const uploadDate = new Date(paper.upload_date);
                metaSpan4.textContent = ` ${uploadDate.toDateString()} | Views: ${paper.view_count}`;

                contentDiv.appendChild(titleHeader);
                // contentDiv.appendChild(document.createElement('br'));
                contentDiv.appendChild(metaSpan1);
                contentDiv.appendChild(document.createElement('br'));
                contentDiv.appendChild(metaSpan2);
                contentDiv.appendChild(document.createElement('br'));
                contentDiv.appendChild(metaSpan3);
                contentDiv.appendChild(document.createElement('br'));
                contentDiv.appendChild(metaSpan4);

                article.appendChild(contentDiv);
                listItem.appendChild(article);
                // Append listItem to searchResults directly
                searchResults.appendChild(listItem);
            });
        }
        })
        .catch(error => console.error('Error fetching search results:', error));
};


// Event listener for search input
searchInput.addEventListener('input', () => {
    const query = searchInput.value.trim();
    if (query.length > 0) {
        fetchSuggestions(query);
    } else {
        suggestionsContainer.innerHTML = ''; // Clear suggestions if query is empty
    }
});

// Event listener for search button
document.getElementById('search-btn').addEventListener('click', () => {
    const query = searchInput.value.trim();
    if (query.length > 0) {
        performSearch(query);
    } else {
        suggestionsContainer.innerHTML = ''; // Clear suggestions if query is empty
        searchResults.innerHTML = ''; // Clear search results if query is empty
        sectionTitle.innerHTML='';
    }
});

// Listen for Enter key press and trigger search
searchInput.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
        const query = searchInput.value.trim();
        if (query.length > 0) {
            performSearch(query);
        }
    }
});

// searchInput.addEventListener('focus', () => {
//     suggestions.style.display = 'block';
// });

// // Hide suggestion box when input is blurred
// searchInput.addEventListener('blur', () => {
//     suggestions.style.display = 'none';  });

searchInput.addEventListener('focus', () => {
    suggestions.style.display = 'block';
});

// Hide suggestion box when input is blurred, unless clicked on suggestion
document.addEventListener('click', (event) => {
    const clickedElement = event.target;
    const isSuggestionClicked = suggestions.contains(clickedElement);
    const isSearchInput = clickedElement.id === 'search-input';

    if (!isSuggestionClicked && !isSearchInput) {
        suggestions.style.display = 'none';
    }
});

// Function to handle suggestion click
const handleSuggestionClick = (suggestion) => {
    // Set the clicked suggestion as the value of the search input
    searchInput.value = suggestion;
    // Hide the suggestion box
    suggestions.style.display = 'none';
    // Optionally, you can submit the search form or trigger a search function here
};

// Example: Add click event listener to suggestions
suggestions.addEventListener('click', (event) => {
    const clickedSuggestion = event.target.textContent;
    handleSuggestionClick(clickedSuggestion);
});
