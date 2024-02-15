import videos from './films.js';
import seriesList from './series.js';


let FilmWtihId = [];

function extractMovieDetails(videoTitle) {
    const titleYearRegex = /^(.+?)\.(\d{4})[^*]*\.mp4$/;
    const match = videoTitle.match(titleYearRegex);

    if (match) {
        const title = match[1].replace(/[.\s]/g, '+');
        const year = match[2];
        const filename = match[0];
        const id = match[3] || null;

        if (id) {
            //console.log(FilmWtihId);
            const filenameWithoutId = filename.replace(/\sid="\d+"/, '');
            return FilmWtihId.push({ filename: filenameWithoutId, id });
        } else {
            return { title, year, filename };
        }

    } else {
        console.log('changes le format de ses films:', videoTitle);
        return { title: null, year: null, filename: null, id: null };
    }
}
videos.forEach(videoTitle => {
    const { title, year, filename } = extractMovieDetails(videoTitle);
    fetchMovieDetails(title, year, filename);
});


let FilmNoFetched = [];
let globalMovieInfo = [];
let movieCount = 0;

function MovieCount() {
    const movieCountHtml = document.getElementById('movie-count');
    movieCountHtml.textContent = globalMovieInfo.length;

}
//genres filters

const genreList = document.querySelector('.genre-list');
const genreListContainer = genreList.parentElement;
let selectedGenres = []; // Array to store selected genre IDs

// Genre list event listener
genreListContainer.addEventListener('click', (e) => {
    if (e.target && e.target.matches("li")) {
        const checkboxId = e.target.getAttribute('data-genre-id');
        if (selectedGenres.includes(parseInt(checkboxId))) {
            removeGenreFilter(checkboxId);
            toggleGenre(checkboxId);
            filterMoviesAndSeries();
        } else {
            toggleGenre(checkboxId);
            filterMoviesAndSeries();
            createGenreFilter(checkboxId);
        }
    }
});

// Toggle genre selection
function toggleGenre(genreId) {
    const index = selectedGenres.indexOf(parseInt(genreId));
    if (index === -1) {
        // Genre not in the array, add it
        selectedGenres.push(parseInt(genreId));
    } else {
        // Genre already in the array, remove it        
        selectedGenres.splice(index, 1);
    }
}

// Filter movies and series based on selected genres
function filterMoviesAndSeries() {
    globalMovieInfo.forEach(movie => {
        const isVisible = arraysMatch(selectedGenres, movie.genre);
        const movielabel = document.getElementById(movie.id);
        if (movielabel) {
            movielabel.classList.toggle("hide-genre", !isVisible);
        }
    });
    globalSerieInfo.forEach(serie => {
        const isVisible = arraysMatch(selectedGenres, serie.genre);
        const serielabel = document.getElementById(serie.id);
        if (serielabel) {
            serielabel.classList.toggle("hide-genre", !isVisible);
        }
    });

    function arraysMatch(selectedGenres, movieGenres) {
        if (selectedGenres.length === 0) return true;
        return selectedGenres.every(selectedGenre => movieGenres.includes(selectedGenre));
    }
}
const genreFilter = document.querySelector('.genre-filter');
// Create genre filter element
function createGenreFilter(genreId) {
    const genreName = document.querySelector(`[data-genre-id="${genreId}"]`).innerText;
    const shortenedGenreName = genreName.substring(0, 6); // Shorten to 5 characters
    const divTag = document.createElement('div');
    divTag.innerHTML = `<p>${shortenedGenreName}</p><span class="close" data-genre-id="${genreId}">&times;</span>`;
    genreFilter.appendChild(divTag);
}

// Remove genre filter element
function removeGenreFilter(genreId) {
    if (!genreId) return;
    const genreFilters = document.querySelectorAll('.genre-filter div');
    genreFilters.forEach(filter => {
        if (filter.querySelector('span').getAttribute('data-genre-id') === genreId) {
            filter.remove();
        }
    });
}

// Close button event listener for removing genre filter
genreFilter.addEventListener('click', (e) => {
    if (e.target && e.target.matches('span')) {
        const genreId = e.target.getAttribute('data-genre-id');
        toggleGenre(genreId);
        filterMoviesAndSeries();
        removeGenreFilter(genreId);
    }
});

