const feed = document.getElementById("feed");
const template = document.getElementById("post-template");
const components = [
    '.post-dname', '.post-time', '.post-title', '.post-artist', 
    '.post-genres', '.post-body', '.post-karma'
];

class PostElement {
    
    constructor(inst) {
        this.innerElements = [];
        this.clone = inst;
        for (let i = 0; i < components.length; i++) {
            this.innerElements.push(inst.querySelector(components[i]));
        }
    }
    
    setup(details) {
        this.#setSlug(details.slug);
        this.#setName(details.display_name);
        this.#setTime(details.elapsed);
        this.#setArtist(details.artist_name);
        this.#setTitle(details.song_title);
        this.#setGenres(details.genres);
        this.#setBody(details.content);
        this.#setKarma(details.karma);
    }
    
    #setSlug(slug) {
        let container = this.clone.querySelector(".post-container");
        container.id = slug;
    }
    
    #setName(name) {
        this.innerElements[0].innerText = name; 
    }
    
    #setTime(stamp) {
        let time = (stamp == "0") ? "Today" : stamp + " days ago.";
        this.innerElements[1].innerText = time;
    }
    
    #setTitle(title) {
        this.innerElements[2].innerText = title;
    }
    
    #setArtist(name) {
        this.innerElements[3].innerText = name;
    }
    
    #setGenres(genres) {
        
        let genresFormatted = genres.split(',');
        
        if (typeof genresFormatted === "string") {
            let genreBubble = document.createElement('p');
            genreBubble.innerText = genres;
            this.innerElements[4].appendChild(genreBubble);
        }
        else {
            for (let i = 0; i < genresFormatted.length; i++) {
                let genreBubble = document.createElement('p');
                genreBubble.innerText = genresFormatted[i];
                this.innerElements[4].appendChild(genreBubble);
            }
        }
    }
    
    #setBody(text) {
        this.innerElements[5].innerText = text;
    }
    
    #setKarma(karma) {
        this.innerElements[6].innerText = karma;
    }
}

function generate(details) {
    let postClone = template.content.cloneNode(true);
    let postObject = new PostElement(postClone);
    postObject.setup(details);
    feed.appendChild(postClone);
}

function generateMany(N, nDetails) {
    for (let i = 0; i < N; i++) {
        generate(nDetails[i]);
    } 
}

function start() {
    xhr = new XMLHttpRequest();
    xhr.open('GET', 'feed.php', true);
    let response;
    
    xhr.onreadystatechange = () => {
        
        if (xhr.readyState === XMLHttpRequest.DONE) {
            
            const status = xhr.status;
            if (status === 0 || (status >= 200 && status < 400)) {
                response = JSON.parse(xhr.responseText);
                generateMany(2, response);
            } else {
                response = null;
            }
        }
    };
    xhr.send();
}

start();