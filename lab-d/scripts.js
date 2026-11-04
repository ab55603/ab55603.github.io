let key = "57b52bb94881cf58c820b702f2aa9fd6";
let current = "https://api.openweathermap.org/data/2.5/weather";
let forecast = "https://api.openweathermap.org/data/2.5/forecast";
let lat = 0;
let lon = 0;

function build_api_call(call) { call += `?lat=${lat}&lon=${lon}&units=metric&appid=${key}`; return call; }

let button = document.getElementById('check');
let place = document.getElementById('place');

button.onclick = async () => {
    let geo = `https://api.openweathermap.org/geo/1.0/direct?q=${place.value}&limit=1&appid=${key}`;
    let response = await fetch(geo);
    let result = await response.json();
    lat = result[0].lat;
    lon = result[0].lon;
    let currw = await get_current();
    console.log(currw);
    let forcw = await get_forecast();
    console.log(forcw);
    let container = document.querySelector(".weather");

    // PAKISTAN INIT
    container.innerHTML = `
        <div style="background:#555522; color:#aaa; padding:8px; margin:10px; border:5px ridge #999; border-radius:0px; font-family:monospace; width:fit-content; box-shadow:8px 8px 0px #222; transform:rotate(-0.3deg);">
            <h3 style="background:#999966; color:#333; padding:5px; text-decoration:underline overline;">!!! ${currw.name} WEATHER REPORT !!!</h3>
            <img width="45" src="https://openweathermap.org/img/wn/${currw.weather[0].icon}@2x.png">
            <h2 style="margin:0; color:#ff00ff; background:#333300; width:fit-content; border:2px dotted red;">${Math.round(currw.main.temp)}°C</h2>
            <p style="color:#00ff00; background:#444400;">${currw.weather[0].description}</p>
            <p>Feels like: ${currw.main.feels_like}°C</p>
            <p style="background:#777700;">Humidity: ${currw.main.humidity}%</p>
            <p style="color:red;">Pressure: ${currw.main.pressure} hPa</p>
            <p style="color:cyan; text-shadow:2px 2px red;">Wind speed: ${currw.wind.speed} m/s</p>
            <hr style="border:4px dashed magenta;">
            <h3 style="background:linear-gradient(90deg, red, yellow, lime, blue); color:black; padding:4px; border:3px groove white;">5 DAY FORECAST</h3>
            <div style="display:flex; flex-wrap:wrap; gap:5px; background:#333; padding:5px; border:4px double lime;">
                ${forcw.list.filter((item, index) => index % 8 === 0).map(item => `
                    <div style="background:#777; color:#ccc; padding:5px; border:3px outset #ff00ff; border-radius:0px; text-align:center; width:95px; font-size:11px; box-shadow:3px 3px 0px black;">
                        <h4 style="margin:2px; color:yellow; text-decoration:underline;">${item.dt_txt}</h4>
                        <img width="35" src="https://openweathermap.org/img/wn/${item.weather[0].icon}@2x.png">
                        <h3 style="margin:2px; color:lime;">${Math.round(item.main.temp)}°C</h3>
                        <p style="color:#ff00ff;">${item.weather[0].description}</p>
                        <p style="background:#555500;">Humidity: ${item.main.humidity}%</p>
                        <p style="color:cyan;">Wind: ${item.wind.speed} m/s</p>
                    </div>
                `).join("")}
            </div>
            <marquee style="color:red; background:yellow; margin-top:8px;">*** WEATHER WEATHER WEATHER *** ${currw.name} *** WEATHER WEATHER WEATHER ***</marquee>
        </div>`;
    // PAKISTAN END
};

function get_current()
{
    return new Promise((resolve) => {
        let xhr = new XMLHttpRequest();
        let call = build_api_call(current);
        xhr.open("GET", call, true);
        xhr.onload = function () {
            resolve(JSON.parse(xhr.responseText));
        };
        xhr.send();
    });
}

async function get_forecast()
{
    let call = build_api_call(forecast);
    let response = await fetch(call);
    let result = await response.json();
    return result;
}