//menu genre-list
const searchInput = document.querySelector("[data-search]");
const icon = document.querySelector('.fas.fa-search');
searchInput.addEventListener("click", () => {
    genreList.classList.toggle("hide");
});
document.addEventListener("click", (e) => {
    if (!genreList.contains(e.target) && e.target !== searchInput) {
        genreList.classList.add("hide");
    }
});

//search filter
searchInput.addEventListener("input", e => {

    const value = e.target.value.toLowerCase();
    const regex = new RegExp(escapeRegex(value), 'i');
    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // Searching through movie info
    globalMovieInfo.forEach(movie => {
        const isVisible = regex.test(movie.title.toLowerCase()) || regex.test(movie.year);
        const movielabel = document.getElementById(movie.id);
        if (movielabel) {
            movielabel.classList.toggle("hide", !isVisible);
        }
    });

    // Searching through series info
    globalSerieInfo.forEach(series => {
        const isVisible = series.title.toLowerCase().includes(value) || series.year.includes(value);
        const seriesLabel = document.getElementById(series.id);
        if (seriesLabel) {
            seriesLabel.classList.toggle("hide", !isVisible);
        }
    });

    if (searchInput.value) {

        genreList.classList.add("hide");
        searchInput.classList.add('expanded');
        icon.classList.add('opacity');
    } else {

        genreList.classList.remove("hide");
        searchInput.classList.remove('expanded');
        icon.classList.remove('opacity');
    }
});

//film
async function fetchMovieDetails(videoTitle, releaseYear, filename) {
    const title = encodeURIComponent(videoTitle);
    const year = encodeURIComponent(releaseYear);
    const apiKey = 'ea8a949d784bd700cd45f9b7b5248479';
    const searchUrl = `https://api.themoviedb.org/3/search/movie?api_key=${apiKey}&query=${title}&language=fr-FR&region=FR&primary_release_year=${year}`;


    const response = await fetch(searchUrl);
    const data = await response.json();

    return fetch(searchUrl)
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data.results.length > 0) {
                const movie = data.results[0];
                //console.log(globalMovieInfo);
                const posterPath = getPosterPath(movie);
                const voteAverage = movie.vote_average;
                let formatedVote = movie.vote_average.toFixed(1);
                function formatVote(voteAverage) {
                    let formatedVote;
                    if (voteAverage % 1 === 0) {
                        formatedVote = voteAverage.toFixed(0);
                    } else {
                        formatedVote = voteAverage.toFixed(1);
                    }
                    return formatedVote;
                }
                const formattedVote = formatVote(voteAverage);
                const rating = formattedVote + ('/10');
                const movieCard = document.createElement('div');
                movieCard.classList.add('movie-card');
                function getPosterPath(movie) {
                    if (movie.poster_path) {
                        return `https://image.tmdb.org/t/p/w300/${movie.poster_path}`;
                    } else {
                        return 'img/posterholder.png';
                    }
                }
                const movieID = movie.id;
                const genreIds = movie.genre_ids;
                const label = document.createElement('label');
                label.setAttribute('for', 'p1');
                label.setAttribute('class', 'cursorpointer');
                label.id = movieID;
                label.setAttribute('genre', genreIds);
                label.setAttribute('onclick', `handlePosterClick(${movieID})`);
                label.setAttribute('path', filename);

                const movieCover = document.createElement('img');
                movieCover.classList.add('lazyload');
                movieCover.src = posterPath;

                const movieInfo = document.createElement('div');
                movieInfo.classList.add('movie-info');

                const movieTitle = document.createElement('h2');
                movieTitle.classList.add('movie-title');
                movieTitle.textContent = movie.title;
                movieTitle.setAttribute('title', movie.title);


                const movieYear = document.createElement('span');
                movieYear.classList.add('movie-year');
                movieYear.textContent = movie.release_date.substring(0, 4);
                const movieRating = document.createElement('span');
                const movieDetails = document.createElement('div')
                movieDetails.classList.add('movie-details')
                movieRating.classList.add('movie-rating');
                movieRating.textContent = rating;
                const starIcon = document.createElement('i');
                starIcon.classList.add('fa-solid', 'fa-star', 'stars');

                const movieSummary = document.createElement('p');
                movieSummary.classList.add('hide');
                movieSummary.textContent = movie.overview;

                movieInfo.appendChild(movieTitle);
                movieInfo.appendChild(movieDetails)
                movieInfo.appendChild(movieSummary);
                movieDetails.appendChild(movieYear);
                movieCard.appendChild(movieCover);
                movieCard.appendChild(movieInfo);
                label.appendChild(movieCard)
                const ratingContainer = document.createElement('div');
                ratingContainer.classList.add('rating-container');
                ratingContainer.appendChild(starIcon);
                ratingContainer.appendChild(movieRating);

                movieDetails.appendChild(ratingContainer);

                // Append the movie card to the movie grid
                const movieGrid = document.getElementById('movie-grid');
                movieGrid.appendChild(label);

                //return list for search
                if (data.results.length > 0) {
                    const movie = data.results[0];
                    globalMovieInfo.push({
                        id: movieID,                        
                        genre: genreIds,
                        title: movie.title,
                        year: movie.release_date,
                        rating: movie.vote_average,
                        plot: movie.overview,
                        poster: posterPath,
                        path: filename,
                    });
                }
                //loader
                const loader = document.getElementById('loader-1');
                loader.classList.add('hide');

                movieCount = globalMovieInfo.length;
                globalMovieInfo.sort((a, b) => {
                    return parseInt(b.year) - parseInt(a.year);
                });

                return (globalMovieInfo);
            } else {

                FilmNoFetched.push(filename);
                return null;
            }
        })
        .then(returnedValues => {

            MovieCount();
        })
        .catch(error => {
            console.error(error);
            return null;
        });

}

