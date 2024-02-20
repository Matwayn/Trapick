<?php
session_start();
if(isset($_GET['logout'])) {
	unset($_SESSION["login"]);
    header("Location: login_1.php");
}
if (!isset($_SESSION["login"])) {
  header("Location: login_1.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="/assets/css/solid.min.css">
    <title>TkP</title>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <!--link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" /-->
</head>

<body>
    <header>
        <div class="category">
            <div class="category-type">
                <a data-category="film" href="#" class="visible">Film</a>
                <a data-category="serie" href="#serie">Serie</a>
            </div>
            <div class="box">
                <input type="text" class="inputbox" id="search" data-search><i class="fas fa-search"></i>
                <div class="genre-filter">
                    <!--capsule here-->
                </div>

                <div class="genre-list hide">
                    <ul>
                        <li data-genre-id="28">Action</li>
                        <li data-genre-id="53">Thriller</li>
                        <li data-genre-id="99">Documentaire</li>
                        <li data-genre-id="14">Fantastique</li>
                        <li data-genre-id="878">Science-Fiction</li>
                        <li data-genre-id="35">Comédie</li>
                        <li data-genre-id="12">Aventure</li>
                        <li data-genre-id="18">Drame</li>
                        <li data-genre-id="27">Horreur</li>
                        <li data-genre-id="9648">Mystère</li>
                        <li data-genre-id="80">Crime</li>
                        <li data-genre-id="16">Animation</li>
                        <li class="hide" data-genre-id="35">Comédie</li>
                        <li class="hide" data-genre-id="10759">Action & Aventure</li>
                        <li class="hide" data-genre-id="10765">S-F & Fantastique</li>
                        <li class="hide" data-genre-id="10768">Guerre & Politique</li>
                        <li class="hide" data-genre-id="10751">Familial</li>
                        <li class="hide" data-genre-id="16">Animation</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="film-count">
            <p id="movie-count">0</p><br>Films</br>
        </div>

        </div>
    </header>
    <main>
        <div class="container">
            <div class="loader" id="loader-1"></div>
        </div>
        <div class="movie-grid" id="movie-grid">
            <!-- Les films ici -->
        </div>
        <input type="checkbox" class="hide" id="p1">
        <div id="popup1" class="overlay light">
            <label for="p1" class="cancel"></label>
            <div class="popup">
                <img class="popup" id="poster" src="img/posterholder.png">
                <div id="popup-content" class="popup-content">
                    <h1 id="popup-title">Not able to fetch the name</h1>
                    <h2 id="popup-year"><strong>N/A</strong></h2>
                    <h3 id="popup-genre"><strong></strong></h3>
                    <p><strong id="popup-rating"><i class="fa-solid fa-star stars"></i>N/A/10</strong></p></i>
                    <span id="popup-plot">N/A</span>
                    <label for="p1" class="close">&times;</label>
                    <div class="content-button">
                        <div class="play">
                            <a onclick="setupTrailerPopup()" id="popup-trailer" data-id="" target="_blank"
                                class="button"><strong>Trailer</strong> </a>
                            <a onclick="videoplay()" id="popup-play" target="_blank" title="" path-film=""
                                class="button"><strong>Play</strong></a>

                        </div>
                        <div id="menuSerie" class="menu-series">
                            <div id="SA">
                                <ul id="seasons-list" class="list hide">

                                </ul>
                            </div>
                            <a id="button-saison" class="menu-item, button" onclick="showSeasons()">Saison 1</a>
                            <div id="EP">
                                <ul id="episodes-list" class="list hide">

                                </ul>
                            </div>
                            <a id="button-episode" class="menu-item, button" onclick="showEpisodes()">Episode 1</a>

                        </div>
                    </div>
                </div>

            </div>
            <div id="player" class="iframe-container hide"><span class="close-btn" id="close-btn">&times;</span>
                <iframe id="iframe" width="756" height="425" frameborder="0" src="" allowfullscreen allowtransparency
                    autoplay=true></iframe>
            </div>
        </div>

        <script>
            //close popup with echap key
            document.addEventListener('keydown', function (event) {
                if (event.key === "Escape" || event.keyCode === 27) {
                    const closeBtn = document.getElementById('close-btn');
                    const iframeTrailer = document.getElementById("player");
                    iframeTrailer.classList.add('hide');
                    const popupIframe = document.getElementById("iframe");
                    popupIframe.src = ""; 
                    const checkbox = document.getElementById('p1');
                    checkbox.checked = false;
                }
            });

            let id1;
            let serieInfocontent = [];

            function handlePosterClick(id) {
                id1 = id;
                const movieselected = document.getElementById(id);
                const selectedtitle = movieselected.querySelectorAll('.movie-title');
                const selectedyear = movieselected.querySelectorAll('.movie-year');
                const selectedGenre = movieselected.getAttribute('genre');
                const selectedrating = movieselected.querySelectorAll('.movie-rating');
                const selectedplot = movieselected.querySelectorAll('.hide');
                const selectedimg = movieselected.querySelector('img');
                const selectedposter = selectedimg.src;
                const posterpopup = document.getElementById('poster');
                posterpopup.src = selectedposter;
                const selectedpath = movieselected.getAttribute('path');
                const popupplay = document.getElementById('popup-play')

                let genres = [
                    {
                        "id": 10759,
                        "name": "Action & Adventure"
                    },
                    {
                        "id": 10762,
                        "name": "Kids"
                    },
                    {
                        "id": 9648,
                        "name": "Mystère"
                    },
                    {
                        "id": 10763,
                        "name": "News"
                    },
                    {
                        "id": 10764,
                        "name": "Realité"
                    },
                    {
                        "id": 10765,
                        "name": "S-F & Fantastique"
                    },
                    {
                        "id": 10766,
                        "name": "Soap"
                    },
                    {
                        "id": 10767,
                        "name": "Talk"
                    },
                    {
                        "id": 10768,
                        "name": "Guerre & Politique"
                    },
                    {
                        "id": 28,
                        "name": "Action"
                    },
                    {
                        "id": 12,
                        "name": "Aventure"
                    },
                    {
                        "id": 16,
                        "name": "Animation"
                    },
                    {
                        "id": 35,
                        "name": "Comédie"
                    },
                    {
                        "id": 80,
                        "name": "Crime"
                    },
                    {
                        "id": 99,
                        "name": "Documentaire"
                    },
                    {
                        "id": 18,
                        "name": "Drame"
                    },
                    {
                        "id": 10751,
                        "name": "Familial"
                    },
                    {
                        "id": 14,
                        "name": "Fantastique"
                    },
                    {
                        "id": 36,
                        "name": "Histoire"
                    },
                    {
                        "id": 27,
                        "name": "Horreur"
                    },
                    {
                        "id": 10402,
                        "name": "Musique"
                    },
                    {
                        "id": 9648,
                        "name": "Mystère"
                    },
                    {
                        "id": 10749,
                        "name": "Romance"
                    },
                    {
                        "id": 878,
                        "name": "Science-Fiction"
                    },
                    {
                        "id": 10770,
                        "name": "Téléfilm"
                    },
                    {
                        "id": 53,
                        "name": "Thriller"
                    },
                    {
                        "id": 10752,
                        "name": "Guerre"
                    },
                    {
                        "id": 37,
                        "name": "Western"
                    }
                ]
                let selectedGenreIds = selectedGenre.split(',').map(id => parseInt(id.trim()));
                let selectedGenreNames = [];

                selectedGenreIds.forEach(id => {
                    let genre = genres.find(genre => genre.id === id);
                    if (genre) {
                        selectedGenreNames.push(genre.name);
                    }
                });
                const popupGenre = document.getElementById("popup-genre");
                popupGenre.innerText = selectedGenreNames.join(', ');

                selectedtitle.forEach(selectedtitle => {
                    document.getElementById('popup-title').innerText = selectedtitle.innerHTML;
                });
                selectedyear.forEach(selectedyear => {
                    document.getElementById('popup-year').innerText = selectedyear.innerHTML;

                });

                selectedrating.forEach(selectedrating => {
                    const ratingContainer = document.getElementById('popup-rating');
                    const ratingText = selectedrating.innerHTML;
                    const ratingHTML = `<i class="fa-solid fa-star stars"></i>${ratingText}`;
                    ratingContainer.innerHTML = ratingHTML;
                });
                selectedplot.forEach(selectedplot => {
                    document.getElementById('popup-plot').innerText = selectedplot.innerHTML;
                });
                //fetch film path 
                const truepath = '/' + selectedpath
                popupplay.setAttribute('path-film', truepath);
                popupplay.setAttribute('title', truepath);
                // Fetch trailer


                const trailerOptions = {
                    method: 'GET',
                    headers: {
                        accept: 'application/json',
                        Authorization: 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJlYThhOTQ5ZDc4NGJkNzAwY2Q0NWY5YjdiNTI0ODQ3OSIsInN1YiI6IjY1YjkyZGUxMzM0NGM2MDE4NTkyMzBmMSIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.sC9UQp2R51QBfDfSie_th8D3FTobBAsnDR41ojBKD0k'
                    }
                };
                const filmButton = document.querySelector('a[data-category="film"]');
                const serieButton = document.querySelector('a[data-category="serie"]');
                let type = 'movie';
                function updateType() {
                    if (document.querySelector('a[data-category="film"].visible')) {
                        type = 'movie';
                    } else if (document.querySelector('a[data-category="serie"].visible')) {
                        type = 'tv';
                    }
                }
                const menuserie = document.getElementById('menuSerie');
                function hidemenuserie() {
                    if (document.querySelector('a[data-category="film"].visible')) {
                        menuserie.classList.add('visibility');
                    } else if (document.querySelector('a[data-category="serie"].visible')) {
                        menuserie.classList.remove('visibility');
                    }
                }
                updateType();
                hidemenuserie();
                fetch(`https://api.themoviedb.org/3/${type}/${id}/videos?language=fr-FR`, trailerOptions)
                    .then(response => response.json())
                    .then(trailerData => {
                        if (trailerData.results.length > 0) {
                            // Find the first result that has type "Trailer"
                            const trailer = trailerData.results.find(result => result.type === "Trailer");

                            if (trailer) {
                                // Get the key for the desired language (French in this case)
                                document.getElementById("popup-trailer").setAttribute("data-id", trailer.key);
                                document.getElementById("popup-trailer").classList.remove('hide');
                            } else {

                                throw new Error('No trailer available');
                            }
                        } else {
                            fetch(`https://api.themoviedb.org/3/movie/${id}/videos?language=en-US`, trailerOptions)
                                .then(responseUS => {
                                    if (responseUS.ok) {
                                        return responseUS.json();
                                    } else {
                                        throw new Error('No trailer available');
                                    }
                                })
                                .then(trailerDataUS => {
                                    // Find the first result that has type "Trailer" in English
                                    const trailerUS = trailerDataUS.results.find(result => result.type === "Trailer");

                                    if (trailerUS) {
                                        // Get the key for the English trailer
                                        document.getElementById("popup-trailer").setAttribute("data-id", trailerUS.key);
                                        document.getElementById("popup-trailer").classList.remove('hide');
                                    } else {
                                        document.getElementById("popup-trailer").setAttribute("data-id", "");
                                        document.getElementById("popup-trailer").classList.add('hide');
                                    }
                                });
                        }
                    })

                    .catch(error => {

                        document.getElementById("popup-trailer").setAttribute("data-id", "");
                        document.getElementById("popup-trailer").classList.add('hide');
                    });
                return id
            }

            //series Saison-EP list
            function showSeasons() {
                if (id1 === null || id1 === undefined) {
                    closePopup();

                } else {
                    var seasonsList = document.getElementById('seasons-list');
                    var episodesList = document.getElementById('episodes-list');

                    episodesList.classList.add('hide');
                    if (seasonsList.classList.contains('hide')) {
                        seasonsList.classList.remove('hide');
                        const getSaison = document.getElementById(id1).getAttribute('saison');
                        const seasonNumbers = getSaison.split(',').map(s => parseInt(s.replace(/\D/g, '')));

                        let seasonsHTML = "";
                        seasonNumbers.forEach(number => {
                            seasonsHTML += `<li>Saison ${number}</li>`;
                        });


                        seasonsList.innerHTML = seasonsHTML;
                    } else {
                        seasonsList.classList.add('hide');
                    }
                }
            }



            let pathseries = [];
            
            function showEpisodes() {
                var seasonsList = document.getElementById('seasons-list');
                var episodesList = document.getElementById('episodes-list');

                seasonsList.classList.add('hide');

                // Toggle episodes list visibility
                if (episodesList.classList.contains('hide')) {
                    episodesList.classList.remove('hide');
                    const seasonText = document.getElementById('button-saison').textContent;
                    const seasonNumber = seasonText.replace(/\D/g, '');
                    const season = 'S' + seasonNumber;
                    let data = serieInfocontent;
                    let numberOfFiles = findSeasonFilesByIdAndSeason(id1, season).length;
                    if (numberOfFiles === 0) {
                        console.log(`Pas d'épisodes trouvés avec la Saison ${seasonNumber}`);
                        alert(`Pas d'épisodes trouvés avec la Saison ${seasonNumber}`);
                        closePopup();
                    }
                    pathseries = findSeasonFilesByIdAndSeason(id1, season);


                    function findSeasonFilesByIdAndSeason(id1, season) {
                        let series = data.find(serie => serie.id === id1);
                        if (!series) {
                            console.log(`Pas de saisons trouvées avec la serie ${id1}`);
                            closePopup();
                            // return null;
                        }
                        let episodeFiles = series.seasons[season];

                        if (!episodeFiles) {

                            console.log(`Pas d'épisodes trouvés avec la Saison ${seasonNumber}`);
                            alert(`Pas d'épisodes trouvés avec la Saison ${seasonNumber}`);
                            closePopup();
                            // return null;
                        }
                        return episodeFiles;
                    }
                    let episodeHtml = "";
                    for (let i = 1; i <= numberOfFiles; i++) {
                        episodeHtml += `<li>Episode ${i}</li>`;
                    }
                    episodesList.innerHTML = episodeHtml;
                    //console.log(id1, season, numberOfFiles);
                } else {
                    episodesList.classList.add('hide');
                }
                return pathseries;
            }


            //  event listener to all season list items
            document.getElementById("SA").addEventListener("click", function (e) {
                if (e.target && e.target.matches("li")) {
                    const seasonName = e.target.textContent;
                    replaceTextWithSeason(seasonName);
                }
            });

            //  event listener to all episode list items
            document.getElementById("EP").addEventListener("click", function (e) {
                if (e.target && e.target.matches("li")) {
                    const episodeName = e.target.textContent;
                    replaceTextWithEpisode(episodeName);
                    let epDigit = Number(episodeName.match(/\d+/)[0]);
                    let pathseriesfile = pathseries[epDigit - 1];

                    const selectedmovie = document.getElementById(id1);
                    selectedmovie.setAttribute('path', pathseriesfile);
                    const playerpath = document.getElementById('popup-play');
                    playerpath.setAttribute('path-film', pathseriesfile);
                    playerpath.setAttribute('title', pathseriesfile);

                }
            });

            // Function to close the popup
            function closePopup() {
                const saisonlist = document.getElementById
                    ('seasons-list');
                const episodelist = document.getElementById('episodes-list')
                // Hide the popup
                saisonlist.classList.add('hide');
                episodelist.classList.add('hide')
            }

            // Function to replace text when a season is clicked
            function replaceTextWithSeason(seasonName) {
                // Close the popup
                closePopup();
                const buttonseason = document.getElementById('button-saison');
                // Replace the text with the selected season
                buttonseason.textContent = seasonName;
            }

            // Function to replace text when an episode is clicked
            function replaceTextWithEpisode(episodeName) {
                // Close the popup
                closePopup();
                const buttonepisode = document.getElementById('button-episode');
                // Replace the text with the selected episode
                buttonepisode.textContent = episodeName;
            }

            //trailer
            function setupTrailerPopup() {
                const popupTrailerLink = document.getElementById("popup-trailer");
                const iframeTrailer = document.getElementById("player");
                const popupIframe = document.getElementById("iframe");
                const closeBtn = document.getElementById("close-btn");

                popupTrailerLink.addEventListener("click", function (event) {
                    event.preventDefault(); // Prevent default behavior (opening a new tab)

                    const videoId = popupTrailerLink.getAttribute("data-id");
                    const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&mute=0`;

                    popupIframe.src = embedUrl;
                    iframeTrailer.classList.remove('hide');
                });

                closeBtn.addEventListener("click", function () {
                    iframeTrailer.classList.add('hide');
                    popupIframe.src = ""; // Stop video when popup is closed
                });

                iframeTrailer.addEventListener("click", function (event) {
                    if (event.target === iframeTrailer) {
                        iframeTrailer.classList.add('hide');
                        popupIframe.src = ""; // Stop video when popup is closed
                    }
                });
            }

            document.addEventListener("DOMContentLoaded", setupTrailerPopup);
            //videoplayer
            function videoplay() {
                const popupFilmlink = document.getElementById("popup-play");
                const iframeTrailer = document.getElementById("player");
                const popupIframe = document.getElementById("iframe");
                const closeBtn = document.getElementById("close-btn");

                popupFilmlink.addEventListener("click", function (event) {
                    const videoId = popupFilmlink.getAttribute('path-film');

                    if (!videoId || videoId.trim() === "/null") {
                        console.log("Il semble pas y avoir de lien pour ce film");
                        alert("Il semble pas y avoir de lien pour ce film");
                        return false;
                    }

                    const embedUrl = videoId;

                    popupIframe.src = embedUrl;
                    iframeTrailer.classList.remove('hide');
                });

                closeBtn.addEventListener("click", function () {
                    iframeTrailer.classList.add('hide');
                    popupIframe.src = "";
                });

                iframeTrailer.addEventListener("click", function (event) {
                    if (event.target === iframeTrailer) {
                        iframeTrailer.classList.add('hide');
                        popupIframe.src = "";
                    }
                });
            }

        </script>
    </main>
    <footer>
        <p>&copy;Trapick</p>
    </footer>
    <script type="module" src="/js/script no fetch.js"></script>
    <!--<script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>-->
</body>

</html>