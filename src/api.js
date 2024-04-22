const feed = document.getElementById("feed");
const template = document.getElementById("post-template");

async function newFetch(payload) {
    
    const reqHeaders = new Headers();
    reqHeaders.append('Content-Type', 'application/json');
    const reqDetails = {
        method: "POST",
        headers: reqHeaders,
        body: payload
    };
    
    const response = await fetch("/TuneShare/src/api/api.php", reqDetails);
    var json = await response.json();
    return json;
}

class ApiElement {
    
    constructor(inst, components) {
        this.innerElements = [];
        this.inst = inst;
        for (let i = 0; i < components.length; i++) {
            this.innerElements.push(this.inst.querySelector(components[i]));
        }
    }
}

class ProfileElement extends ApiElement {
    
    constructor(inst) {
        const components = [
            '.user-dname', '.user-uname', 
            '.user-date', '.user-time'
        ];
        super(inst, components);
    }
    
    setup(details) {
        this.#setDname(details.display_name);
        this.#setUname(details.username);
        this.#setDate(details.date);
        this.#setTime(details.time);
    }
    
    #setDname(name) { this.innerElements[0].innerText = name; }
    
    #setUname(name) { this.innerElements[1].innerText = name; }
    
    #setDate(date) { this.innerElements[2].innerText = date; }
    
    #setTime(time) { this.innerElements[3].innerText = time; }
    
}

/* Wrapper class for posts 
 *
 * We need to check the initial vote state in the future. (User already voted) 
 * 
 */
class PostElement extends ApiElement {
    
    constructor(inst) {
        const components = [
            '.post-dname', '.post-time', '.post-title', '.post-artist', 
            '.post-genres', '.post-body', '.post-karma'
        ];
        super(inst, components);
    }
    
    setup(details) {
        
        this.actions = this.inst.querySelector(".post-btm");
        this.postId = details.post_id;
        this.userId = details.user_id;
        this.slug = details.slug;
        this.voteMode = 0;
        this.karma = parseInt(details.karma);
        this.#setSlug();
        this.#setName(details.display_name);
        this.#setTime(details.elapsed);
        this.#setArtist(details.artist_name);
        this.#setTitle(details.song_title);
        this.#setGenres(details.genres);
        this.#setBody(details.content);
        this.#setKarma(details.karma);
        this.#setListeners();
    }
    
    #setSlug() {
        let container = this.inst.querySelector(".post-container");
        container.id = this.slug;
    }
    
    #setName(name) {
        this.innerElements[0].innerText = name; 
    }
    
    #setTime(stamp) {
        
        let time;
        
        if (stamp == "0") {
            time = "Today";
        } else if (stamp == "1") {
            time = "1 day ago.";
        } else {
            time = stamp + " days ago.";
        }
        
        this.innerElements[1].innerText = time;
    }
    
    #setTitle(title) { this.innerElements[2].innerText = title; }
    
    #setArtist(name) { this.innerElements[3].innerText = name; }
    
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
    
    #setBody(text) { this.innerElements[5].innerText = text; }
    
    #setKarma(karma) { this.innerElements[6].innerText = karma; }
    
    #updateVote(val) {
        this.karma += val;
        this.#setKarma(this.karma);
        
        var request = JSON.stringify({
            "action": "vote", 
            "slug": this.slug, 
            "vote": val
        });
        newFetch(request);
    }
    
    /* Add event listeners for the post action toolbar */
    #setListeners() {
        let upvote = this.actions.querySelector(".post-like");
        let downvote = this.actions.querySelector(".post-dislike");
        //let reply = this.actions.querySelector("");
        let share = this.actions.querySelector(".post-share");
        
        // Visiting poster profile
        this.innerElements[0].addEventListener("click", () => {
            
            (async () => {
                var request = JSON.stringify({
                    "action": "userself",
                    "user_id": this.userId
                });
                var response = await newFetch(request); // slug
                window.location.href = getPath("profile.php?user=" + response);
            })();
            
        });
        
        upvote.addEventListener("click", () => {
            if (this.voteMode === 1) {
                this.#updateVote( -1 );
                this.voteMode = 0;
            }
            else if (this.voteMode === 0 || this.voteMode === -1) {
                this.#updateVote( 1 - this.voteMode );
                this.voteMode = 1;
            }
        });
        
        downvote.addEventListener("click", () => {
            if (this.voteMode === -1) {
                this.#updateVote( 1 );
                this.voteMode = 0;
            }
            else if (this.voteMode === 0 || this.voteMode === 1) {
                this.#updateVote( -1 - this.voteMode );
                this.voteMode = -1;
            }
        });
        
        share.addEventListener("click", () => {
            navigator.clipboard.writeText(getPath("post.php?p=" + this.slug));
            window.alert("Post URL copied to clipboard.");
        });
    }
}

function getPath(suffix) {
    let pathname = window.location.pathname;
    pathname = pathname.slice(0, pathname.lastIndexOf('/'));
    let root = window.location.origin + pathname + "/";
    return root + suffix;
}

/* Instantiates a PostElement object with post details */
function generatePost(details) {
    let postClone = template.content.cloneNode(true);
    let postObject = new PostElement(postClone);
    postObject.setup(details);
    feed.appendChild(postClone);
}

/* Instantiates many PostElement objects with multiple post details */
function generateMany(N, nDetails) {
    for (let i = 0; i < N; i++) {
        generatePost(nDetails[i]);
    } 
}

function generateFeed() {
// immediately invoked
    (async () => {
        var request = JSON.stringify( {"action": "feed"} );
        var response = await newFetch(request);
        generateMany(response.length, response);
    })();
}

function getPost(slug) {
    
    (async () => {
        var request = JSON.stringify( {"action": "post", "slug": slug} );
        var response = await newFetch(request);
        generatePost(response);
    })();
    
}

function generateProfile(details) {
    let profileBox = document.querySelector('#profile');
    console.log(profileBox);
    let profileObject = new ProfileElement(profileBox);
    profileObject.setup(details);
}

function getProfile(username) {
    (async () => {
        var request = JSON.stringify({
            "action": "user",
            "username": username
        });
        var response = await newFetch(request); // username
        generateProfile(response);
    })();
    
}

function getProfileSelf(userId) {
    (async () => {
        var request = JSON.stringify({
            "action": "userself",
            "user_id": userId
        });
        var response = await newFetch(request); // username
        window.location.href = getPath("profile.php?u=" + response['username']);
    })();
    
}