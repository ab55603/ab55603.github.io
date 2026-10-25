let map = L.map('map').setView([53.430127, 14.564802], 18);
// L.tileLayer.provider('OpenStreetMap.DE').addTo(map);
L.tileLayer.provider('Esri.WorldImagery').addTo(map);
let marker = L.marker([53.430127, 14.564802]).addTo(map);
marker.bindPopup("<strong>Hello!</strong><br>This is a popup.");

let root = getComputedStyle(document.documentElement);
let width = root.getPropertyValue("--map-width").trim();
let height = root.getPropertyValue("--map-height").trim();
let slices_x = 4;
let slices_y = 4;
let slices = slices_x*slices_y;
let correct = [];
let indices = [];
let perm = [];

document.getElementById("saveButton").addEventListener("click", function() {
    leafletImage(map, async function (err, canvas) {
        let permission = await Notification.requestPermission();

        let state = {
            "♿": false
        }
        
        let puzzle_area = document.querySelector(".puzzle-area");
        let rasterMap = document.getElementById("rasterMap");
        if (!rasterMap)
        {
            puzzle_area.replaceChildren();
            puzzle_area.innerHTML = `<canvas id="rasterMap" width="${width}" height="${height}"></canvas>`;
            rasterMap = document.getElementById("rasterMap");
        }
        let rasterContext = rasterMap.getContext("2d");

        for (let i = 0; i < slices; ++i)
            indices[i] = i;
        for (let i = 0; i < slices; ++i)
            perm[i] = indices.splice(Math.floor(Math.random()*indices.length), 1).pop();

        let container = document.querySelector(".puzzle-pieces");
        let content = "";
        for (let i = 0; i < slices; ++i)
        {
            content += `<canvas class="map-slice" data-index="${perm[i]}" draggable="true" width="${rasterMap.width/slices_x}" height="${rasterMap.height/slices_y}"></canvas>`;
            correct[i] = 0;
        }
        container.innerHTML = content;

        rasterContext.drawImage(canvas, 0, 0, rasterMap.width, rasterMap.height);

        let piece;
        let pieceContext;
        for (let i = 0; i < slices; ++i)
        {
            idx_x = i % slices_x;
            idx_y = Math.floor(i/slices_y);
            piece = document.querySelector(`.map-slice[data-index="${i}"]`);
            pieceContext = piece.getContext("2d");
            pieceContext.drawImage(rasterMap, rasterMap.width/slices_x*idx_x, rasterMap.height/slices_y*idx_y, rasterMap.width/slices_x, rasterMap.height/slices_y, 0, 0, piece.width, piece.height);
        }

        content = "";
        for (let i = 0; i < slices; ++i)
        {
            content += `<div class="drag-target" data-index="${i}"></div>`;
        }
        puzzle_area.innerHTML = content;

        let items = document.querySelectorAll('.map-slice');
        for (let item of items) {
            item.addEventListener("dragstart", function(event) {
                this.style.border = "5px dashed #D8D8FF";
                event.dataTransfer.setData("text", this.dataset.index);
            });

            item.addEventListener("dragend", function(event) {
                this.style.borderWidth = "0";
            });
        }
        
        let baseTarget = document.querySelector(".puzzle-pieces");
        let targets = document.querySelectorAll(".drag-target");
        for (let target of targets) {
            target.addEventListener("dragenter", function (event) {
            });
            target.addEventListener("dragover", function (event) {
                event.preventDefault();
            });
            target.addEventListener("drop", function (event) {
                let myElement = document.querySelector(`.map-slice[data-index="${event.dataTransfer.getData('text')}"]`);
                if (this.children.length > 0)
                    baseTarget.appendChild(this.children[0]);
                this.appendChild(myElement);
                let sum = 0;
                for (let i = 0; i < slices; ++i)
                {
                    let currTarget = targets[i];
                    let targetChild;
                    if (currTarget.children.length > 0)
                    {
                        targetChild = currTarget.children[0];
                        if (targetChild.dataset.index === currTarget.dataset.index)
                        {
                            correct[i] = 1;
                            sum++;
                        }
                        else
                            correct[i] = 0;
                    }
                    else
                        correct[i] = 0;
                }
                console.log(`ulozone puzzle: ${sum}/${slices}`);
                if (sum === slices)
                {
                    state["♿"] = true;
                    console.log("wszystkie puzzle ulozone");
                }
                if (permission === "granted" && state["♿"] === true)
                {
                    let notification = new Notification("puzzle", {
                        body: "puzzle ulozone",
                        navigate: "/messages",
                    });
                }
                else if (permission !== "granted" && state["♿"] === true)
                    console.log("moze zezwol na powiadomienia ??");
            }, false);
        }
        baseTarget.addEventListener("dragenter", function (event) {
        });
        baseTarget.addEventListener("dragover", function (event) {
            event.preventDefault();
        });
        baseTarget.addEventListener("drop", function (event) {
            let myElement = document.querySelector(`.map-slice[data-index="${event.dataTransfer.getData('text')}"]`);
            this.appendChild(myElement);
        }, false);
    });
});

document.getElementById("getLocation").addEventListener("click", function(event) {
    if (!navigator.geolocation) {
        console.log("No geolocation.");
    }

    navigator.geolocation.getCurrentPosition(position => {
        console.log(position);
        let lat = position.coords.latitude;
        let lon = position.coords.longitude;

        map.setView([lat, lon]);
        if (marker)
            map.removeLayer(marker);
        marker = L.marker([lat, lon]).addTo(map);
    }, positionError => {
        console.error(positionError);
    },
    {
        enableHighAccuracy: false,
        maximumAge: 120000
    });
});