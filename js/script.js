function updateClock() {
    var now = new Date();
    var datetime = now.toLocaleString();
    document.getElementById("datetime").innerHTML = datetime;

}
setInterval(updateClock, 1000);
updateClock();