//category
function switchCategory(category) {
    var filmLink = document.querySelector('a[data-category="film"]');
    var serieLink = document.querySelector('a[data-category="serie"]');
    const list = document.querySelectorAll('.genre-list li');
    if (category === 'film') {
        filmLink.classList.add('visible');
        serieLink.classList.remove('visible');
        globalMovieInfo.forEach(movie => {
            const movielabel = document.getElementById(movie.id);
            movielabel.classList.remove("hide-category");
        });
        globalSerieInfo.forEach(serie => {
            const serielabel = document.getElementById(serie.id);
            serielabel.classList.add("hide-category");
        });
        list.forEach(li => {
            li.classList.toggle("hide");
        });

    } else if (category === 'serie') {
        serieLink.classList.add('visible');
        filmLink.classList.remove('visible');
        globalMovieInfo.forEach(movie => {
            const movielabel = document.getElementById(movie.id);
            movielabel.classList.add("hide-category");
        });
        globalSerieInfo.forEach(serie => {
            const movielabel = document.getElementById(serie.id);
            movielabel.classList.remove("hide-category");
        });
        list.forEach(li => {
            li.classList.toggle("hide");
        });
    }
}
document.querySelector('a[data-category="film"]').addEventListener('click', function () {
    switchCategory('film');
});
document.querySelector('a[data-category="serie"]').addEventListener('click', function () {
    switchCategory('serie');
});


//FETCH API SERIE

let seriesInfo = {};
let highestSeason = {};
seriesList.forEach(filePath => {
    const parts = filePath.split('/');
    const fileName = parts[parts.length - 1];
    const seriesData = fileName.match(/^(.*?)\.S(\d+)E(\d+)(\.\w+)?\.mp4$/);
    if (seriesData) {
        const seriesName = seriesData[1];
        const seasonNumber = parseInt(seriesData[2]);
        const season = `S${seasonNumber}`;
        const episodefile = `${filePath}`;

        if (!seriesInfo[seriesName]) {
            seriesInfo[seriesName] = {
                seasons: {},
            };
        }

        if (!seriesInfo[seriesName].seasons[season]) {
            seriesInfo[seriesName].seasons[season] = [];
        }
        seriesInfo[seriesName].seasons[season].push(episodefile);

        if (!highestSeason[seriesName] || seasonNumber > highestSeason[seriesName]) {
            highestSeason[seriesName] = seasonNumber;
        }
    }
});

for (const seriesName in highestSeason) {
    const highestSeasonNumber = highestSeason[seriesName];
    fetchSerieDetails(seriesName, highestSeasonNumber);
}
async function fetchSerieDetails(seriesName, season) {
    const formattedTitle = seriesName.replace(/[ .]/g, '+');
    const title = encodeURIComponent(formattedTitle);
    //const year = encodeURIComponent(releaseYear);
    const apiKey = 'ea8a949d784bd700cd45f9b7b5248479';
    const searchUrl = `https://api.themoviedb.org/3/search/tv?api_key=${apiKey}&query=${title}&language=fr-FR&region=FR`;

    const responsetv = await fetch(searchUrl);
    const datatv = await responsetv.json();

    return fetch(searchUrl)
        .then(responsetv => {
            return responsetv.json();
        })
        .then(datatv => {
            if (datatv.results.length > 0) {
                const serie = datatv.results[0];
                const posterPath = getSeriePosterPath(serie);
                const voteAverage = serie.vote_average;
                function formatVote(voteAverage) {
                    let formatedVote;
                    if (voteAverage % 1 === 0) {
                        formatedVote = voteAverage.toFixed(0);
                    } else {
                        formatedVote = voteAverage.toFixed(1);
                    }
                    return formatedVote;
                }
                const formattedVote = formatVote(voteAverage);
                const rating = formattedVote + ('/10');
                const serieCard = document.createElement('div');
                serieCard.classList.add('movie-card');
                function getSeriePosterPath(serie) {
                    if (serie.poster_path) {
                        return `https://image.tmdb.org/t/p/w300/${serie.poster_path}`;
                    } else {
                        return 'img/posterholder.png';
                    }
                }
                const serieID = serie.id;
                const genreIds = serie.genre_ids;
                const label = document.createElement('label');
                label.setAttribute('for', 'p1');
                label.setAttribute('class', 'cursorpointer, hide-category');
                label.id = serieID;
                label.setAttribute('onclick', `handlePosterClick(${serieID})`);
                const allsaisons = Object.keys(seriesInfo[seriesName].seasons).sort();
                label.setAttribute('saison', allsaisons);
                label.setAttribute('genre', genreIds);
                const firstSeason = Object.keys(seriesInfo[seriesName].seasons).sort()[0];
                const firstEP = seriesInfo[seriesName].seasons[firstSeason][0];
                label.setAttribute('path', firstEP);
                const serieCover = document.createElement('img');
                serieCover.src = posterPath;
                serieCover.classList.add('lazyload');

                const serieInfo = document.createElement('div');
                serieInfo.classList.add('movie-info');

                const serieTitle = document.createElement('h2');
                serieTitle.classList.add('movie-title');
                serieTitle.textContent = serie.name;
                serieTitle.setAttribute('title', serie.name);

                const serieYear = document.createElement('span');
                serieYear.classList.add('movie-year');
                serieYear.textContent = serie.first_air_date.substring(0, 4);
                const serieRating = document.createElement('span');
                const serieDetails = document.createElement('div');
                serieDetails.classList.add('movie-details');
                serieRating.classList.add('movie-rating');
                serieRating.textContent = rating;
                const starIcon = document.createElement('i');
                starIcon.classList.add('fa-solid', 'fa-star', 'stars');

                const serieSummary = document.createElement('p');
                serieSummary.classList.add('hide');
                serieSummary.textContent = serie.overview;

                serieInfo.appendChild(serieTitle);
                serieInfo.appendChild(serieDetails)
                serieInfo.appendChild(serieSummary);
                serieDetails.appendChild(serieYear);

                serieCard.appendChild(serieCover);
                serieCard.appendChild(serieInfo);
                label.appendChild(serieCard)
                const ratingContainer = document.createElement('div');
                ratingContainer.classList.add('rating-container');
                ratingContainer.appendChild(starIcon);
                ratingContainer.appendChild(serieRating);

                serieDetails.appendChild(ratingContainer);

                // Append the serie card to the serie grid
                const serieGrid = document.getElementById('movie-grid');
                serieGrid.appendChild(label);

                //return list for search
                if (datatv.results.length > 0) {
                    const serie = datatv.results[0];
                    globalSerieInfo.push({
                        id: serie.id,
                        title: serie.name,
                        year: serie.first_air_date,
                        rating: serie.vote_average,
                        genre: genreIds,
                        seasons: seriesInfo[seriesName].seasons,                    
                        plot: serie.overview,                        
                        poster: posterPath,
                    });
                }
                //loader
                // const loader = document.getElementById('loader-1');
                //loader.classList.add('hide');
                console.log(globalSerieInfo);
                return (globalSerieInfo);
            }
        })
        .catch(error => {
            console.error(error);
            return null;
        });
}

console.log("films no fetched :", FilmNoFetched);